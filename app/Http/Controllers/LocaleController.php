<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, ['ro', 'ru'])) {
            abort(404);
        }

        session(['locale' => $locale]);

        if ($request->user()) {
            $request->user()->update(['lang' => $locale]);
        }

        return back();
    }
}
