<!-- resources/views/employees/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8" x-data="{
            showPass: false,
            showConfirmPass: false,
            role: '{{ old('role', $employee->role) }}',
            roleLabel: '{{ old('role', $employee->role) }}' || 'SELECIONE UMA FUNÇÃO...',
            open: false
        }">
        <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-800 pb-8">
            <a href="{{ route('funcionarios') }}"
                class="p-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 transition-all shadow-xs outline-none border-none">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-400"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Editar
                    Colaborador</h1>
                <p class="text-gray-400 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">
                    Credenciais de acesso para {{ $employee->name }}</p>
            </div>
        </div>

        <form action="{{ route('funcionarios.update', $employee->id) }}" method="POST" class="w-full space-y-6">
            @csrf
            @method('PUT')

            <div
                class="p-8 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl space-y-6 shadow-xs">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">E-mail
                            Corporativo</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">CPF</label>
                        <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $employee->cpf) }}" required
                            maxlength="14" oninput="maskCPF(this)"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2 relative">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cargo / Função
                        Atual</label>
                    <input type="hidden" name="role" x-model="role" required>
                    <button type="button" @click="open = !open"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 flex items-center justify-between transition-all outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 uppercase tracking-tight">
                        <span x-text="roleLabel"></span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition
                        class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                        <div class="p-2 space-y-1">
                            <template x-for="option in ['GERENTE', 'OPERADOR DE CAIXA', 'ESTOQUISTA', 'VENDEDOR']"
                                :key="option">
                                <button type="button" @click="role = option; roleLabel = option; open = false"
                                    class="w-full px-4 py-3 text-left text-[11px] font-black uppercase tracking-widest rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-gray-800 dark:hover:text-gray-50"
                                    :class="role === option ? 'bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-50' : 'text-gray-400 dark:text-gray-500'">
                                    <span x-text="option"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-4">Segurança e Acesso
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nova Senha
                                (Opcional)</label>
                            <div class="relative">
                                <input :type="showPass ? 'text' : 'password'" name="password"
                                    class="w-full pl-4 pr-14 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all">
                                <button type="button" @click="showPass = !showPass"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors border-none bg-transparent outline-none cursor-pointer flex items-center justify-center">
                                    <i x-show="!showPass" data-lucide="eye" class="w-5 h-5"></i>
                                    <i x-show="showPass" x-cloak data-lucide="eye-off" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Confirmar
                                Senha</label>
                            <div class="relative">
                                <input :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation"
                                    class="w-full pl-4 pr-14 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all">
                                <button type="button" @click="showConfirmPass = !showConfirmPass"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors border-none bg-transparent outline-none cursor-pointer flex items-center justify-center">
                                    <i x-show="!showConfirmPass" data-lucide="eye" class="w-5 h-5"></i>
                                    <i x-show="showConfirmPass" x-cloak data-lucide="eye-off" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center gap-4">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Sincronizado em:
                    {{ $employee->updated_at->format('d/m/Y H:i') }}</p>
                <button type="submit"
                    class="px-10 py-4 bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-950 dark:hover:bg-gray-200 transition-all shadow-xl border-none outline-none cursor-pointer">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    <script>
        function maskCPF(i) {
            let v = i.value.replace(/\D/g, '');
            if (v.length > 11) v = v.substring(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            i.value = v;
        }
    </script>
@endsection