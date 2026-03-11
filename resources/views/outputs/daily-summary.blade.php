<!-- resources/views/outputs/daily-summary.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Fechamento de
                    Caixa</h1>
                <p class="text-gray-500 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">Resumo
                    consolidado por período</p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <form action="{{ route('saidas.resumo') }}" method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $data['selected_date'] }}" onchange="this.form.submit()"
                        class="px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black text-gray-800 dark:text-gray-50 uppercase tracking-widest outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                </form>

                <a href="{{ route('saidas.relatorio', ['date' => $data['selected_date']]) }}" target="_blank"
                    class="flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg text-[10px] uppercase tracking-widest cursor-pointer border-none outline-none">
                    <i data-lucide="printer" class="w-4 h-4"></i> Imprimir {{ $data['display_date'] }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs transition-all">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <i data-lucide="banknote" class="w-6 h-6 text-gray-800 dark:text-gray-50"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Saldo
                        Bruto em Vendas</span>
                </div>
                <h2 class="text-3xl font-black text-gray-800 dark:text-gray-50 tracking-tighter">
                    R$ {{ number_format($data['total_revenue'], 2, ',', '.') }}
                </h2>
                <div class="mt-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Referente ao dia
                        {{ $data['display_date'] }}</span>
                </div>
            </div>

            <div
                class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs transition-all">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <i data-lucide="package-check" class="w-6 h-6 text-gray-800 dark:text-gray-50"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total de
                        Itens Saídos</span>
                </div>
                <h2 class="text-3xl font-black text-gray-800 dark:text-gray-50 tracking-tighter">
                    {{ number_format($data['total_items'], 0, ',', '.') }} un
                </h2>
                <div class="mt-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Volume movimentado no
                        período</span>
                </div>
            </div>

            <div
                class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs transition-all">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <i data-lucide="arrow-down-to-dot" class="w-6 h-6 text-gray-800 dark:text-gray-50"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total em
                        Sangrias</span>
                </div>
                <h2 class="text-3xl font-black text-gray-800 dark:text-gray-50 tracking-tighter">
                    R$ {{ number_format($data['total_withdrawals'], 2, ',', '.') }}
                </h2>
                <div class="mt-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Retiradas manuais
                        autorizadas</span>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-900 bg-gray-50/10 dark:bg-gray-900/10">
                <h3 class="text-xs font-black text-gray-800 dark:text-gray-50 uppercase tracking-widest">Detalhamento
                    Financeiro por Método</h3>
            </div>
            <div
                class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-gray-900">
                <div class="px-8 py-6 flex flex-col gap-1 hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Dinheiro / Cash</span>
                    <span class="text-lg font-black text-gray-800 dark:text-gray-50">R$
                        {{ number_format($data['methods']['money'], 2, ',', '.') }}</span>
                </div>
                <div class="px-8 py-6 flex flex-col gap-1 hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Cartão de Débito</span>
                    <span class="text-lg font-black text-gray-800 dark:text-gray-50">R$
                        {{ number_format($data['methods']['debit'], 2, ',', '.') }}</span>
                </div>
                <div class="px-8 py-6 flex flex-col gap-1 hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Cartão de Crédito</span>
                    <span class="text-lg font-black text-gray-800 dark:text-gray-50">R$
                        {{ number_format($data['methods']['credit'], 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection