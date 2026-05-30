<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('ordine_sortare')->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nume_ro'        => ['required', 'string', 'max:255'],
            'nume_ru'        => ['required', 'string', 'max:255'],
            'ordine_sortare' => ['required', 'integer', 'min:0'],
        ]);

        Category::create($request->only('nume_ro', 'nume_ru', 'ordine_sortare'));

        return redirect()->route('categories.index')->with('success', __('admin.salvat_succes'));
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'nume_ro'        => ['required', 'string', 'max:255'],
            'nume_ru'        => ['required', 'string', 'max:255'],
            'ordine_sortare' => ['required', 'integer', 'min:0'],
        ]);

        $category->update($request->only('nume_ro', 'nume_ru', 'ordine_sortare'));

        return redirect()->route('categories.index')->with('success', __('admin.salvat_succes'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', __('admin.sters_succes'));
    }
}
