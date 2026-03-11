<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Estoque</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.onpageshow = function (event) {
            if (event.persisted) { window.location.reload(); }
        };
    </script>
</head>

<body class="bg-white dark:bg-gray-950 antialiased text-gray-800 dark:text-gray-50 transition-colors duration-200">
    <div class="flex min-h-screen">
        <aside
            class="w-64 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 flex flex-col fixed h-full z-20">
            <div class="p-8 flex items-center gap-3">
                <div
                    class="w-9 h-9 bg-gray-800 dark:bg-gray-50 rounded-xl flex items-center justify-center shadow-lg shadow-gray-200 dark:shadow-none">
                    <i data-lucide="box" class="w-5 h-5 text-white dark:text-gray-950"></i>
                </div>
                <span class="text-xl font-black tracking-tighter uppercase dark:text-gray-50">ERP ESTOQUE</span>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 mt-4 overflow-y-auto">
                <div class="pb-2 px-4">
                    <span
                        class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">Logística</span>
                </div>

                @php
                    $inventoryItems = [
                        ['route' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                        ['route' => 'produtos', 'icon' => 'package', 'label' => 'Produtos'],
                        ['route' => 'entradas', 'icon' => 'trending-up', 'label' => 'Entradas'],
                        ['route' => 'saidas', 'icon' => 'trending-down', 'label' => 'Saídas'],
                    ];
                @endphp

                @foreach ($inventoryItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route'] . '*');
                        if ($item['route'] === 'saidas') {
                            $isActive = request()->routeIs('saidas') || request()->routeIs('saidas.index') || request()->routeIs('saidas.create');
                        }
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all {{ $isActive ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-gray-800 dark:hover:text-gray-50' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="pt-6 pb-2 px-4">
                    <span
                        class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">Administrativo</span>
                </div>

                <a href="{{ route('categorias') }}"
                    class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('categorias*') ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-gray-800 dark:hover:text-gray-50' }}">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    Categorias
                </a>

                <a href="{{ route('funcionarios') }}"
                    class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('funcionarios*') ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-gray-800 dark:hover:text-gray-50' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    Funcionários
                </a>

                <a href="{{ route('saidas.commissoes') }}"
                    class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('saidas.commissoes') ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-gray-800 dark:hover:text-gray-50' }}">
                    <i data-lucide="percent" class="w-4 h-4"></i>
                    Comissões
                </a>

                <a href="{{ route('saidas.resumo') }}"
                    class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('saidas.resumo') ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-gray-800 dark:hover:text-gray-50' }}">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    Resumo Diário
                </a>

                <a href="{{ route('saidas.descontos') }}"
                    class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('saidas.descontos') ? 'bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 shadow-md' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-900/50 hover:text-gray-800 dark:hover:text-gray-50' }}">
                    <i data-lucide="ticket" class="w-4 h-4"></i>
                    Descontos
                </a>

                <div class="pt-6 pb-2 px-4">
                    <span
                        class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">Operacional</span>
                </div>

                <a href="{{ route('saidas.create') }}" target="_blank"
                    class="flex items-center gap-3 px-4 py-4 text-xs font-black uppercase tracking-widest rounded-xl transition-all bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg shadow-emerald-100 dark:shadow-none">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    Frente de Caixa
                </a>
            </nav>

            <div class="p-6 border-t border-gray-100 dark:border-gray-900">
                @auth
                    <div class="flex items-center gap-3 px-2 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 flex items-center justify-center font-black text-gray-800 dark:text-gray-50 uppercase">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <p class="text-xs font-black text-gray-800 dark:text-gray-50 truncate uppercase">
                                {{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-[10px] font-black uppercase text-red-500 bg-red-50/50 dark:bg-red-950/10 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl border border-red-100 dark:border-red-900/30 transition-all cursor-pointer outline-none">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Encerrar Sessão
                        </button>
                    </form>
                @endauth
            </div>
        </aside>

        <div class="flex-1 ml-64 flex flex-col">
            <header
                class="h-20 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl sticky top-0 z-30">
                <div class="h-full px-8 flex items-center justify-between">
                    <nav class="flex items-center text-[10px] font-black uppercase tracking-widest">
                        <ol class="flex items-center gap-2">
                            <li><a href="{{ route('dashboard') }}"
                                    class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors">SISTEMA</a>
                            </li>
                            @if(!request()->routeIs('dashboard'))
                                <li class="flex items-center gap-2">
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300 dark:text-gray-700"></i>
                                    <span
                                        class="text-gray-800 dark:text-gray-50 uppercase">{{ str_replace('.', ' ', request()->route()->getName()) }}</span>
                                </li>
                            @endif
                        </ol>
                    </nav>

                    <div class="flex items-center gap-6">
                        <div class="relative group hidden lg:block" x-data="{ query: '', results: [], open: false }">
                            <i data-lucide="search"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" x-model="query"
                                @input.debounce.300ms="if(query.length > 2) fetch('/api/search?q=' + query).then(res => res.json()).then(data => { results = data; open = true })"
                                @click.away="open = false" placeholder="BUSCAR SKU OU PRODUTO..."
                                class="w-72 pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black tracking-widest focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all outline-none text-gray-800 dark:text-gray-50">
                            <div x-show="open && results.length > 0"
                                class="absolute top-full right-0 w-96 mt-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl z-50 overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                                <template x-for="item in results" :key="item.id">
                                    <a :href="'/produtos/' + item.id"
                                        class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-900 border-b last:border-0 border-gray-100 dark:border-gray-900 transition-all group/item">
                                        <div
                                            class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 overflow-hidden shrink-0 flex items-center justify-center p-1.5 transition-transform group-hover/item:scale-105">
                                            <template x-if="item.image_path"><img :src="'/storage/' + item.image_path"
                                                    class="max-w-full max-h-full object-contain"></template>
                                            <template x-if="!item.image_path"><i data-lucide="package"
                                                    class="w-5 h-5 text-gray-300"></i></template>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span
                                                class="text-[11px] font-black text-gray-800 dark:text-gray-50 truncate uppercase"
                                                x-text="item.name"></span>
                                            <span class="text-[9px] text-gray-400 font-mono font-bold uppercase"
                                                x-text="'REF: ' + item.sku"></span>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center gap-1" x-data="{
                                showDate: false,
                                reportDate: '{{ now()->format('Y-m-d') }}',
                                printReport() {
                                    const url = `{{ route('saidas.relatorio') }}?date=${this.reportDate}`;
                                    window.open(url, '_blank');
                                    this.showDate = false;
                                }
                            }">
                            <div class="relative">
                                <button @click="showDate = !showDate"
                                    class="p-2.5 text-gray-400 hover:text-emerald-500 transition-colors cursor-pointer rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 border-none bg-transparent outline-none">
                                    <i data-lucide="calendar-range" class="w-5 h-5"></i>
                                </button>
                                <div x-show="showDate" @click.away="showDate = false" x-cloak
                                    class="absolute top-full right-0 mt-3 w-64 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl z-50 p-5 space-y-4">
                                    <div>
                                        <label
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Data
                                            do Relatório</label>
                                        <input type="date" x-model="reportDate"
                                            class="w-full mt-2 px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl text-xs font-black text-gray-800 dark:text-gray-50 outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                                    </div>
                                    <button @click="printReport()"
                                        class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg border-none cursor-pointer">Gerar
                                        PDF agora</button>
                                </div>
                            </div>
                            <button
                                class="p-2.5 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors cursor-pointer rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 border-none bg-transparent outline-none">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-10 flex-1">
                @yield('content')
            </main>

            <footer
                class="px-10 py-6 border-t border-gray-100 dark:border-gray-900 bg-white/50 dark:bg-gray-950/50 backdrop-blur-md">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span
                                class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">Servidor
                                Online</span>
                        </div>
                        <span class="text-gray-200 dark:text-gray-800">|</span>
                        <div class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">
                            Versão <span class="text-gray-800 dark:text-gray-50 uppercase">2.4.0-Stable</span></div>
                    </div>
                    <div class="flex items-center gap-6">
                        <p class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">
                            &copy; {{ date('Y') }} ERP ESTOQUE PRO.</p>
                        <div
                            class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-900 rounded-full border border-gray-100 dark:border-gray-800">
                            <i data-lucide="shield-check" class="w-3 h-3 text-emerald-500"></i>
                            <span
                                class="text-[8px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Ambiente
                                Seguro</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>