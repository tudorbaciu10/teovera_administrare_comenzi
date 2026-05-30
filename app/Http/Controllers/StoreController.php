<?php

namespace App\Http\Controllers;

use App\Models\Route as DeliveryRoute;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        $stores = Store::with('route')->orderBy('denumire')->get();

        return view('stores.index', compact('stores'));
    }

    public function create(): View
    {
        $routes = DeliveryRoute::orderBy('nume')->get();

        return view('stores.create', compact('routes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'denumire'  => ['required', 'string', 'max:255'],
            'localitate'=> ['required', 'string', 'max:255'],
            'adresa'    => ['nullable', 'string', 'max:500'],
            'route_id'  => ['nullable', 'exists:routes,id'],
            'tip'       => ['required', 'in:magazin,angro'],
        ]);

        Store::create($request->only('denumire', 'localitate', 'adresa', 'route_id', 'tip'));

        return redirect()->route('stores.index')->with('success', __('admin.salvat_succes'));
    }

    public function show(Store $store): View
    {
        $store->load('route', 'users');

        return view('stores.show', compact('store'));
    }

    public function edit(Store $store): View
    {
        $routes = DeliveryRoute::orderBy('nume')->get();

        return view('stores.edit', compact('store', 'routes'));
    }

    public function update(Request $request, Store $store): RedirectResponse
    {
        $request->validate([
            'denumire'  => ['required', 'string', 'max:255'],
            'localitate'=> ['required', 'string', 'max:255'],
            'adresa'    => ['nullable', 'string', 'max:500'],
            'route_id'  => ['nullable', 'exists:routes,id'],
            'tip'       => ['required', 'in:magazin,angro'],
        ]);

        $store->update($request->only('denumire', 'localitate', 'adresa', 'route_id', 'tip'));

        return redirect()->route('stores.index')->with('success', __('admin.salvat_succes'));
    }

    public function destroy(Store $store): RedirectResponse
    {
        $store->delete();

        return redirect()->route('stores.index')->with('success', __('admin.sters_succes'));
    }

    public function regenerateToken(Store $store): RedirectResponse
    {
        $store->update(['token_acces' => Str::random(32)]);

        return back()->with('success', 'Token regenerat cu succes.');
    }
}
