<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (! $locale && $request->user()) {
            $locale = $request->user()->lang;
        }

        if (! $locale) {
            $locale = config('app.locale', 'ro');
        }

        if (in_array($locale, ['ro', 'ru'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
