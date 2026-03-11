<!-- resources/views/stock/entries.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-10" x-data="{ open: false, selected: '', id: '', currentStock: '' }">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl">
                    <i data-lucide="trending-up" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50">Entrada de Materiais
                    </h1>
                    <p class="text-gray-500 dark:text-gray-100 font-medium text-sm mt-1">Aumente o saldo de itens do
                        inventário de forma segura.</p>
                </div>
            </div>
            <button type="submit" form="entry-form"
                class="px-8 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-bold rounded-xl transition-all cursor-pointer shadow-lg shadow-gray-200 dark:shadow-none text-sm">
                Confirmar Lote
            </button>
        </div>

        <form id="entry-form" action="{{ route('stock.store') }}" method="POST" class="grid grid-cols-12 gap-8">
            @csrf
            <input type="hidden" name="type" value="entry">
            <input type="hidden" name="product_id" :value="id" required>

            <div class="col-span-12 lg:col-span-7">
                <div
                    class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs space-y-6">
                    <div class="space-y-4 relative">
                        <label
                            class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">Identificar
                            Produto</label>

                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all cursor-pointer group">
                            <span x-text="selected || 'Selecione o item no catálogo...'"
                                :class="selected ? 'text-gray-800 dark:text-gray-50 font-bold' : 'text-gray-400'"></span>
                            <i data-lucide="search"
                                class="w-5 h-5 text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-50 transition-colors"></i>
                        </button>

                        <div x-show="open" x-transition.opacity
                            class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-900">
                            @foreach($products as $product)
                                <div @click="selected = '{{ $product->name }}'; id = '{{ $product->id }}'; currentStock = '{{ $product->stock_quantity }}'; open = false"
                                    class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-900 cursor-pointer transition-all flex items-center justify-between group">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-gray-800 dark:text-gray-50 tracking-tight group-hover:translate-x-1 transition-transform">{{ $product->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">SKU:
                                            {{ $product->sku }}</span>
                                    </div>
                                    <span
                                        class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-black text-gray-500">Saldo:
                                        {{ $product->stock_quantity }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4 pt-4">
                        <label
                            class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">Volume
                            de Carga</label>
                        <input type="number" name="quantity" required min="1" placeholder="00"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-2xl font-black focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all text-gray-800 dark:text-gray-50 placeholder:text-gray-300">
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-5 space-y-6">
                <div class="p-8 bg-gray-800 dark:bg-gray-50 rounded-3xl shadow-xl space-y-6">
                    <h3 class="text-white dark:text-gray-950 font-black text-lg tracking-tighter">Status Pré-Movimentação
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-white/10 dark:border-gray-200 pb-4">
                            <span class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest">Saldo
                                Atual</span>
                            <span class="text-white dark:text-gray-950 font-black text-xl"
                                x-text="currentStock || '0'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest">Tipo
                                de Operação</span>
                            <span
                                class="px-3 py-1 bg-emerald-500/20 text-emerald-400 dark:text-emerald-600 rounded-lg text-[10px] font-black uppercase">Crédito
                                (Entrada)</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection