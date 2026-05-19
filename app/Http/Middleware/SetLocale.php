<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next, ?string $locale = null): Response
    {
        $locale = $locale ?: $request->route('locale') ?: config('locales.default', 'vi');

        if (! array_key_exists($locale, config('locales.supported', []))) {
            abort(404);
        }

        App::setLocale($locale);
        $request->attributes->set('locale', $locale);

        return $next($request);
    }
}
