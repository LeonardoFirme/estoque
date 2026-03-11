<!-- resources/views/categories/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-8">
            <div class="flex items-center gap-5">
                <a href="{{ route('categorias') }}"
                    class="p-3 bg-gray-50 dark:bg-gray-900 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 rounded-2xl border border-gray-200 dark:border-gray-800 transition-all shadow-xs outline-none group">
                    <i data-lucide="chevron-left" class="w-6 h-6 transition-transform group-hover:-translate-x-1"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Nova
                        Categoria</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">
                        Definição de estrutura e hierarquia do estoque</p>
                </div>
            </div>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="w-full grid grid-cols-12 gap-8">
            @csrf
            <div class="col-span-12 lg:col-span-8 space-y-8">
                <div
                    class="p-10 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-4xl shadow-xs space-y-10">

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-1">Vínculo de
                            Hierarquia</label>
                        <input type="hidden" name="parent_id" value="{{ request('parent_id') }}">
                        <div
                            class="w-full p-6 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl flex items-center gap-4 group transition-all">
                            <div
                                class="w-10 h-10 rounded-xl bg-white dark:bg-gray-950 flex items-center justify-center shadow-xs">
                                <i data-lucide="git-branch" class="w-5 h-5 text-emerald-500"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-black text-emerald-500 uppercase tracking-widest">
                                    @if(request('parent_id'))
                                        @php $p = \App\Models\Category::find(request('parent_id')); @endphp
                                        Sub-categoria de: {{ $p ? $p->name : 'Principal' }}
                                    @else
                                        Categoria Raiz (Nível 0)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-1">Identificação da
                            Categoria</label>
                        <input type="text" name="name" required placeholder="DIGITE O NOME EX: ACESSÓRIOS PARA PC..."
                            class="w-full px-8 py-5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl text-lg font-black text-gray-800 dark:text-gray-50 outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all uppercase placeholder:text-gray-300 dark:placeholder:text-gray-700">
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-1">Descrição e
                            Observações</label>
                        <textarea name="description" rows="6"
                            placeholder="DESCREVA O PROPÓSITO DESTA CATEGORIA PARA O INVENTÁRIO..."
                            class="w-full px-8 py-6 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all leading-relaxed"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div
                    class="p-10 bg-gray-800 dark:bg-gray-50 rounded-4xl shadow-2xl space-y-8 flex flex-col justify-between h-fit">
                    <div class="space-y-2">
                        <h3 class="text-white dark:text-gray-950 font-black text-xl uppercase tracking-tighter">Ações de
                            Registro</h3>
                        <p
                            class="text-gray-400 dark:text-gray-500 text-[10px] font-bold uppercase tracking-widest leading-relaxed">
                            Certifique-se de que o nome é único para evitar conflitos no PDV.</p>
                    </div>

                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full py-6 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-lg transition-all border-none cursor-pointer active:scale-95">Confirmar
                            e Salvar</button>
                        <a href="{{ route('categorias') }}"
                            class="block w-full py-6 text-center text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 bg-transparent border-2 border-gray-700 dark:border-gray-200 rounded-2xl hover:bg-gray-700 dark:hover:bg-gray-100 transition-all">Descartar</a>
                    </div>
                </div>

                <div class="p-8 border border-gray-200 dark:border-gray-800 rounded-4xl space-y-4">
                    <div class="flex items-center gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-gray-400"></i>
                        <span class="text-[10px] font-black text-gray-800 dark:text-gray-50 uppercase tracking-widest">Dica
                            Estratégica</span>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase leading-relaxed tracking-tighter">Categorias bem
                        estruturadas permitem relatórios de vendas por departamento muito mais precisos no fechamento
                        diário.</p>
                </div>
            </div>
        </form>
    </div>
@endsection