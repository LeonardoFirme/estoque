<!-- resources/views/auth/gate.blade.php -->
<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA - SELEÇÃO DE ACESSO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body
    class="bg-white dark:bg-gray-950 min-h-screen flex flex-col justify-center items-center antialiased transition-colors duration-200">
    <div class="w-full max-w-4xl p-8 space-y-12">
        <div class="text-center space-y-4">
            <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Portal de Acesso
            </h1>
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.4em]">Selecione o
                ambiente para operar</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <a href="{{ route('login') }}"
                class="group p-10 bg-gray-50 dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-800 rounded-3xl transition-all hover:border-gray-800 dark:hover:border-gray-50 flex flex-col items-center text-center space-y-6 shadow-xs">
                <div
                    class="w-20 h-20 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                    <i data-lucide="layout-dashboard" class="w-10 h-10 text-gray-800 dark:text-gray-50"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-gray-800 dark:text-gray-50 uppercase tracking-tight">Gestão de
                        Estoque</h2>
                    <p class="text-xs font-medium text-gray-400 mt-2 uppercase tracking-widest">Painel Administrativo
                        ERP</p>
                </div>
            </a>

            <a href="{{ route('pdv.login') }}"
                class="group p-10 bg-emerald-500 rounded-3xl transition-all hover:bg-emerald-600 flex flex-col items-center text-center space-y-6 shadow-2xl shadow-emerald-500/20">
                <div
                    class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform text-white">
                    <i data-lucide="shopping-cart" class="w-10 h-10"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white uppercase tracking-tight">Frente de Caixa</h2>
                    <p class="text-xs font-medium text-white/80 mt-2 uppercase tracking-widest">Terminal de Vendas
                        Diretas</p>
                </div>
            </a>
        </div>

        <div class="pt-8 border-t border-gray-100 dark:border-gray-900 text-center">
            <p class="text-[9px] font-black text-gray-300 dark:text-gray-700 uppercase tracking-widest">&copy;
                {{ date('Y') }} ERP ESTOQUE PRO - v2.4.0</p>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>