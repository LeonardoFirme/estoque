<?php
// app/Http/Controllers/InputController.php
namespace App\Http\Controllers;

use App\Models\Input;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InputController extends Controller
{
    /**
     * Listagem com filtros de busca e período.
     */
    public function index(Request $request): View
    {
        $query = Input::with('product');

        // Filtro de Busca (Produto, SKU ou Fornecedor)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('sku', 'LIKE', "%{$search}%");
                })->orWhere('supplier', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por Período
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $inputs = $query->latest()->paginate(10)->withQueryString();

        return view('inputs.index', compact('inputs'));
    }

    /**
     * Formulário de nova entrada.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get();
        return view('inputs.create', compact('products'));
    }

    /**
     * Processamento da entrada com atualização de custo e estoque.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255', 'uppercase'],
        ]);

        return DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            $totalCost = $validated['cost_price'] * $validated['quantity'];

            // 1. Registra a movimentação de entrada
            Input::create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'cost_price' => $validated['cost_price'],
                'total_cost' => $totalCost,
                'supplier' => $validated['supplier'],
            ]);

            // 2. Atualiza o Custo do Produto no cadastro (Custo da última compra)
            // Em ERPs de alta precisão, você pode optar por Custo Médio aqui.
            $product->cost_price = $validated['cost_price'];

            // 3. Incrementa o saldo físico
            $product->increment('stock_quantity', $validated['quantity']);

            $product->save();

            return redirect()->route('entradas')->with('success', 'ESTOQUE ATUALIZADO COM SUCESSO.');
        });
    }

    /**
     * Exclusão de registro (Opcional: Estorna o estoque se necessário)
     */
    public function destroy(Input $input): RedirectResponse
    {
        return DB::transaction(function () use ($input) {
            $product = $input->product;

            // Estorna a quantidade do estoque
            if ($product->stock_quantity >= $input->quantity) {
                $product->decrement('stock_quantity', $input->quantity);
            }

            $input->delete();

            return redirect()->route('entradas')->with('success', 'ENTRADA REMOVIDA E ESTOQUE ESTORNADO.');
        });
    }
}