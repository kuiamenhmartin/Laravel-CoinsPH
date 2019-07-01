<?php

namespace App\Http\Controllers\Api\Server\CoinsPh;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Illuminate\Support\Arr;

// Import Service
use App\Services\Server\CoinsPh\Adapters\Gateway\GenerateApiParameterService;
use App\Services\Server\CoinsPh\Adapters\Gateway\ValidateCodeAndStateService;
use App\Services\Server\CoinsPh\Adapters\Gateway\AccessTokenService;
use App\Services\Server\CoinsPh\Adapters\Gateway\RefreshTokenService;

// Import App Helpers
use App\Helpers\QioskApp;

class GatewayController extends Controller
{
    /**
    * Create an new buyer
    *
    * For complete ref -> https://docs.coins.asia/docs/create-buyorder
    *
    * @param $action GenerateApiParameterService
    *
    * @return Response
    */
    public function getConfig(GenerateApiParameterService $action): Response
    {
        $appName = request()->route('app_name');

        $parameters = ['app_name' => $appName, 'token' => request()->bearerToken(), 'user_id' => request()->user()->id];

        $result = $action->execute($parameters);

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, sprintf('Your API Credential for %s', $appName), $result);
    }

    /**
     * The callback where the authorization server will redirect
     * This will give us the code that we will be using to get api_token
     *
     * @param $validationAction ValidateCodeAndStateService
     * @param $accessTokenAction AccessTokenService
     *
     * @return Response
     */
    public function callback(ValidateCodeAndStateService $validationAction, AccessTokenService $accessTokenAction): Response
    {
        $appName = request()->route('app_name');

        $parameters = Arr::add(request()->all(), 'app_name', $appName);

        $validated = $validationAction->execute($parameters);

        $yourApiToken = $accessTokenAction->execute($validated);

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, sprintf('Your are now connected to %s', $appName), [
            'atoken' => $yourApiToken,
            'ptoken' => $validated['token']
        ]);
    }
}
