<!-- resources/views/outputs/create.blade.php -->
<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDV - FRENTE DE CAIXA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-white dark:bg-gray-950 antialiased overflow-hidden transition-colors duration-200" x-data="{
        employeeId: '',
        employeeName: 'SELECIONE O VENDEDOR',
        employeeRole: '',
        type: 'sale',
        typeLabel: 'VENDA COMERCIAL',
        withdrawalValue: 0,
        search: '',
        empSearch: '',
        open: false,
        empOpen: false,
        typeOpen: false,
        checkoutModal: false,
        printModal: false,
        shouldPrint: true,
        managerPassword: '',
        paymentMethod: 'money',
        installments: 1,
        globalDiscount: 0,
        cart: [],
        products: @js($products),
        employees: @js($employees),

        get filteredProducts() {
            if (this.search === '') return this.products.slice(0, 10);
            return this.products.filter(p =>
                p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                p.sku.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        get filteredEmployees() {
            if (this.empSearch === '') return this.employees;
            return this.employees.filter(e =>
                e.name.toLowerCase().includes(this.empSearch.toLowerCase()) ||
                e.role.toLowerCase().includes(this.empSearch.toLowerCase())
            );
        },

        addToCart(product) {
            this.cart.push({
                id: product.id,
                name: product.name,
                sku: product.sku,
                price: parseFloat(product.price),
                discount: 0,
                qty: 1,
                image: product.image,
                timestamp: Date.now()
            });
            this.search = '';
            this.open = false;
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        get subtotal() {
            if (this.type === 'withdrawal') return parseFloat(this.withdrawalValue || 0);
            let totalItems = this.cart.reduce((sum, item) => sum + (item.price - item.discount), 0);
            return Math.max(0, totalItems - this.globalDiscount);
        },

        selectEmployee(emp) {
            this.employeeId = emp.id;
            this.employeeName = emp.name;
            this.employeeRole = emp.role.toLowerCase();
            this.empOpen = false;
            this.empSearch = '';
            if (this.employeeRole !== 'gerente' && this.type === 'withdrawal') {
                this.selectType('sale', 'VENDA COMERCIAL');
            }
        },

        selectType(val, label) {
            if (val === 'withdrawal' && this.employeeRole !== 'gerente') {
                alert('APENAS GERENTES PODEM REALIZAR RETIRADAS.');
                return;
            }
            this.type = val;
            this.typeLabel = label;
            this.typeOpen = false;
            if(val === 'withdrawal') this.cart = [];
        },

        submitForm() {
            if(!this.employeeId) return alert('Selecione o vendedor');
            if(this.type === 'withdrawal' && (!this.withdrawalValue || this.withdrawalValue <= 0)) return alert('Informe um valor válido');

            const needsAuth = this.type !== 'sale' || this.globalDiscount > 0 || this.cart.some(i => i.discount > 0);
            if(needsAuth && this.managerPassword !== 'estoque123') {
                alert('SENHA GERENCIAL INCORRETA.');
                return;
            }
            document.getElementById('output-form').submit();
        }
    }" x-init="
        lucide.createIcons();
        $watch('cart', () => { $nextTick(() => lucide.createIcons()); });
        @if(session('print_id'))
            window.open('{{ route('saidas.recibo', session('print_id')) }}', '_blank');
        @endif
    " @keydown.window.enter="if(printModal) submitForm()">

    <div class="h-screen flex flex-col">
        <header
            class="h-20 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800 px-10 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg text-white">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">TERMINAL
                        PDV</h1>
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-emerald-500">Operação em tempo real
                    </p>
                </div>
            </div>
            <form action="{{ route('pdv.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all cursor-pointer border-none outline-none">Sair
                    do PDV</button>
            </form>
        </header>

        <main class="flex-1 overflow-hidden grid grid-cols-12">
            <div
                class="col-span-12 lg:col-span-8 p-10 overflow-y-auto bg-gray-50/30 dark:bg-gray-950 space-y-10 border-r border-gray-200 dark:border-gray-800">
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-3 relative">
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Colaborador</label>
                        <button @click="empOpen = !empOpen"
                            class="w-full px-6 py-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-left font-black text-gray-800 dark:text-gray-50 flex justify-between items-center uppercase outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <span x-text="employeeName"></span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                        </button>
                        <div x-show="empOpen" x-cloak @click.away="empOpen = false"
                            class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl z-50 overflow-hidden ring-1 ring-black/5">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-800">
                                <input type="text" x-model="empSearch" placeholder="BUSCAR..."
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black uppercase outline-none focus:border-emerald-500 transition-all">
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <template x-for="emp in filteredEmployees" :key="emp.id">
                                    <div @click="selectEmployee(emp)"
                                        class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer border-b last:border-0 border-gray-50 dark:border-gray-800 transition-all group">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-gray-800 dark:text-gray-50 uppercase"
                                                x-text="emp.name"></span>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase mt-0.5"
                                                x-text="emp.role"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 relative">
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Operação</label>
                        <button @click="typeOpen = !typeOpen"
                            class="w-full px-6 py-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-left font-black text-gray-800 dark:text-gray-50 flex justify-between items-center uppercase outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <span x-text="typeLabel"></span>
                            <i data-lucide="layers" class="w-5 h-5 text-gray-400"></i>
                        </button>
                        <div x-show="typeOpen" x-cloak @click.away="typeOpen = false"
                            class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl z-50 overflow-hidden ring-1 ring-black/5 text-gray-800 dark:text-gray-50">
                            <div @click="selectType('sale', 'VENDA COMERCIAL')"
                                class="px-6 py-4 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 cursor-pointer border-b border-gray-50 dark:border-gray-800 text-xs font-black uppercase flex items-center gap-3">
                                <i data-lucide="shopping-cart" class="w-4 h-4 text-emerald-500"></i> VENDA COMERCIAL
                            </div>
                            <div @click="selectType('return', 'DEVOLUÇÃO DE ITEM')"
                                class="px-6 py-4 hover:bg-blue-50 dark:hover:bg-blue-950/30 cursor-pointer border-b border-gray-50 dark:border-gray-800 text-xs font-black uppercase flex items-center gap-3">
                                <i data-lucide="refresh-cw" class="w-4 h-4 text-blue-500"></i> DEVOLUÇÃO DE ITEM</div>
                            <div @click="selectType('loss', 'AVARIA / PERDA')"
                                class="px-6 py-4 hover:bg-red-50 dark:hover:bg-red-950/30 cursor-pointer border-b border-gray-50 dark:border-gray-800 text-xs font-black uppercase flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500"></i> AVARIA / PERDA</div>
                            <div x-show="employeeRole === 'gerente'"
                                @click="selectType('withdrawal', 'RETIRADA DE CAIXA')"
                                class="px-6 py-4 hover:bg-amber-50 dark:hover:bg-amber-950/30 cursor-pointer border-b border-gray-50 dark:border-gray-800 text-xs font-black uppercase flex items-center gap-3">
                                <i data-lucide="banknote" class="w-4 h-4 text-amber-500"></i> RETIRADA DE CAIXA</div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 relative" x-show="type !== 'withdrawal'" @click.away="open = false">
                    <div class="relative max-w-2xl mx-auto">
                        <i data-lucide="search"
                            class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                        <input type="text" x-model="search" @focus="open = true" placeholder="BUSCAR PRODUTO..."
                            class="w-full pl-16 pr-6 py-6 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-800 rounded-3xl text-xl font-black text-gray-800 dark:text-gray-50 uppercase outline-none focus:border-emerald-500 transition-all shadow-xl shadow-gray-200/50 dark:shadow-none">
                        <div x-show="open" x-cloak
                            class="absolute top-full left-0 w-full mt-4 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl shadow-2xl z-60 max-h-80 overflow-y-auto ring-1 ring-black/5">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <div @mousedown="addToCart(product)"
                                    class="flex items-center gap-4 px-8 py-5 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 cursor-pointer border-b last:border-0 border-gray-50 dark:border-gray-800 transition-all group">
                                    <div
                                        class="w-14 h-14 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                        <template x-if="product.image"><img :src="product.image"
                                                class="w-full h-full object-contain p-1"></template>
                                        <template x-if="!product.image"><i data-lucide="package"
                                                class="w-6 h-6 text-gray-300"></i></template>
                                    </div>
                                    <div class="flex flex-col flex-1">
                                        <span
                                            class="text-sm font-black text-gray-800 dark:text-gray-50 uppercase tracking-tight group-hover:text-emerald-500 transition-colors"
                                            x-text="product.name"></span>
                                        <span class="text-[10px] font-mono font-bold text-gray-400"
                                            x-text="'SKU: ' + product.sku + ' | EST: ' + product.stock"></span>
                                    </div>
                                    <span class="text-sm font-black text-gray-800 dark:text-gray-50"
                                        x-text="'R$ ' + parseFloat(product.price).toLocaleString('pt-BR', {minimumFractionDigits:2})"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 max-w-md mx-auto" x-show="type === 'withdrawal'" x-cloak>
                    <label
                        class="text-[10px] font-black text-amber-500 uppercase tracking-widest text-center block">Valor
                        para Sangria de Caixa</label>
                    <input type="number" step="0.01" x-model="withdrawalValue" placeholder="0,00"
                        class="w-full px-8 py-6 bg-white dark:bg-gray-900 border-2 border-amber-200 dark:border-amber-900/50 rounded-3xl text-4xl font-black text-gray-800 dark:text-gray-50 text-center outline-none focus:border-amber-500 transition-all shadow-xl shadow-amber-500/10">
                </div>

                <div class="mt-12" x-show="type !== 'withdrawal'">
                    <div
                        class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-4xl overflow-hidden shadow-xs ring-1 ring-black/5">
                        <table class="w-full text-left">
                            <thead>
                                <tr
                                    class="bg-gray-50 dark:bg-gray-800/50 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-8 py-5">Item</th>
                                    <th class="px-8 py-5">Produto</th>
                                    <th class="px-8 py-5 text-right">Unitário</th>
                                    <th class="px-8 py-5 text-right">Desc. Item</th>
                                    <th class="px-8 py-5 text-right">Subtotal</th>
                                    <th class="px-8 py-5"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                <template x-for="(item, index) in cart" :key="item.timestamp">
                                    <tr
                                        class="text-xs font-black text-gray-800 dark:text-gray-50 uppercase group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-all">
                                        <td class="px-8 py-5 text-gray-400" x-text="index + 1"></td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                                    <template x-if="item.image"><img :src="item.image"
                                                            class="w-full h-full object-contain"></template>
                                                    <template x-if="!item.image"><i data-lucide="package"
                                                            class="w-4 h-4 text-gray-300"></i></template>
                                                </div>
                                                <span x-text="item.name"></span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right text-gray-400"
                                            x-text="'R$ ' + item.price.toFixed(2)"></td>
                                        <td class="px-8 py-5 text-right"><input type="number" step="0.01"
                                                x-model="item.discount"
                                                class="w-20 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg px-2 py-1 text-right text-red-500 font-black outline-none focus:border-red-500 transition-all">
                                        </td>
                                        <td class="px-8 py-5 text-right text-emerald-500"
                                            x-text="'R$ ' + (item.price - item.discount).toLocaleString('pt-BR', {minimumFractionDigits: 2})">
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <button @click="removeFromCart(index)"
                                                class="p-2 text-gray-300 hover:text-red-500 cursor-pointer border-none bg-transparent transition-colors"><i
                                                    data-lucide="trash-2" class="w-4 h-4"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div
                class="col-span-12 lg:col-span-4 bg-white dark:bg-gray-900 p-10 flex flex-col justify-between border-l border-gray-200 dark:border-gray-800">
                <div class="space-y-8 text-center">
                    <div
                        class="p-10 bg-gray-50 dark:bg-gray-950 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-3xl">
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.4em] mb-4 block"
                            x-text="type === 'withdrawal' ? 'VALOR DA RETIRADA' : 'TOTAL DA OPERAÇÃO'"></span>
                        <div class="flex justify-between items-center text-[10px] font-black text-red-500 uppercase tracking-widest mb-6 border-b border-gray-100 dark:border-gray-800 pb-4"
                            x-show="type !== 'withdrawal'">
                            <span>DESC. GLOBAL</span>
                            <input type="number" step="0.01" x-model="globalDiscount"
                                class="w-24 bg-transparent text-right font-black outline-none border-b border-red-200 focus:border-red-500 transition-all">
                        </div>
                        <div
                            class="text-7xl font-black tracking-tighter text-gray-800 dark:text-gray-50 flex items-start justify-center gap-2">
                            <span class="text-2xl mt-2 tracking-normal">R$</span>
                            <span x-text="subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>
                    <button @click="checkoutModal = true"
                        class="w-full py-8 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xl uppercase tracking-widest rounded-3xl shadow-2xl active:scale-95 cursor-pointer border-none transition-all outline-none">FINALIZAR
                        OPERAÇÃO</button>
                </div>
            </div>
        </main>
    </div>

    <form id="output-form" action="{{ route('saidas.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="employee_id" x-model="employeeId">
        <input type="hidden" name="type" x-model="type">
        <input type="hidden" name="manager_password" x-model="managerPassword">
        <input type="hidden" name="payment_method" x-model="paymentMethod">
        <input type="hidden" name="global_discount" x-model="globalDiscount">
        <input type="hidden" name="withdrawal_value" x-model="withdrawalValue">
        <input type="hidden" name="should_print" x-model="shouldPrint">
        <template x-for="(item, index) in cart" :key="index">
            <div>
                <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.id">
                <input type="hidden" :name="'items['+index+'][quantity]'" value="1">
                <input type="hidden" :name="'items['+index+'][discount]'" :value="item.discount">
            </div>
        </template>
    </form>

    <div x-cloak x-show="checkoutModal">@include('outputs.partials.checkout-modal')</div>
    <script>lucide.createIcons();</script>
</body>

</html>