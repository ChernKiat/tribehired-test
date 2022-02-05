<?php

namespace App\Http\Middleware;

class DisableRouteCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)->withHeaders([
            "Pragma" => "no-cache",
            "Expires" => "Fri, 01 Jan 1990 00:00:00 GMT",
            "Cache-Control" => "no-cache, must-revalidate, no-store, max-age=0, private",
        ]);
            // ->middleware('cache.headers:private;no-store;no-cache;must-revalidate;post-check=0;pre-check=0;max-age=0')
    }
}
