<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\{Product, Output, Category};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\{Storage, DB};
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('category')->latest()->paginate(10);
        $recentProducts = Product::latest()->take(5)->get();

        $chartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $value = Output::whereDate('created_at', $date)->where('type', 'sale')->sum('total_price');
            $chartData->push(['day' => $date->isoFormat('ddd'), 'value' => (float) $value]);
        }

        $maxVal = $chartData->max('value') ?: 1;
        $chartData = $chartData->map(function ($item) use ($maxVal) {
            $item['percentage'] = ($item['value'] / $maxVal) * 100;
            return $item;
        });

        $stats = [
            'total' => (int) Product::count(),
            'low_stock' => (int) Product::whereColumn('stock_quantity', '<=', 'min_stock')->count(),
            'value' => (float) Product::query()->sum(DB::raw('(cost_price + operational_cost) * stock_quantity'))
        ];

        return view('dashboard', compact('products', 'stats', 'recentProducts', 'chartData'));
    }

    public function list(Request $request): View
    {
        $query = Product::with(['category', 'category.parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sku' => ['required', 'string', 'unique:products,sku'],
            'ean_13' => ['nullable', 'string', 'max:13', 'unique:products,ean_13'],
            'ncm' => ['nullable', 'string', 'max:8'],
            'category_id' => ['required', 'exists:categories,id'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'operational_cost' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'iof_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $data = collect($validated)->except(['image'])->toArray();

            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            Product::create($data);

            return redirect()->route('produtos')->with('success', 'PRODUTO REGISTRADO COM SUCESSO.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'ERRO AO SALVAR: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Product $product): View
    {
        $history = collect()
            ->merge($product->inputs->map(fn($i) => ['date' => $i->created_at, 'type' => 'input', 'label' => 'Entrada', 'quantity' => $i->quantity, 'price' => $i->cost_price]))
            ->merge($product->outputs->map(fn($o) => ['date' => $o->created_at, 'type' => 'output', 'label' => $o->type === 'sale' ? 'Venda' : 'Ajuste', 'quantity' => $o->quantity, 'price' => $o->unit_price]))
            ->sortByDesc('date');

        return view('products.show', compact('product', 'history'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sku' => ['required', 'string', 'unique:products,sku,' . $product->id],
            'ean_13' => ['nullable', 'string', 'size:13', 'unique:products,ean_13,' . $product->id],
            'ncm' => ['nullable', 'string', 'max:8'],
            'category_id' => ['required', 'exists:categories,id'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'operational_cost' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'iof_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = collect($validated)->except(['image'])->toArray();

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->fill($data);
        $product->save();

        return redirect()->route('produtos')->with('success', 'PRODUTO ATUALIZADO COM SUCESSO.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->delete();
            return redirect()->route('produtos')->with('success', 'PRODUTO REMOVIDO COM SUCESSO.');
        } catch (\Exception $e) {
            return back()->with('error', 'ITEM VINCULADO A MOVIMENTAÇÕES.');
        }
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = explode(',', $request->input('ids'));
        $ids = array_filter($ids);

        if (empty($ids)) {
            return back()->with('error', 'NENHUM ITEM SELECIONADO.');
        }

        try {
            DB::transaction(function () use ($ids) {
                $products = Product::whereIn('id', $ids)->get();
                foreach ($products as $product) {
                    if ($product->image_path) {
                        Storage::disk('public')->delete($product->image_path);
                    }
                    $product->delete();
                }
            });
            return redirect()->route('produtos')->with('success', count($ids) . ' PRODUTOS REMOVIDOS.');
        } catch (\Exception $e) {
            return back()->with('error', 'ALGUNS ITENS POSSUEM VÍNCULOS E NÃO FORAM REMOVIDOS.');
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        return response()->json(Product::where('name', 'LIKE', "%{$query}%")->orWhere('sku', 'LIKE', "%{$query}%")->limit(5)->get(['id', 'name', 'sku', 'image_path']));
    }
}