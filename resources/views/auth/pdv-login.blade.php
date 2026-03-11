<!-- resources/views/auth/pdv-login.blade.php -->
<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDV - LOGIN DE TERMINAL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .no-uppercase {
            text-transform: none !important;
        }
    </style>
</head>

<body
    class="bg-white dark:bg-gray-950 min-h-screen flex flex-col justify-center items-center antialiased transition-colors duration-200">
    <div class="w-full max-w-md p-8 space-y-10">
        <div class="text-center space-y-4">
            <div
                class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-2xl mx-auto shadow-emerald-500/20 text-white">
                <i data-lucide="shopping-cart" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Terminal PDV
                </h1>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.3em]">
                    Autenticação de Operador</p>
            </div>
        </div>

        <form action="/login/pdv" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="from_pdv" value="true">

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">E-mail</label>
                    <input type="email" name="email" required placeholder="operador@exemplo.com"
                        value="{{ old('email') }}"
                        class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-950 border-2 border-gray-100 dark:border-gray-800 rounded-2xl text-smtext-gray-800 dark:text-gray-50 focus:border-emerald-500 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Senha</label>
                    <div class="relative group">
                        <input type="password" id="pdv_password" name="password" required placeholder="••••••••"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-950 border-2 border-gray-100 dark:border-gray-800 rounded-2xl text-sm text-gray-800 dark:text-gray-50 focus:border-emerald-500 outline-none transition-all">
                        <button type="button" onclick="togglePass('pdv_password', 'icon_pdv')"
                            class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-500 transition-colors cursor-pointer">
                            <i id="icon_pdv" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 rounded-xl">
                    <p class="text-[10px] font-black text-red-500 uppercase tracking-widest text-center">
                        {{ $errors->first() }}
                    </p>
                </div>
            @endif

            <button type="submit"
                class="w-full py-5 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl transition-all active:scale-95 cursor-pointer">
                Acessar Frente de Caixa
            </button>
        </form>

        <div class="pt-10 border-t border-gray-100 dark:border-gray-900 flex flex-col items-center gap-4">
            <p class="text-[9px] font-black text-gray-300 dark:text-gray-700 uppercase tracking-widest text-center">
                &copy; ERP PRO - AMBIENTE SEGURO</p>
            <a href="{{ route('gate') }}"
                class="text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-emerald-500 transition-colors">Voltar
                para Seleção</a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePass(id, iconId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(iconId);
            const isPass = input.type === 'password';

            input.type = isPass ? 'text' : 'password';
            icon.setAttribute('data-lucide', isPass ? 'eye-off' : 'eye');

            lucide.createIcons();
        }
    </script>
</body>

</html>