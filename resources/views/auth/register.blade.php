<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="min-h-[80vh] flex flex-col items-center justify-center">
        <div
            class="w-full max-w-md p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-50 tracking-tight">Criar Conta</h1>
                <p class="text-gray-500 dark:text-gray-100 mt-1">Cadastre sua empresa no ERP.</p>
            </div>

            <form method="POST" action="/register" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-50">Nome Completo</label>
                    <input type="text" name="name" required placeholder="Ex: Leonardo Firme"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg text-gray-800 dark:text-gray-50 focus:outline-hidden focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all placeholder:text-gray-400">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-50">E-mail Corporativo</label>
                    <input type="email" name="email" required placeholder="leonardo@empresa.com"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg text-gray-800 dark:text-gray-50 focus:outline-hidden focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all placeholder:text-gray-400">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-50">Senha</label>
                    <div class="relative group">
                        <input type="password" id="reg_password" name="password" required
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-lg text-gray-800 dark:text-gray-50 focus:outline-hidden focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all">
                        <button type="button" onclick="togglePass('reg_password', 'icon_reg')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 cursor-pointer">
                            <i id="icon_reg" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-bold rounded-lg transition-all cursor-pointer">
                    Finalizar Cadastro
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-900 text-center">
                <p class="text-sm text-gray-400 dark:text-gray-200">
                    Já possui conta?
                    <a href="/login" class="text-gray-800 dark:text-gray-50 font-bold hover:underline">Fazer Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePass(id, iconId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(iconId);
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.setAttribute('data-lucide', isPass ? 'eye-off' : 'eye');
            lucide.createIcons();
        }
    </script>
@endsection