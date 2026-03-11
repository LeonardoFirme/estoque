<!-- resources/views/products/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8" x-data="{
            tab: 'geral',
            imagePreview: null,
            eanValid: true,
            cost: 0, opCost: 0, price: 0, iof: 0,
            displayCost: '', displayOpCost: '', displayPrice: '', displayIof: '',
            category: '', catLabel: 'SELECIONE UMA CATEGORIA...', catOpen: false, searchCat: '',
            get margin() {
                let totalCost = parseFloat(this.cost) + parseFloat(this.opCost);
                if (totalCost <= 0 || !this.price) return 0;
                return (((this.price - totalCost) / totalCost) * 100).toFixed(2);
            },
            formatMoney(value, type) {
                let cleanValue = value.replace(/\D/g, '');
                if (type === 'iof') {
                    let numberValue = (cleanValue / 10).toFixed(1);
                    this.iof = parseFloat(numberValue);
                    this.displayIof = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 1 }).format(numberValue) + '%';
                } else {
                    let numberValue = (cleanValue / 100).toFixed(2);
                    if (type === 'cost') { this.cost = parseFloat(numberValue); this.displayCost = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(numberValue); }
                    else if (type === 'opCost') { this.opCost = parseFloat(numberValue); this.displayOpCost = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(numberValue); }
                    else { this.price = parseFloat(numberValue); this.displayPrice = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(numberValue); }
                }
            },
            validateEAN(ean) {
                if (!ean || ean.length !== 13) { this.eanValid = false; return; }
                let sum = 0;
                for (let i = 0; i < 12; i++) sum += parseInt(ean[i]) * (i % 2 === 0 ? 1 : 3);
                let checkDigit = (10 - (sum % 10)) % 10;
                this.eanValid = checkDigit === parseInt(ean[12]);
            },
            handleScanner(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const focusable = Array.from(document.querySelectorAll('input, textarea, button'));
                    const index = focusable.indexOf(e.target);
                    if (index > -1 && focusable[index + 1]) focusable[index + 1].focus();
                }
            }
        }">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('produtos') }}" class="p-2.5 bg-gray-50 dark:bg-gray-900 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 rounded-xl border border-gray-200 dark:border-gray-800 transition-all shadow-xs outline-none"><i data-lucide="chevron-left" class="w-5 h-5"></i></a>
                <div>
                    <h1 class="text-2xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Novo Produto</h1>
                    <p class="text-gray-400 dark:text-gray-300 text-[10px] font-black uppercase tracking-widest mt-1">Módulo de Cadastro Profissional</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" form="product-form" class="px-8 py-2.5 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-sm text-[10px] uppercase tracking-widest border-none cursor-pointer">Salvar Registro</button>
            </div>
        </div>

        <div class="flex items-center gap-8 border-b border-gray-200 dark:border-gray-800">
            <button @click="tab = 'geral'" :class="tab === 'geral' ? 'border-gray-800 dark:border-gray-50 text-gray-800 dark:text-gray-50' : 'border-transparent text-gray-400'" class="pb-4 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all cursor-pointer outline-none">Geral</button>
            <button @click="tab = 'financeiro'" :class="tab === 'financeiro' ? 'border-gray-800 dark:border-gray-50 text-gray-800 dark:text-gray-50' : 'border-transparent text-gray-400'" class="pb-4 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all cursor-pointer outline-none">Financeiro</button>
            <button @click="tab = 'estoque'" :class="tab === 'estoque' ? 'border-gray-800 dark:border-gray-50 text-gray-800 dark:text-gray-50' : 'border-transparent text-gray-400'" class="pb-4 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all cursor-pointer outline-none">Estoque</button>
        </div>

        @if ($errors->any())
            <div class="p-5 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-2xl">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase tracking-tight">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="w-full bg-gray-50 dark:bg-gray-900 p-8 border border-gray-200/50 dark:border-gray-800/50 rounded">
            @csrf

            <div x-show="tab === 'geral'" x-transition.opacity class="grid grid-cols-12 gap-8">
                <div class="col-span-12 lg:col-span-2">
                    <div class="text-center">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">Imagem do Item</label>
                        <div class="relative group aspect-square rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 flex items-center justify-center overflow-hidden transition-all hover:border-emerald-500/50">
                            <div x-show="!imagePreview" class="text-center p-4">
                                <i data-lucide="image-plus" class="w-8 h-8 text-gray-300 mx-auto"></i>
                                <p class="text-[9px] font-black text-gray-400 uppercase mt-1">Upload</p>
                            </div>
                            <div x-show="imagePreview" class="w-full h-full p-3" x-cloak><img :src="imagePreview" class="w-full h-full object-contain"></div>
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer" @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL(file) }">
                        </div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-8 space-y-6">
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nome do Produto</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Digital o nome do produto" required class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest ml-1" :class="eanValid ? 'text-gray-400' : 'text-red-500'">Cód. EAN 13 (SCANNER)</label>
                                <input type="text" name="ean_13" value="{{ old('ean_13') }}" maxlength="13" @input="validateEAN($event.target.value)" @keydown="handleScanner($event)" placeholder="0000000000000" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all" :class="eanValid ? 'border-gray-200 dark:border-gray-800' : 'border-red-500 shadow-lg shadow-red-500/10'">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 relative">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Categoria</label>
                                <input type="hidden" name="category_id" x-model="category" required>
                                <button type="button" @click="catOpen = !catOpen; if(catOpen) $nextTick(() => $refs.searchField.focus())" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 flex items-center justify-between outline-none uppercase transition-all">
                                    <span x-text="catLabel"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="catOpen ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="catOpen" @click.away="catOpen = false" x-cloak x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl overflow-hidden max-h-60 flex flex-col">
                                    <div class="p-3 border-b border-gray-100 dark:border-gray-900 bg-white/50 dark:bg-gray-900/50">
                                        <input type="text" x-model="searchCat" x-ref="searchField" placeholder="BUSCAR CATEGORIA..." class="w-full px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg text-[10px] font-black uppercase outline-none focus:border-emerald-500 transition-all">
                                    </div>
                                    <div class="overflow-y-auto p-2 space-y-1">
                                        @foreach($categories as $parent)
                                            <button type="button" x-show="'{{ strtoupper($parent->name) }}'.includes(searchCat.toUpperCase())" @click="category = '{{ $parent->id }}'; catLabel = '{{ $parent->name }}'; catOpen = false; searchCat = ''" class="w-full px-4 py-3 text-left text-[11px] font-black uppercase tracking-widest rounded-xl transition-all hover:bg-white dark:hover:bg-gray-900" :class="category === '{{ $parent->id }}' ? 'bg-white dark:bg-gray-900 text-emerald-500' : 'text-gray-800 dark:text-gray-200'">{{ $parent->name }}</button>
                                            @foreach($parent->children as $child)
                                                <button type="button" x-show="'{{ strtoupper($parent->name) }} {{ strtoupper($child->name) }}'.includes(searchCat.toUpperCase())" @click="category = '{{ $child->id }}'; catLabel = '{{ $parent->name }} > {{ $child->name }}'; catOpen = false; searchCat = ''" class="w-full px-8 py-2.5 text-left text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all hover:bg-white dark:hover:bg-gray-900 text-gray-400 dark:text-gray-500" :class="category === '{{ $child->id }}' ? 'text-emerald-500' : ''">— {{ $child->name }}</button>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Código NCM</label>
                                <input type="text" name="ncm" value="{{ old('ncm') }}" maxlength="8" placeholder="EX: 84713012" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all uppercase">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descrição Técnica</label>
                            <textarea name="description" rows="5" placeholder="Escreva sobre o produto..." class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'financeiro'" x-transition.opacity class="grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-3">
                    <div class="h-full p-6 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl flex flex-col justify-center text-center shadow-xs">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Margem Líquida</span>
                        <span class="text-4xl font-black tracking-tighter" :class="margin > 0 ? 'text-emerald-500' : 'text-red-500'" x-text="margin + '%'"></span>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-2 tracking-tight">Cálculo baseado em custos totas</p>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-9">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Custo Compra</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-gray-400 font-black text-xs">R$</span>
                                <input type="text" x-model="displayCost" @input="formatMoney($event.target.value, 'cost')" required class="w-full pl-10 px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-lg font-black text-gray-800 dark:text-gray-50 outline-none">
                                <input type="hidden" name="cost_price" :value="cost">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Custo Operacional</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-gray-400 font-black text-xs">R$</span>
                                <input type="text" x-model="displayOpCost" @input="formatMoney($event.target.value, 'opCost')" class="w-full pl-10 px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-lg font-black text-gray-800 dark:text-gray-50 outline-none">
                                <input type="hidden" name="operational_cost" :value="opCost">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-1">Preço Venda</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-emerald-500 font-black text-xs">R$</span>
                                <input type="text" x-model="displayPrice" @input="formatMoney($event.target.value, 'price')" required class="w-full pl-10 px-4 py-3 bg-white dark:bg-gray-900 border border-emerald-500/30 rounded-xl text-xl font-black text-emerald-500 outline-none">
                                <input type="hidden" name="price" :value="price">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">IOF (%)</label>
                            <input type="text" x-model="displayIof" @input="formatMoney($event.target.value, 'iof')" placeholder="0,0%" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl font-black text-red-500 outline-none">
                            <input type="hidden" name="iof_rate" :value="iof">
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'estoque'" x-transition.opacity class="grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-9">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Saldo</label>
                                <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" placeholder="15" required class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl font-black text-gray-800 dark:text-gray-50 outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mínimo</label>
                                <input type="number" name="min_stock" value="{{ old('min_stock') }}" placeholder="5" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl font-black text-amber-500 outline-none">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Código (SKU)</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" required @keydown="handleScanner($event)" placeholder="SKU-XX-00" class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl font-bold text-gray-800 outline-none uppercase">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection