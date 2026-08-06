<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('seo.enforce_canonical_host')) {
            return $next($request);
        }

        $canonicalUrl = parse_url((string) config('app.url'));
        $canonicalHost = $canonicalUrl['host'] ?? null;

        if (! $canonicalHost || strcasecmp($request->getHost(), $canonicalHost) === 0) {
            return $next($request);
        }

        $scheme = $canonicalUrl['scheme'] ?? 'https';
        $port = isset($canonicalUrl['port']) ? ':'.$canonicalUrl['port'] : '';
        $target = $scheme.'://'.$canonicalHost.$port.$request->getRequestUri();

        return redirect()->away($target, $request->isMethodSafe() ? 301 : 308);
    }
}
