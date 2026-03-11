<?php
// app/Http/Controllers/StockController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Input;
use App\Models\Output;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function entries(): View
    {
        $inputs = Input::with('product')->latest()->paginate(15);
        return view('stock.entries', compact('inputs'));
    }

    public function outputs(): View
    {
        $outputs = Output::with('product')->latest()->paginate(15);
        return view('stock.outputs', compact('outputs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:entry,output'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);

            if ($validated['type'] === 'entry') {
                // Registro de Entrada Técnica
                Input::create([
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'cost_price' => $validated['price'],
                    'total_cost' => $validated['price'] * $validated['quantity'],
                    'supplier' => $validated['reason'] ?? 'Ajuste de Inventário',
                ]);

                $product->increment('stock_quantity', $validated['quantity']);
                $message = 'Entrada de estoque registrada com sucesso.';
            } else {
                // Validação de Saída Técnica
                if ($product->stock_quantity < $validated['quantity']) {
                    return back()->withErrors(['quantity' => 'Saldo insuficiente para realizar a baixa.']);
                }

                Output::create([
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $validated['price'],
                    'total_price' => $validated['price'] * $validated['quantity'],
                    'type' => 'loss', // Definido como perda/ajuste por padrão neste controller
                    'notes' => $validated['reason'] ?? 'Saída por ajuste de estoque',
                ]);

                $product->decrement('stock_quantity', $validated['quantity']);
                $message = 'Saída de estoque registrada com sucesso.';
            }

            return redirect()->route('dashboard')->with('success', $message);
        });
    }
}