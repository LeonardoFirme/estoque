<!-- resources/views/employees/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8" x-data="{
                search: '',
                employees: @js($employees->items()),
                get filteredEmployees() {
                    if (!this.search) return this.employees;
                    return this.employees.filter(emp =>
                        emp.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        emp.email.toLowerCase().includes(this.search.toLowerCase()) ||
                        emp.cpf.includes(this.search) ||
                        emp.role.toLowerCase().includes(this.search.toLowerCase())
                    );
                }
            }" x-init="
                lucide.createIcons();
                $watch('search', () => { $nextTick(() => lucide.createIcons()); });
            ">

        <div x-effect="filteredEmployees; $nextTick(() => lucide.createIcons())"></div>

        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Equipe e
                    Colaboradores</h1>
                <p class="text-gray-500 dark:text-gray-100 font-medium mt-1 uppercase text-[10px] tracking-widest">Gestão de
                    acessos e cargos</p>
            </div>

            <div class="flex items-center gap-4 flex-1 max-w-md">
                <div class="relative w-full">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" x-model="search" placeholder="BUSCAR NA PÁGINA ATUAL..."
                        class="w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-[10px] font-black tracking-widest focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-800 transition-all outline-none text-gray-800 dark:text-gray-50 uppercase">
                </div>
                <a href="{{ route('funcionarios.create') }}"
                    class="shrink-0 flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg text-[10px] uppercase tracking-widest border-none outline-none">
                    <i data-lucide="user-plus" class="w-4 h-4"></i> Adicionar
                </a>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-gray-900">
                            <th class="px-8 py-5">Nome / Cargo</th>
                            <th class="px-8 py-5">Identificação</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-900">
                        <template x-for="emp in filteredEmployees" :key="emp.id">
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-all"
                                :class="!emp.is_active ? 'opacity-50' : ''">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 flex items-center justify-center font-black text-xs text-gray-400"
                                            x-text="emp.name.substring(0,2).toUpperCase()"></div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-black text-gray-800 dark:text-gray-50 tracking-tight uppercase"
                                                x-text="emp.name"></span>
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest"
                                                x-text="emp.role"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col text-[10px] font-bold text-gray-500 dark:text-gray-400">
                                        <span x-text="'CPF: ' + emp.cpf"></span>
                                        <span x-text="emp.email"></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <form :action="'/funcionarios/' + emp.id + '/status'" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border transition-all cursor-pointer border-none"
                                            :class="emp.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/20 dark:border-emerald-900/50' : 'bg-red-50 text-red-600 border-red-100 dark:bg-red-950/20 dark:border-red-900/50'"
                                            x-text="emp.is_active ? 'Ativo' : 'Inativo'">
                                        </button>
                                    </form>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a :href="'/funcionarios/' + emp.id + '/editar'"
                                            class="p-2 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors border-none outline-none">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form :action="'/funcionarios/' + emp.id" method="POST"
                                            @submit.prevent="if(confirm('Excluir permanentemente?')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-500 transition-colors cursor-pointer border-none bg-transparent outline-none">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div
                class="px-8 py-6 border-t border-gray-100 dark:border-gray-900 bg-gray-50/10 dark:bg-gray-900/10 flex items-center justify-between">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Registros Totais:
                    {{ $employees->total() }}</span>
                <div class="flex items-center">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection