<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8" x-data="{
            openDelete: false,
            productName: '',
            deleteUrl: '',
            selected: [],
            selectAll: false,
            isBulk: false,
            toggleAll() {
                this.selectAll = !this.selectAll;
                this.selected = this.selectAll ? Array.from(document.querySelectorAll('.prod-checkbox')).map(el => el.value) : [];
            }
        }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Produtos</h1>
                <p class="text-gray-500 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">Gestão de catálogo e controle de estoque real.</p>
            </div>

            <div class="flex items-center gap-4 flex-1 max-w-2xl">
                <div x-show="selected.length > 0" x-cloak x-transition>
                    <button @click="productName = selected.length + ' ITENS SELECIONADOS'; deleteUrl = '{{ route('products.bulk-destroy') }}'; isBulk = true; openDelete = true"
                        class="px-4 py-3 bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400 rounded-xl text-[10px] font-black uppercase tracking-widest border-none cursor-pointer hover:bg-red-100 transition-all">
                        Excluir (<span x-text="selected.length"></span>)
                    </button>
                </div>

                <div class="relative w-full">
                    <form action="{{ route('produtos') }}" method="GET" class="relative group">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="BUSCAR POR NOME OU SKU..."
                            class="w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-950 rounded-xl text-[10px] font-black tracking-widest border border-gray-200 dark:border-gray-800 transition-all outline-none text-gray-800 dark:text-gray-50 uppercase">
                        @if(request('search'))
                            <a href="{{ route('produtos') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500"><i data-lucide="x" class="w-4 h-4"></i></a>
                        @endif
                    </form>
                </div>
                <a href="{{ route('produtos.create') }}" class="shrink-0 flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg text-[10px] uppercase tracking-widest border-none outline-none">
                    <i data-lucide="plus" class="w-4 h-4"></i> Novo
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-900">
                            <th class="px-6 py-5 text-center w-10">
                                <input type="checkbox" @click="toggleAll()" :checked="selectAll" class="w-4 h-4 rounded border-gray-300 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                            </th>
                            <th class="px-8 py-5">Item</th>
                            <th class="px-8 py-5">Identificador (SKU)</th>
                            <th class="px-8 py-5 text-right">Preço Unitário</th>
                            <th class="px-8 py-5 text-right">Saldo</th>
                            <th class="px-8 py-5 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-900">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-all group">
                                <td class="px-6 py-5 text-center">
                                    <input type="checkbox" value="{{ $product->id }}" x-model="selected" class="prod-checkbox w-4 h-4 rounded border border-gray-300 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-900 overflow-hidden shrink-0 flex items-center justify-center p-1 group-hover:border-emerald-500/20 transition-colors">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform">
                                            @else
                                                <i data-lucide="package" class="w-5 h-5 text-gray-300"></i>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-800 dark:text-gray-50 tracking-tight uppercase">{{ $product->name }}</span>
                                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">
                                                @if($product->category)
                                                    {{ $product->category->parent ? $product->category->parent->name . ' > ' : '' }}{{ $product->category->name }}
                                                @else
                                                    SEM CATEGORIA
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-xs font-mono font-bold text-gray-400 uppercase">{{ $product->sku }}</td>
                                <td class="px-8 py-5 text-sm text-right font-black text-gray-800 dark:text-gray-50">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                <td class="px-8 py-5 text-right">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 rounded-lg text-xs font-black text-gray-800 dark:text-gray-50">{{ $product->stock_quantity }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('produtos.show', $product->id) }}" class="p-2 text-gray-400 hover:text-gray-800 transition-colors"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                        <a href="{{ route('produtos.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-emerald-500 transition-colors"><i data-lucide="edit-3" class="w-4 h-4"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-8 py-20 text-center"><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nenhum produto localizado.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-6 border-t border-gray-100 dark:border-gray-900 bg-gray-50/10 dark:bg-gray-900/10 flex items-center justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Exibindo {{ $products->count() }} de {{ $products->total() }} itens</span>
                {{ $products->links() }}
            </div>
        </div>

        <template x-if="openDelete">
            <div class="fixed inset-0 z-60 flex items-center justify-center p-4 bg-gray-950/60 backdrop-blur-sm" x-transition.opacity>
                <div class="w-full max-w-sm p-8 bg-white dark:bg-gray-950 rounded-3xl shadow-2xl" @click.away="openDelete = false">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-4 mb-4 bg-red-50 dark:bg-red-950/30 rounded-2xl"><i data-lucide="alert-triangle" class="w-8 h-8 text-red-600"></i></div>
                        <h2 class="text-xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Confirmar</h2>
                        <p class="mt-2 text-sm text-gray-500 font-medium leading-relaxed">Excluir permanentemente: <span class="font-black text-gray-800 dark:text-gray-50" x-text="productName"></span>?</p>
                    </div>
                    <div class="flex items-center gap-3 mt-8">
                        <button @click="openDelete = false" class="flex-1 py-3 text-xs font-black uppercase text-gray-400 border-none bg-transparent cursor-pointer">Cancelar</button>
                        <form :action="deleteUrl" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <template x-if="isBulk">
                                <input type="hidden" name="ids" :value="selected.join(',')">
                            </template>
                            <button type="submit" class="w-full py-3 bg-red-600 text-white font-black rounded-xl text-[10px] uppercase border-none cursor-pointer">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection