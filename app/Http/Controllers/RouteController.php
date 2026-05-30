<?php

namespace App\Http\Controllers;

use App\Models\Route as DeliveryRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    private const ZILE = ['luni', 'marti', 'miercuri', 'joi', 'vineri', 'sambata', 'duminica'];

    public function index(): View
    {
        $routes = DeliveryRoute::withCount('stores')->orderBy('nume')->get();

        return view('routes.index', compact('routes'));
    }

    public function create(): View
    {
        return view('routes.create', ['zile' => self::ZILE]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nume'          => ['required', 'string', 'max:255'],
            'zile_livrare'  => ['required', 'array', 'min:1'],
            'zile_livrare.*'=> ['in:'.implode(',', self::ZILE)],
            'zi_cutoff'     => ['required', 'in:'.implode(',', self::ZILE)],
            'ora_cutoff'    => ['required', 'date_format:H:i'],
        ]);

        DeliveryRoute::create([
            'nume'         => $request->nume,
            'zile_livrare' => $request->zile_livrare,
            'zi_cutoff'    => $request->zi_cutoff,
            'ora_cutoff'   => $request->ora_cutoff,
        ]);

        return redirect()->route('routes.index')->with('success', __('admin.salvat_succes'));
    }

    public function edit(DeliveryRoute $route): View
    {
        return view('routes.edit', ['route' => $route, 'zile' => self::ZILE]);
    }

    public function update(Request $request, DeliveryRoute $route): RedirectResponse
    {
        $request->validate([
            'nume'          => ['required', 'string', 'max:255'],
            'zile_livrare'  => ['required', 'array', 'min:1'],
            'zile_livrare.*'=> ['in:'.implode(',', self::ZILE)],
            'zi_cutoff'     => ['required', 'in:'.implode(',', self::ZILE)],
            'ora_cutoff'    => ['required', 'date_format:H:i'],
        ]);

        $route->update([
            'nume'         => $request->nume,
            'zile_livrare' => $request->zile_livrare,
            'zi_cutoff'    => $request->zi_cutoff,
            'ora_cutoff'   => $request->ora_cutoff,
        ]);

        return redirect()->route('routes.index')->with('success', __('admin.salvat_succes'));
    }

    public function destroy(DeliveryRoute $route): RedirectResponse
    {
        $route->delete();

        return redirect()->route('routes.index')->with('success', __('admin.sters_succes'));
    }
}
