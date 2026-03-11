<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP - ACESSO ADMINISTRATIVO</title>
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
    <div
        class="w-full max-w-md p-8 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-800 dark:text-gray-50 uppercase tracking-tighter">Acessar ERP</h1>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Ambiente Administrativo</p>
        </div>

        <form method="POST" action="/login/estoque" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">E-mail</label>
                <input type="email" name="email" required placeholder="nome@exemplo.com" value="{{ old('email') }}"
                    class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-gray-800 dark:text-gray-50 focus:border-gray-800 dark:focus:border-gray-50 outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Senha</label>
                <div class="relative group">
                    <input type="password" id="login_password" name="password" required placeholder="••••••••"
                        class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-gray-800 dark:text-gray-50 focus:border-gray-800 dark:focus:border-gray-50 outline-none transition-all">
                    <button type="button" onclick="togglePass('login_password', 'icon_login')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors cursor-pointer">
                        <i id="icon_login" data-lucide="eye" class="w-4 h-4"></i>
                    </button>
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
                class="w-full py-4 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-xl transition-all cursor-pointer">
                Entrar no Sistema
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-900 text-center">
            <a href="{{ route('gate') }}"
                class="text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-800 dark:hover:text-gray-50 transition-colors">Voltar
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