<!-- resources/views/inputs/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8" x-data="{
            prodOpen: false,
            searchProd: '',
            selectedProd: '',
            prodLabel: 'SELECIONE O PRODUTO...',
            costPrice: '',
            displayCost: '',
            formatMoney(value) {
                let cleanValue = value.replace(/\D/g, '');
                let numberValue = (cleanValue / 100).toFixed(2);
                this.costPrice = numberValue;
                this.displayCost = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(numberValue);
            }
        }">
        <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-800 pb-8">
            <a href="{{ route('entradas') }}"
                class="p-2.5 bg-gray-50 dark:bg-gray-900 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 rounded-xl transition-all shadow-xs outline-none">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Nova Entrada</h1>
                <p class="text-gray-400 dark:text-gray-400 font-black uppercase text-[10px] tracking-widest mt-1">Reposição
                    de estoque manual e ajuste de custo</p>
            </div>
        </div>

        <form action="{{ route('entradas.store') }}" method="POST" class="space-y-6 bg-gray-50 dark:bg-gray-900 p-8 border border-gray-200/50 dark:border-gray-800/50 rounded">
            @csrf
            <div class="space-y-6">

                <div class="space-y-2 relative">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Selecionar
                        Produto</label>
                    <input type="hidden" name="product_id" x-model="selectedProd" required>

                    <button type="button"
                        @click="prodOpen = !prodOpen; if(prodOpen) $nextTick(() => $refs.searchField.focus())"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 flex items-center justify-between outline-none uppercase transition-all">
                        <span x-text="prodLabel"></span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform"
                            :class="prodOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="prodOpen" @click.away="prodOpen = false" x-cloak x-transition
                        class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-2xl overflow-hidden max-h-80 flex flex-col">

                        <div class="p-3 border-b border-gray-100 dark:border-gray-900 bg-gray-50/50 dark:bg-gray-900/50">
                            <input type="text" x-model="searchProd" x-ref="searchField"
                                placeholder="BUSCAR POR NOME OU SKU..."
                                class="w-full px-4 py-2 bg-white dark:bg-gray-900 rounded-lg text-[10px] font-black uppercase outline-none focus:ring-1 focus:ring-emerald-500 transition-all">
                        </div>

                        <div class="overflow-y-auto p-2 space-y-1">
                            @foreach($products as $product)
                                <button type="button"
                                    x-show="'{{ strtoupper($product->name) }} {{ strtoupper($product->sku) }}'.includes(searchProd.toUpperCase())"
                                    @click="selectedProd = '{{ $product->id }}'; prodLabel = '{{ $product->sku }} - {{ $product->name }}'; prodOpen = false; searchProd = ''; costPrice = '{{ $product->cost_price }}'; displayCost = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(costPrice)"
                                    class="w-full px-4 py-3 flex items-center gap-4 rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-gray-900 group">

                                    <div
                                        class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="package" class="w-4 h-4 text-gray-300"></i>
                                        @endif
                                    </div>

                                    <div class="flex flex-col text-left">
                                        <span
                                            class="text-[11px] font-black uppercase text-gray-800 dark:text-gray-200 group-hover:text-emerald-500 transition-colors">{{ $product->name }}</span>
                                        <span
                                            class="text-[9px] font-bold text-gray-400 font-mono tracking-tighter">{{ $product->sku }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Quantidade
                            Entrante</label>
                        <input type="number" name="quantity" min="1" required placeholder="0"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Novo Preço de
                            Custo</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-gray-400 font-black text-xs">R$</span>
                            <input type="text" x-model="displayCost" @input="formatMoney($event.target.value)" required
                                class="w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none">
                            <input type="hidden" name="cost_price" :value="costPrice">
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fornecedor /
                        Origem</label>
                    <input type="text" name="supplier" placeholder="DIGITE O NOME DO FORNECEDOR"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none uppercase transition-all">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-10 py-4 bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-950 dark:hover:bg-gray-200 transition-all shadow-xl cursor-pointer">
                    Efetivar Entrada
                </button>
            </div>
        </form>
    </div>
@endsection