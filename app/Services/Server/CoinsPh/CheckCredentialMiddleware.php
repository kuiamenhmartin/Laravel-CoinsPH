<?php

namespace App\Services\Server\CoinsPh;

use Closure;

use Illuminate\Support\Str;

use App\Models\UserExternalApiCredentials;

class CheckCredentialMiddleware
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
        /**
         * Get your api credential
         * @var [type]
         */
        $apis = $request->user()->getApiCredential()->first();

        //when api credential not provied
        if (is_null($apis)) {
            return \QioskApp::httpResponse(
                \QioskApp::UNAUTHENTICATED,
                'You haven\'t provided an API credential yet.',
                [],
                403
            );
        }

        //continue process request
        return $next($request);
    }
}
