<!-- resources/views/inputs/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Histórico de
                    Entradas</h1>
                <p class="text-gray-500 font-medium mt-1 uppercase text-[10px] tracking-widest dark:text-gray-400">Gestão de
                    reposição de estoque e controle de custos.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('entradas.create') }}"
                    class="flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg shadow-gray-100 dark:shadow-none cursor-pointer text-[10px] uppercase tracking-widest">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Nova Entrada
                </a>
            </div>
        </div>

        <form action="{{ route('entradas') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-gray-50/50 dark:bg-gray-900/20 p-6 rounded-3xl border border-gray-100 dark:border-gray-800">
            <div class="md:col-span-2 relative">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Busca
                    Geral</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="PRODUTO, SKU OU FORNECEDOR..."
                        class="w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black tracking-widest focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all dark:text-gray-50">
                </div>
            </div>

            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Data
                    Inicial</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-4 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all dark:text-gray-50">
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Data
                        Final</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all dark:text-gray-50">
                </div>
                <button type="submit"
                    class="p-3.5 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 rounded-xl transition-all shadow-md cursor-pointer">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                </button>
                @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                    <a href="{{ route('entradas') }}"
                        class="p-3.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-400 hover:text-red-500 rounded-xl transition-all shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>

        <div
            class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-gray-900">
                            <th class="px-8 py-5">Data/Hora</th>
                            <th class="px-8 py-5">Produto / Fornecedor</th>
                            <th class="px-8 py-5 text-center">Qtd</th>
                            <th class="px-8 py-5 text-right">Unitário</th>
                            <th class="px-8 py-5 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-900">
                        @forelse($inputs as $input)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-all">
                                <td class="px-8 py-5 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">
                                    {{ $input->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-gray-800 dark:text-gray-50 tracking-tight uppercase">{{ $input->product->name }}</span>
                                        <span class="text-[10px] text-emerald-500 font-mono font-bold uppercase">Origem:
                                            {{ $input->supplier ?? 'NÃO INFORMADO' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span
                                        class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-lg font-black text-xs">
                                        + {{ $input->quantity }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm text-right font-bold text-gray-400 dark:text-gray-500">
                                    R$ {{ number_format($input->cost_price, 2, ',', '.') }}
                                </td>
                                <td class="w-40 px-8 py-5 text-sm text-right font-black text-gray-800 dark:text-gray-50">
                                    R$ {{ number_format($input->total_cost, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="search-x" class="w-8 h-8 text-gray-200"></i>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nenhum
                                            registro de entrada encontrado.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div
                class="px-8 py-6 border-t border-gray-100 dark:border-gray-900 bg-gray-50/10 dark:bg-gray-900/10 flex items-center justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    Exibindo {{ $inputs->count() }} de {{ $inputs->total() }} registros
                </span>
                {{ $inputs->links() }}
            </div>
        </div>
    </div>
@endsection