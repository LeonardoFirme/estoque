<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="w-full space-y-8">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-gray-800 dark:text-gray-50 uppercase">Categorias</h1>
                <p class="text-gray-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest mt-1">
                    Organização hierárquica do catálogo</p>
            </div>
            <a href="{{ route('categories.create') }}"
                class="flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-950 dark:bg-gray-50 dark:hover:bg-gray-200 text-white dark:text-gray-950 font-black rounded-xl transition-all shadow-lg text-[10px] uppercase tracking-widest border-none cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Nova Categoria
            </a>
        </div>

        @if($categories->isEmpty())
            <div
                class="flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-4xl border-dashed">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-2xl flex items-center justify-center mb-4">
                    <i data-lucide="layers" class="w-8 h-8 text-gray-300"></i>
                </div>
                <h3 class="text-sm font-black text-gray-800 dark:text-gray-50 uppercase tracking-tighter">Nenhum registro
                    encontrado</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Comece criando uma categoria
                    principal para organizar seus produtos</p>
                <a href="{{ route('categories.create') }}"
                    class="mt-6 text-[10px] font-black text-emerald-500 hover:text-emerald-600 uppercase tracking-[0.2em] transition-colors">Criar
                    agora —</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $parent)
                    <div
                        class="p-6 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gray-50 dark:bg-gray-900 rounded-xl flex items-center justify-center shadow-xs">
                                    <i data-lucide="folder" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <span
                                    class="text-sm font-black text-gray-800 dark:text-gray-50 uppercase">{{ $parent->name }}</span>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('categories.edit', $parent->id) }}"
                                    class="p-2 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors cursor-pointer border-none bg-transparent">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('categories.create', ['parent_id' => $parent->id]) }}"
                                    class="p-2 text-gray-400 hover:text-emerald-500 transition-colors cursor-pointer border-none bg-transparent"
                                    title="Adicionar Subcategoria">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $parent->id) }}" method="POST"
                                    onsubmit="return confirm('Excluir categoria e subcategorias?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-500 transition-colors cursor-pointer border-none bg-transparent outline-none">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($parent->children->count() > 0)
                            <div class="pl-4 border-l-2 border-gray-100 dark:border-gray-900 space-y-2">
                                @foreach($parent->children as $child)
                                    <div class="flex items-center justify-between group">
                                        <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">—
                                            {{ $child->name }}</span>
                                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                            <a href="{{ route('categories.edit', $child->id) }}"
                                                class="p-1 text-gray-400 hover:text-gray-800 dark:hover:text-gray-50 transition-colors cursor-pointer border-none bg-transparent"><i
                                                    data-lucide="edit-3" class="w-3 h-3"></i></a>
                                            <form action="{{ route('categories.destroy', $child->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-1 text-gray-400 hover:text-red-500 transition-all border-none bg-transparent cursor-pointer"><i
                                                        data-lucide="x" class="w-3 h-3"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-[9px] font-black text-gray-300 dark:text-gray-700 uppercase tracking-widest italic">Nenhuma
                                subcategoria</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection