<?php
// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string']
        ]);

        Category::create($validated);

        return redirect()->route('categorias')->with('success', 'CATEGORIA REGISTRADA COM SUCESSO.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string']
        ]);

        $category->update($validated);

        return redirect()->route('categorias')->with('success', 'CATEGORIA ATUALIZADA COM SUCESSO.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('categorias')->with('success', 'CATEGORIA REMOVIDA.');
    }
}