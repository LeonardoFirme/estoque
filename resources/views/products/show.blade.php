<!-- resources/views/products/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-10">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('produtos') }}"
                    class="p-2.5 bg-gray-50 dark:bg-gray-900 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 rounded-xl border border-gray-200 dark:border-gray-800 cursor-pointer transition-all">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">Ficha Técnica</span>
                    <h1 class="text-3xl font-black tracking-tighter text-gray-800 dark:text-gray-50">{{ $product->name }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('produtos.edit', $product->id) }}"
                    class="px-6 py-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-50 font-bold rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors cursor-pointer">
                    Editar Dados
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <div class="col-span-12 lg:col-span-4 space-y-8">
                <div
                    class="aspect-square w-full bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl flex items-center justify-center overflow-hidden p-8">
                    @if($product->image_path)
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                            class="max-w-full max-h-full object-contain drop-shadow-2xl">
                    @else
                        <div class="flex flex-col items-center gap-2 text-gray-200 dark:text-gray-800">
                            <i data-lucide="image" class="w-16 h-16"></i>
                            <span class="text-[10px] font-black uppercase">Sem Imagem</span>
                        </div>
                    @endif
                </div>

                <div
                    class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl space-y-6 shadow-xs">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Métricas de Patrimônio</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div
                            class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Preço de Tabela</span>
                            <span class="text-3xl font-black text-gray-800 dark:text-gray-50">R$
                                {{ number_format($product->price, 2, ',', '.') }}</span>
                        </div>
                        <div
                            class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Saldo Atual</span>
                                <span
                                    class="text-3xl font-black text-gray-800 dark:text-gray-50">{{ $product->stock_quantity }}
                                    <span class="text-sm">un</span></span>
                            </div>
                            @if($product->stock_quantity < $product->min_stock)
                                <span
                                    class="px-3 py-1 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 text-[10px] font-black uppercase rounded-lg border border-red-200 dark:border-red-900">Crítico</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8 space-y-8">
                <div
                    class="p-10 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs">
                    <div class="flex items-center gap-2 mb-8">
                        <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                        <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em]">Especificações do Produto
                        </h2>
                    </div>

                    <div class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-12 gap-y-10">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Part Number /
                                    SKU</span>
                                <p
                                    class="text-sm font-black text-gray-800 dark:text-gray-50 mt-2 font-mono bg-gray-50 dark:bg-gray-900 px-3 py-1.5 rounded-lg inline-block border border-gray-100 dark:border-gray-800 uppercase tracking-tighter">
                                    {{ $product->sku }}
                                </p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Código de Barras /
                                    EAN 13</span>
                                <p
                                    class="text-sm font-black text-gray-800 dark:text-gray-50 mt-2 font-mono bg-gray-50 dark:bg-gray-900 px-3 py-1.5 rounded-lg inline-block border border-gray-100 dark:border-gray-800 uppercase tracking-tighter">
                                    {{ $product->ean_13 }}
                                </p>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Inclusão no
                                    Sistema</span>
                                <p class="text-sm font-black text-gray-800 dark:text-gray-50 mt-2">
                                    {{ $product->created_at->format('d/m/Y') }} <span class="text-gray-400 font-medium">às
                                        {{ $product->created_at->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="pt-10 border-t border-gray-100 dark:border-gray-900">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Descrição
                                Comercial</span>
                            <div class="prose prose-sm dark:prose-invert max-w-none mt-6">
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed font-medium">
                                    {{ $product->description ?? 'Este produto ainda não possui uma descrição técnica detalhada cadastrada.' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-10 border-t border-gray-100 dark:border-gray-900">
                            <div class="flex items-center gap-2 mb-8">
                                <i data-lucide="history" class="w-4 h-4 text-gray-400"></i>
                                <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em]">Últimas
                                    Movimentações</h2>
                            </div>

                            <div class="space-y-4">
                                @php
                                    $movements = collect()
                                        ->merge($product->inputs->map(function ($item) {
                                            $item->log_type = 'input';
                                            return $item;
                                        }))
                                        ->merge($product->outputs->map(function ($item) {
                                            $item->log_type = 'output';
                                            return $item;
                                        }))
                                        ->sortByDesc('created_at')
                                        ->take(10);
                                @endphp

                                @forelse($movements as $movement)
                                    <div
                                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl group transition-all hover:border-gray-200 dark:hover:border-gray-700">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-xl flex items-center justify-center {{ $movement->log_type === 'input' ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600' : 'bg-red-50 dark:bg-red-950/30 text-red-600' }}">
                                                <i data-lucide="{{ $movement->log_type === 'input' ? 'arrow-down-left' : 'arrow-up-right' }}"
                                                    class="w-5 h-5"></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs font-black text-gray-800 dark:text-gray-50 uppercase tracking-tight">
                                                    {{ $movement->log_type === 'input' ? 'Entrada de Estoque' : 'Saída / Baixa' }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 font-medium">
                                                    {{ $movement->created_at->format('d/m/Y') }} às
                                                    {{ $movement->created_at->format('H:i') }}
                                                    •
                                                    {{ $movement->supplier ?? ($movement->type === 'sale' ? 'Venda Direta' : 'Avaria/Outros') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span
                                                class="text-sm font-black {{ $movement->log_type === 'input' ? 'text-emerald-500' : 'text-red-500' }}">
                                                {{ $movement->log_type === 'input' ? '+' : '-' }} {{ $movement->quantity }} un
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-mono font-bold">
                                                R$
                                                {{ number_format($movement->total_cost ?? $movement->total_price, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-gray-100 dark:border-gray-900 rounded-3xl">
                                        <i data-lucide="database-zap" class="w-8 h-8 text-gray-200 dark:text-gray-800 mb-2"></i>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nenhuma
                                            movimentação registrada</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('entradas') }}"
                        class="flex-1 p-6 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl flex items-center justify-between group transition-all hover:bg-emerald-100 dark:hover:bg-emerald-900/30">
                        <div class="flex flex-col">
                            <span
                                class="text-emerald-700 dark:text-emerald-400 font-black text-sm uppercase tracking-tight">Abastecer</span>
                            <span
                                class="text-[10px] text-emerald-600/60 dark:text-emerald-500/60 font-bold uppercase">Entrada
                                de estoque</span>
                        </div>
                        <i data-lucide="arrow-up-right"
                            class="w-5 h-5 text-emerald-600 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('saidas') }}"
                        class="flex-1 p-6 bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/50 rounded-2xl flex items-center justify-between group transition-all hover:bg-red-100 dark:hover:bg-red-900/30">
                        <div class="flex flex-col">
                            <span class="text-red-700 dark:text-red-400 font-black text-sm uppercase tracking-tight">Dar
                                Baixa</span>
                            <span class="text-[10px] text-red-600/60 dark:text-red-500/60 font-bold uppercase">Saída de
                                estoque</span>
                        </div>
                        <i data-lucide="arrow-down-right"
                            class="w-5 h-5 text-red-600 group-hover:translate-x-1 group-hover:translate-y-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection