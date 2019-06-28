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
        $apis = $request->user()->getAppCredential($request->route('app_name'))->first();

        //when api credential not provied
        if(is_null($apis)) {
          return \QioskApp::httpResponse(
              \QioskApp::UNAUTHENTICATED,
              'You haven\'t provided an API credential yet.',
              [],
              403
          );
        }

        /**
         * let's dynamically add api credential to laravel config.
         * This is only valid on the current request
         */
        \Config::set($apis->app_name, [
          'client_id' => $apis->client_id,
          'client_secret' => $apis->client_secret,
          'scopes' => $apis->scopes,
          'redirect_uri' => $apis->redirect_uri,
        ]);

        //continue process request
        return $next($request);;
    }
}
