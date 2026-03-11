<!-- resources/views/outputs/discounts.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Auditoria de
                    Descontos</h1>
                <p class="text-gray-500 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">Relatório
                    de abatimentos autorizados por período</p>
            </div>

            <form action="{{ route('saidas.descontos') }}" method="GET" class="flex items-center gap-3">
                <div
                    class="flex items-center bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl px-4 py-2 gap-3">
                    <input type="date" name="start_date"
                        value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                        class="bg-transparent text-[10px] font-black text-gray-800 dark:text-gray-50 outline-none uppercase">
                    <span class="text-gray-300 dark:text-gray-700 font-black text-[10px]">ATÉ</span>
                    <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                        class="bg-transparent text-[10px] font-black text-gray-800 dark:text-gray-50 outline-none uppercase">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg text-[10px] uppercase tracking-widest cursor-pointer outline-none border-none">
                    Filtrar Período
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs">
                <span
                    class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] block mb-2">Total
                    Bruto (Sem Descontos)</span>
                <h2 class="text-3xl font-black text-gray-800 dark:text-gray-50 tracking-tighter">
                    R$ {{ number_format($totals['gross'], 2, ',', '.') }}
                </h2>
            </div>
            <div class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs">
                <span class="text-[9px] font-black text-red-500 uppercase tracking-[0.2em] block mb-2">Total de
                    Abatimentos</span>
                <h2 class="text-3xl font-black text-red-500 tracking-tighter">
                    - R$ {{ number_format($totals['discounts'], 2, ',', '.') }}
                </h2>
            </div>
            <div
                class="p-8 bg-white dark:bg-gray-950 border border-emerald-500/20 dark:border-emerald-500/10 rounded-3xl shadow-xs">
                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.2em] block mb-2">Faturamento
                    Líquido</span>
                <h2 class="text-3xl font-black text-gray-800 dark:text-gray-50 tracking-tighter">
                    R$ {{ number_format($totals['net'], 2, ',', '.') }}
                </h2>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-4xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-gray-900">
                            <th class="px-8 py-5">Data / Hora</th>
                            <th class="px-8 py-5">Produto</th>
                            <th class="px-8 py-5">Vendedor</th>
                            <th class="px-8 py-5 text-right">Valor Original</th>
                            <th class="px-8 py-5 text-right">Desconto Aplicado</th>
                            <th class="px-8 py-5 text-right">Valor Final</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-900">
                        @forelse($outputs as $output)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-all">
                                <td class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase">
                                    {{ $output->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-gray-800 dark:text-gray-50 uppercase tracking-tight">{{ $output->product->name ?? 'N/A' }}</span>
                                        <span class="text-[9px] font-mono font-bold text-gray-400">SKU:
                                            {{ $output->product->sku ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-xs font-black text-gray-800 dark:text-gray-50 uppercase">
                                    {{ $output->employee->name }}
                                </td>
                                <td class="px-8 py-5 text-right text-xs font-bold text-gray-400">
                                    R$ {{ number_format($output->unit_price * $output->quantity, 2, ',', '.') }}
                                </td>
                                <td class="px-8 py-5 text-right text-xs font-black text-red-500">
                                    - R$
                                    {{ number_format(($output->unit_price * $output->quantity) - $output->total_price, 2, ',', '.') }}
                                </td>
                                <td class="px-8 py-5 text-right text-sm font-black text-gray-800 dark:text-gray-50">
                                    R$ {{ number_format($output->total_price, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-8 py-20 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    Nenhuma movimentação com desconto encontrada no período
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection