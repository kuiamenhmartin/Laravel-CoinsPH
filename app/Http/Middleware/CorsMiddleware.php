<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Add http headers on the http response
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('X-Frame-Options', 'SAMEORIGIN') //DENY
          ->header('Content-Security-Policy', "script-src 'self'")
          ->header('X-XSS-Protection', '1; mode=block')
          ->header('X-Content-Type-Options', 'nosniff')
          ->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}
