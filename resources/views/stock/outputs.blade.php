<!-- resources/views/stock/outputs.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-10" x-data="{ open: false, selected: '', id: '', currentStock: '' }">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-50 dark:bg-red-950/30 rounded-2xl">
                    <i data-lucide="trending-down" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50">Saída de Materiais</h1>
                    <p class="text-gray-500 dark:text-gray-100 font-medium text-sm mt-1">Registre a baixa de produtos por
                        venda ou consumo interno.</p>
                </div>
            </div>
            <button type="submit" form="output-form"
                class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all cursor-pointer shadow-lg shadow-red-100 dark:shadow-none text-sm">
                Processar Saída
            </button>
        </div>

        <form id="output-form" action="{{ route('stock.store') }}" method="POST" class="grid grid-cols-12 gap-8">
            @csrf
            <input type="hidden" name="type" value="output">
            <input type="hidden" name="product_id" :value="id" required>

            <div class="col-span-12 lg:col-span-8">
                <div
                    class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs space-y-8">
                    <div class="space-y-4 relative">
                        <label
                            class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">Item
                            de Saída</label>

                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all cursor-pointer">
                            <span x-text="selected || 'Pesquisar produto no inventário...'"
                                :class="selected ? 'text-gray-800 dark:text-gray-50 font-bold' : 'text-gray-400'"></span>
                            <i data-lucide="package-search" class="w-5 h-5 text-gray-400"></i>
                        </button>

                        <div x-show="open" x-transition.opacity
                            class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl max-h-80 overflow-y-auto">
                            @foreach($products as $product)
                                <div @click="selected = '{{ $product->name }}'; id = '{{ $product->id }}'; currentStock = '{{ $product->stock_quantity }}'; open = false"
                                    class="px-6 py-4 hover:bg-red-50 dark:hover:bg-red-950/20 cursor-pointer transition-all flex items-center justify-between border-b last:border-0 border-gray-100 dark:border-gray-900 group">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-gray-800 dark:text-gray-50 tracking-tight">{{ $product->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Disponível:
                                            {{ $product->stock_quantity }} un</span>
                                    </div>
                                    <i data-lucide="arrow-right"
                                        class="w-4 h-4 text-gray-300 opacity-0 group-hover:opacity-100 transition-all"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4 pt-4">
                        <label
                            class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">Quantidade
                            Solicitada</label>
                        <input type="number" name="quantity" required min="1" placeholder="00"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-2xl font-black focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900 transition-all text-gray-800 dark:text-gray-50">
                        @error('quantity')
                            <p class="text-xs text-red-500 font-black mt-2 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div
                    class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs space-y-6 sticky top-24">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em]">Resumo do Fluxo</h3>
                    <div class="space-y-4">
                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] font-black text-gray-400 uppercase block mb-1">Saldo em
                                Prateleira</span>
                            <span class="text-2xl font-black text-gray-800 dark:text-gray-50"
                                x-text="currentStock || '0'"></span>
                        </div>
                        <p class="text-xs text-gray-400 font-medium leading-relaxed italic">
                            Atenção: Operações de saída debitam instantaneamente do patrimônio imobilizado. Certifique-se da
                            conferência física.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection