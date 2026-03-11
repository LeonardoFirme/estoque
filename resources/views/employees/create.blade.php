<!-- resources/views/employees/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8">
        <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-800 pb-8">
            <a href="{{ route('funcionarios') }}"
                class="p-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 transition-all shadow-xs">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-400"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Cadastrar
                    Colaborador</h1>
                <p class="text-gray-500 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">Adicionar
                    novo membro à equipe operacional</p>
            </div>
        </div>

        <form action="{{ route('funcionarios.store') }}" method="POST" class="space-y-6 bg-gray-50 dark:bg-gray-900 p-8 border border-gray-200/50 dark:border-gray-800/50 rounded" x-data="{
                    role: '',
                    roleLabel: 'SELECIONE UMA FUNÇÃO...',
                    open: false
                }">
            @csrf
            <div
                class="pace-y-6">

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nome Completo</label>
                    <input type="text" name="name" required placeholder="EX: LEONARDO FIRME"
                        class="w-full px-4 py-3  bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">E-mail
                            Corporativo</label>
                        <input type="email" name="email" required placeholder="email@empresa.com"
                            class="w-full px-4 py-3  bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">CPF</label>
                        <input type="text" name="cpf" id="cpf" required placeholder="000.000.000-00" maxlength="14"
                            oninput="maskCPF(this)"
                            class="w-full px-4 py-3  bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2 relative">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cargo /
                        Função</label>
                    <input type="hidden" name="role" x-model="role" required>

                    <button type="button" @click="open = !open"
                        class="w-full px-4 py-3  bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold text-gray-800 dark:text-gray-50 flex items-center justify-between transition-all outline-none uppercase tracking-tight">
                        <span x-text="roleLabel"></span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
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
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-10 py-4 bg-gray-800 dark:bg-gray-50 text-white dark:text-gray-950 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-gray-950 dark:hover:bg-gray-200 transition-all shadow-xl shadow-gray-200 dark:shadow-none cursor-pointer">
                    Efetivar Cadastro
                </button>
            </div>
        </form>
    </div>

    <script>
        function maskCPF(i) {
            let v = i.value.replace(/\D/g, "");
            if (v.length > 11) v = v.substring(0, 11);
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            i.value = v;
        }
    </script>
@endsection