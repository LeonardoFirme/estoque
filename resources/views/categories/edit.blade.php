<!-- resources/views/categories/edit.blade.php -->
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
                    <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Editar
                        Categoria</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">
                        Alterando: {{ $category->name }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="w-full grid grid-cols-12 gap-8">
            @csrf
            @method('PUT')

            <div class="col-span-12 lg:col-span-8 space-y-8">
                <div
                    class="p-10 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-4xl shadow-xs space-y-10">

                    @if($category->parent_id)
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-1">Posicionamento
                                na Árvore</label>
                            <div
                                class="w-full p-6 bg-emerald-50/30 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-900/30 rounded-3xl flex items-center gap-4 transition-all">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-950 flex items-center justify-center shadow-xs">
                                    <i data-lucide="layers" class="w-5 h-5 text-emerald-500"></i>
                                </div>
                                <div>
                                    <span class="text-[11px] font-black text-emerald-500 uppercase tracking-widest">Dependente
                                        de: {{ $category->parent->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-1">Título da
                            Categoria</label>
                        <input type="text" name="name" value="{{ $category->name }}" required
                            class="w-full px-8 py-5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl text-lg font-black text-gray-800 dark:text-gray-50 outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all uppercase">
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] ml-1">Notas e
                            Definições</label>
                        <textarea name="description" rows="6"
                            class="w-full px-8 py-6 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl text-sm font-bold text-gray-800 dark:text-gray-50 outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all leading-relaxed">{{ $category->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="p-10 bg-gray-800 dark:bg-gray-50 rounded-4xl shadow-2xl space-y-8 h-fit">
                    <div class="space-y-2">
                        <h3 class="text-white dark:text-gray-950 font-black text-xl uppercase tracking-tighter">Modificar
                            Registro</h3>
                        <p
                            class="text-gray-400 dark:text-gray-500 text-[10px] font-bold uppercase tracking-widest leading-relaxed">
                            As alterações serão aplicadas a todos os produtos vinculados a esta categoria.</p>
                    </div>

                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full py-6 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-lg transition-all border-none cursor-pointer">Salvar
                            Mudanças</button>
                        <a href="{{ route('categorias') }}"
                            class="block w-full py-6 text-center text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 bg-transparent border-2 border-gray-700 dark:border-gray-200 rounded-2xl hover:bg-gray-700 dark:hover:bg-gray-100 transition-all">Cancelar</a>
                    </div>
                </div>

                <div class="p-8 border border-gray-200 dark:border-gray-800 rounded-4xl flex items-start gap-4">
                    <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500"></i>
                    </div>
                    <div class="space-y-1">
                        <span
                            class="text-[10px] font-black text-gray-800 dark:text-gray-50 uppercase tracking-widest">Atenção</span>
                        <p class="text-[9px] font-bold text-gray-400 uppercase leading-relaxed">Ao mudar o nome, o SLU de
                            URL também será atualizado. Isso pode impactar links externos se houver integração.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection