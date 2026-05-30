<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->orderBy('category_id')->orderBy('nume_ro')->get();

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('ordine_sortare')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nume_ro'     => ['required', 'string', 'max:255'],
            'nume_ru'     => ['required', 'string', 'max:255'],
            'unitate'     => ['required', 'in:kg,buc'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        Product::create([
            'nume_ro'     => $request->nume_ro,
            'nume_ru'     => $request->nume_ru,
            'unitate'     => $request->unitate,
            'category_id' => $request->category_id,
            'activ'       => $request->boolean('activ', true),
        ]);

        return redirect()->route('products.index')->with('success', __('admin.salvat_succes'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('ordine_sortare')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'nume_ro'     => ['required', 'string', 'max:255'],
            'nume_ru'     => ['required', 'string', 'max:255'],
            'unitate'     => ['required', 'in:kg,buc'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $product->update([
            'nume_ro'     => $request->nume_ro,
            'nume_ru'     => $request->nume_ru,
            'unitate'     => $request->unitate,
            'category_id' => $request->category_id,
            'activ'       => $request->boolean('activ'),
        ]);

        return redirect()->route('products.index')->with('success', __('admin.salvat_succes'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', __('admin.sters_succes'));
    }
}
