<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Visão Geral</h1>
                <p class="text-gray-500 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">Monitoramento analítico de inventário e fluxo</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('saidas.create') }}" target="_blank"
                    class="flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-black rounded-xl transition-all shadow-lg shadow-emerald-200 dark:shadow-none text-[10px] uppercase tracking-widest">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> Frente de Caixa
                </a>
                <a href="{{ route('produtos.create') }}"
                    class="flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg shadow-gray-200 dark:shadow-none text-[10px] uppercase tracking-widest">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Novo Produto
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl transition-all hover:border-gray-300 dark:hover:border-gray-700 shadow-xs">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Patrimônio em Itens</p>
                        </div>
                        <h3 class="text-5xl font-black tracking-tighter text-gray-800 dark:text-gray-50">{{ $stats['total'] }}</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Produtos ativos</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-2xl">
                        <i data-lucide="layers" class="w-6 h-6 text-gray-800 dark:text-gray-50"></i>
                    </div>
                </div>
            </div>

            <div class="group p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl transition-all hover:border-red-200 dark:hover:border-red-900 shadow-xs">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            <p class="text-xs font-black text-red-500 uppercase tracking-widest">Estoque Crítico</p>
                        </div>
                        <h3 class="text-5xl font-black tracking-tighter text-gray-800 dark:text-gray-50">{{ $stats['low_stock'] }}</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Abaixo do estoque mínimo</p>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-950/30 rounded-2xl">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-red-500"></i>
                    </div>
                </div>
            </div>

            <div class="group p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl transition-all hover:border-emerald-200 dark:hover:border-emerald-900 shadow-xs">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <p class="text-xs font-black text-emerald-500 uppercase tracking-widest">Capital Imobilizado</p>
                        </div>
                        <h3 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50">
                            <span class="text-xl align-top mr-1 uppercase">R$</span>{{ number_format($stats['value'], 2, ',', '.') }}
                        </h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Valor de custo total</p>
                    </div>
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl">
                        <i data-lucide="wallet" class="w-6 h-6 text-emerald-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-12 lg:col-span-8 space-y-8">
                <div class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl p-8 shadow-xs">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-3">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 text-gray-400"></i>
                            <h2 class="font-black text-gray-800 dark:text-gray-50 tracking-tight uppercase text-sm">Faturamento Diário (7 Dias)</h2>
                        </div>
                    </div>
                    <div class="h-48 flex items-end justify-between gap-3 px-4">
                        @foreach($chartData as $data)
                            <div class="flex-1 flex flex-col items-center gap-3 group relative">
                                <div class="w-full bg-emerald-500/10 group-hover:bg-emerald-500/30 rounded-t-xl transition-all relative h-[{{ round($data['percentage']) }}%]">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[9px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        R$ {{ number_format($data['value'], 2, ',', '.') }}
                                    </div>
                                </div>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ $data['day'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs overflow-hidden h-fit">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50/30 dark:bg-gray-900/30">
                        <div class="flex items-center gap-3">
                            <i data-lucide="list" class="w-5 h-5 text-gray-400"></i>
                            <h2 class="font-black text-gray-800 dark:text-gray-50 tracking-tight uppercase text-sm">Monitoramento de Itens</h2>
                        </div>
                        <a href="{{ route('produtos') }}" class="text-[10px] font-black text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors uppercase tracking-widest">Gerenciar Inventário</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-gray-900">
                                    <th class="px-8 py-5">Produto</th>
                                    <th class="px-8 py-5 text-right">Saldo</th>
                                    <th class="px-8 py-5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-900">
                                @foreach($products as $product)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-all">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 overflow-hidden flex items-center justify-center p-1">
                                                    @if($product->image_path)
                                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-full object-contain">
                                                    @else
                                                        <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-black text-gray-800 dark:text-gray-50 tracking-tight uppercase">{{ $product->name }}</span>
                                                    <span class="text-[10px] text-gray-400 font-mono font-bold uppercase tracking-tighter">{{ $product->sku }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-sm text-right font-black">
                                            {{ $product->stock_quantity }}
                                        </td>
                                        <td class="px-8 py-5 text-center text-[10px] font-black uppercase tracking-widest">
                                            @if($product->stock_quantity <= $product->min_stock)
                                                <span class="text-red-500 border border-red-100 dark:border-red-900/50 px-2 py-1 rounded-md bg-red-50 dark:bg-red-950/20">Crítico</span>
                                            @else
                                                <span class="text-emerald-500 border border-emerald-100 dark:border-emerald-900/50 px-2 py-1 rounded-md bg-emerald-50 dark:bg-emerald-950/20">Saudável</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="p-8 bg-gray-800 dark:bg-gray-50 rounded-3xl shadow-xl shadow-gray-200 dark:shadow-none">
                    <h3 class="text-white dark:text-gray-950 font-black text-lg tracking-tighter uppercase">Ações Rápidas</h3>
                    <div class="mt-8 space-y-3">
                        <a href="{{ route('entradas') }}" class="flex items-center justify-between p-4 bg-white/10 dark:bg-gray-950/5 hover:bg-white/20 dark:hover:bg-gray-950/10 rounded-2xl border border-white/10 dark:border-gray-200 transition-all group">
                            <span class="text-white dark:text-gray-950 font-black text-sm uppercase">Registrar Entrada</span>
                            <i data-lucide="plus" class="w-4 h-4 text-white dark:text-gray-950 group-hover:scale-125 transition-transform"></i>
                        </a>
                        <a href="{{ route('saidas') }}" class="flex items-center justify-between p-4 bg-white/10 dark:bg-gray-950/5 hover:bg-white/20 dark:hover:bg-gray-950/10 rounded-2xl border border-white/10 dark:border-gray-200 transition-all group">
                            <span class="text-white dark:text-gray-950 font-black text-sm uppercase">Registrar Saída</span>
                            <i data-lucide="minus" class="w-4 h-4 text-white dark:text-gray-950 group-hover:scale-125 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <div class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Recém Adicionados</h4>
                    <div class="space-y-4">
                        @forelse($recentProducts as $recent)
                            <a href="{{ route('produtos.show', $recent->id) }}" class="flex items-center gap-3 group">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($recent->image_path)
                                        <img src="{{ asset('storage/' . $recent->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="package" class="w-4 h-4 text-gray-300"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-black text-gray-800 dark:text-gray-50 truncate uppercase tracking-tight">{{ $recent->name }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Incluso em {{ $recent->created_at->format('d/m/Y') }}</span>
                                </div>
                            </a>
                        @empty
                            <p class="text-[10px] text-gray-400 font-black uppercase text-center py-4 tracking-widest">Nenhum item recente</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection