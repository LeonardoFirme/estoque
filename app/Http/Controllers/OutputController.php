<?php
// app/Http/Controllers/OutputController.php
namespace App\Http\Controllers;

use App\Models\Output;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OutputController extends Controller
{
    public function index(Request $request): View
    {
        $query = Output::with(['product', 'employee']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"))
                    ->orWhereHas('employee', fn($e) => $e->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->get('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->get('end_date'));
        }

        $outputs = $query->latest()->paginate(10)->withQueryString();

        return view('outputs.index', compact('outputs'));
    }

    public function create(): View
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->get(['id', 'name', 'sku', 'price', 'stock_quantity', 'image_path'])
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => (float) $p->price,
                'stock' => (int) $p->stock_quantity,
                'image' => $p->image_path ? asset('storage/' . $p->image_path) : null
            ]);

        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'role'])
            ->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'role' => $e->role
            ]);

        return view('outputs.create', compact('products', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type' => ['required', 'string', 'in:sale,loss,withdrawal,return'],
            'payment_method' => ['required', 'string'],
            'manager_password' => ['nullable', 'string'],
            'global_discount' => ['nullable', 'numeric', 'min:0'],
            'withdrawal_value' => ['nullable', 'numeric', 'min:0'],
            'should_print' => ['nullable', 'string'],
            'items' => ['required_if:type,sale,loss,return', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $employee = Employee::findOrFail($validated['employee_id']);

            // Lógica de Desconto
            $hasDiscount = ((float) $validated['global_discount'] ?? 0) > 0;
            if (!$hasDiscount && isset($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    if (((float) $item['discount'] ?? 0) > 0) {
                        $hasDiscount = true;
                        break;
                    }
                }
            }

            // VALIDAÇÃO REAL DE SENHA (BCRYPT)
            if ($validated['type'] !== 'sale' || $hasDiscount) {
                if (!$validated['manager_password'] || !Hash::check($validated['manager_password'], $employee->password)) {
                    throw new \Exception('SENHA INVÁLIDA OU AUTORIZAÇÃO NEGADA PARA ESTE COLABORADOR.');
                }
                if (strtolower($employee->role) !== 'gerente') {
                    throw new \Exception('APENAS COLABORADORES COM CARGO DE GERENTE PODEM AUTORIZAR ESTA OPERAÇÃO.');
                }
            }

            $batchId = (string) Str::uuid();

            DB::transaction(function () use ($validated, $batchId) {
                if ($validated['type'] === 'withdrawal') {
                    Output::create([
                        'id' => $batchId,
                        'product_id' => null,
                        'employee_id' => $validated['employee_id'],
                        'quantity' => 1,
                        'unit_price' => $validated['withdrawal_value'],
                        'total_price' => $validated['withdrawal_value'],
                        'type' => 'withdrawal',
                        'payment_method' => 'money',
                        'installments' => 1,
                        'notes' => 'Retirada de caixa (Sangria)',
                    ]);
                    return;
                }

                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    if ($validated['type'] !== 'return' && $product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Estoque insuficiente para: {$product->name}");
                    }

                    $totalItem = ($product->price * $item['quantity']) - ($item['discount'] ?? 0);

                    Output::create([
                        'product_id' => $product->id,
                        'employee_id' => $validated['employee_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'total_price' => max(0, $totalItem - (($validated['global_discount'] ?? 0) / count($validated['items']))),
                        'type' => $validated['type'],
                        'payment_method' => $validated['payment_method'],
                        'installments' => 1,
                        'notes' => "Lote ID: {$batchId}",
                    ]);

                    $validated['type'] === 'return' ? $product->increment('stock_quantity', $item['quantity']) : $product->decrement('stock_quantity', $item['quantity']);
                }
            });

            $response = redirect()->route('saidas.create')->with('success', 'OPERAÇÃO REALIZADA.');
            if ($request->get('should_print') === 'true')
                $response->with('print_id', $batchId);
            return $response;

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function receipt($id)
    {
        $items = Output::with(['product', 'employee'])
            ->where('id', $id)
            ->orWhere('notes', 'like', "%{$id}%")
            ->get();

        if ($items->isEmpty()) {
            abort(404);
        }

        $first = $items->first();

        // Se for sangria, usa o template de sangria
        if ($first->type === 'withdrawal') {
            return Pdf::loadView('outputs.receipt-withdrawal', ['output' => $first])
                ->setPaper([0, 0, 226.77, 600], 'portrait')
                ->stream("recibo-sangria-{$id}.pdf");
        }

        $totalGross = $items->sum(fn($i) => (float) $i->unit_price * (int) $i->quantity);
        $totalNet = $items->sum(fn($i) => (float) $i->total_price);

        $data = [
            'items' => $items,
            'saleId' => strtoupper(substr($id, 0, 8)),
            'saleDate' => $first->created_at->format('d/m/Y H:i:s'),
            'employeeName' => $first->employee->name ?? 'N/A',
            'paymentMethod' => match ($first->payment_method) {
                'money' => 'DINHEIRO',
                'debit' => 'DÉBITO',
                'credit' => 'CRÉDITO',
                default => 'OUTRO'
            },
            'installments' => $first->installments,
            'totalGross' => $totalGross,
            'totalDiscount' => $totalGross - $totalNet,
            'totalNet' => $totalNet,
        ];

        return Pdf::loadView('outputs.receipt', $data)
            ->setPaper([0, 0, 226.77, 600], 'portrait')
            ->stream("cupom-{$id}.pdf");
    }

    public function discountAudit(Request $request): View
    {
        $start_date = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->get('end_date', now()->format('Y-m-d'));

        $query = Output::with(['product', 'employee'])
            ->where('type', 'sale')
            ->whereBetween('created_at', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);

        $allOutputs = $query->get();

        $outputsWithDiscount = $allOutputs->filter(function ($output) {
            return ($output->unit_price * $output->quantity) > $output->total_price;
        });

        $grossTotal = $allOutputs->sum(fn($o) => $o->unit_price * $o->quantity);
        $netTotal = $allOutputs->sum('total_price');

        $totals = [
            'gross' => (float) $grossTotal,
            'discounts' => (float) ($grossTotal - $netTotal),
            'net' => (float) $netTotal
        ];

        return view('outputs.discounts', [
            'outputs' => $outputsWithDiscount,
            'totals' => $totals
        ]);
    }

    public function commissions(Request $request): View
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $employees = Employee::with([
            'outputs' => function ($query) use ($month, $year) {
                $query->where('type', 'sale')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
            }
        ])->get()->map(function ($employee) {
            $totalSales = (float) $employee->outputs->sum('total_price');
            $employee->total_sales = $totalSales;
            $employee->commission = $totalSales * 0.05;
            return $employee;
        });

        return view('outputs.commissions', compact('employees', 'month', 'year'));
    }

    public function dailyReport(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : today();

        // Busca todas as movimentações do dia [cite: 2026-03-08]
        $outputs = Output::whereDate('created_at', $date)->get();

        // Filtragem precisa por método de pagamento [cite: 2026-03-08]
        $sales = $outputs->where('type', 'sale');
        $moneyIn = (float) $sales->where('payment_method', 'money')->sum('total_price');
        $debitIn = (float) $sales->where('payment_method', 'debit')->sum('total_price');
        $creditIn = (float) $sales->where('payment_method', 'credit')->sum('total_price');

        // Sangrias totais [cite: 2026-03-08]
        $totalWithdrawals = (float) $outputs->where('type', 'withdrawal')->sum('total_price');

        // Faturamento Total = Soma de todas as formas de pagamento [cite: 2026-03-10]
        $totalFaturamento = $moneyIn + $debitIn + $creditIn;

        $data = [
            'date' => $date->format('d/m/Y'),
            'money_in' => $moneyIn,
            'debit' => $debitIn,
            'credit' => $creditIn,
            'total_gross' => $totalFaturamento,
            'count' => $sales->count(),
            'withdrawals' => $totalWithdrawals,
            'net_cash_balance' => $moneyIn - $totalWithdrawals // Apenas o fluxo de espécie [cite: 2026-03-08]
        ];

        return Pdf::loadView('outputs.report', compact('data'))
            ->setPaper([0, 0, 204.1, 650], 'portrait')
            ->stream('fechamento-caixa-' . $date->format('d-m-Y') . '.pdf');
    }

    public function dailySummary(Request $request): View
    {
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : today();

        $outputs = Output::whereDate('created_at', $date)->get();
        $sales = $outputs->where('type', 'sale');
        $withdrawals = $outputs->where('type', 'withdrawal');

        $data = [
            'selected_date' => $date->format('Y-m-d'),
            'display_date' => $date->format('d/m/Y'),
            'total_revenue' => (float) $sales->sum('total_price'),
            'total_items' => (int) $sales->sum('quantity'),
            'total_withdrawals' => (float) $withdrawals->sum('total_price'),
            'methods' => [
                'money' => (float) $sales->where('payment_method', 'money')->sum('total_price'),
                'debit' => (float) $sales->where('payment_method', 'debit')->sum('total_price'),
                'credit' => (float) $sales->where('payment_method', 'credit')->sum('total_price'),
            ]
        ];

        return view('outputs.daily-summary', compact('data'));
    }
}