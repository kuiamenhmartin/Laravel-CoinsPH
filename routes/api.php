<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Adding State Parameter in API Request : https://youtu.be/_xrhWLqX1j0
*/

#Routes for authentication
Route::group([
    'prefix' => 'auth',
    'middleware' => 'json.response'
], function () {
    Route::post('login', 'Api\Auth\AuthController@login')->name('login.api');
    Route::post('register', 'Api\Auth\AuthController@register')->name('register.api');
    Route::get('signup/activate/{token}', 'Api\Auth\AuthController@signupActivate');

    Route::group([
      'middleware' => ['auth:api','verified']
    ], function () {
        Route::get('logout', 'Api\Auth\AuthController@logout')->name('logout');
    });
});

#Route to add/edit/delete user's api credential
Route::group([
    'middleware' => ['auth:api','verified'],
    'prefix' => 'profile'
], function () {
    Route::post('credential', 'Api\Profile\ApiCredentialController@store')->name('api.credential.store');
    Route::patch('credential/{api_credential_id}', 'Api\Profile\ApiCredentialController@update')->name('api.credential.update');
    Route::delete('credential/{api_credential_id}', 'Api\Profile\ApiCredentialController@destroy')->name('api.credential.delete');
});

#Connect to CoinsPH
Route::group([
    'middleware' => ['auth:api','verified', 'api.credential'],
    'prefix' => 'connect'
], function () {
    Route::get('{app_name}', 'Api\Server\CoinsPh\GatewayController@getConfig')->name('api.connect');
});


//@TODO Call the getConfig, it will return api credentials
//@TODO In javascript/frontend, populate the credentials in the url params below wth state param {id, datetime, app_name}
//@TODO https://site.coins.ph/user/api/authorize?client_id=AvqyiU4aXflEti601FV5UMQLl44mEEdBymaSbGhY&response_type=code&scope=wallet_history+wallet_transfer+user_identity&redirect_uri=http://localhost:8001/api/callback
//@TODO It will redirect to callback url and return the authorization_code and state parameters, use that code to get the access_toke and refresh_token
//@TODO callback url will then redirect to api/coinsph/token/code and ask for access_tokens, attempt to login the user back using state parameter
//@TODO save the tokens in the DB, create new table where we can save them
//@TODO add the access token to header

Route::get('{app_name}/callback', 'Api\Server\CoinsPh\GatewayController@callback')->name('api.callback');
Route::get('{app_name}/token/{code}', function ($code) {

// dd($code);
    // echo "dsdsds";
    // dd($code);
    try{
    $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client
    $result = $client->post('https://coins.ph/user/oauthtoken', [
            'query' => array(
              'client_id' => 'AvqyiU4aXflEti601FV5UMQLl44mEEdBymaSbGhY',
              'client_secret' => 'F99Sb7kRxkeiIMGEnUMOIoPLZBIozFm2MNXFaTch3hJA0TKN3Q',
              'code' => $code,
              'grant_type' => 'authorization_code',
              'redirect_uri' => "http://localhost:8001/api/callback"
          ),
          'headers' => [
              'Content-Type' => 'application/x-www-form-urlencoded',
              'Accept' => 'application/json'
          ]
    ]);

    dd(json_decode($result->getBody(), true));
} catch (GuzzleException $e) {

    dd($e);
    // dd($e);
}

    dd($result);

    dd(json_decode($result->getBody(), true));

})->name('api.token');

#Define fallback route
Route::fallback(function () {
    return response()->json(
        [
            'status' => 'PAGE_NOT_FOUND',
            'code' => 404,
            'message' => 'Page Not Found. If error persists, contact info@bitwallet.com'
        ],
        404
    );
});




/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////


Route::get('reg', function ($token) {
    $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client
    $result = $client->get('https://coins.ph/api/v2/buyorder', [
      'headers' => array(
        'accept' => 'application/json',
        'authorization' => 'Bearer '.$token
      )
    ]);
    dd(json_decode($result->getBody(), true));

})->name('api.buy');


/**
 *  Step by Step Authentication with coins.
 *
 * 1. https://site.coins.ph/user/api/authorize?client_id=AvqyiU4aXflEti601FV5UMQLl44mEEdBymaSbGhY&response_type=code&scope=wallet_history+wallet_transfer+user_identity&redirect_uri=http://localhost:8001/api/callback
 *
 *
 * return: Authore=ization Code
 * redirect: api/callback with code ans state as access token
 * then, redirect to : api/token/{code}
 * then, it will return
 *
 * array:4 [▼
 * "access_token" => "H9skMD8sdTrS8tcUi7utV3y18sPEDK"
 * "token_type" => "Bearer"
 * "refresh_token" => "wKq6H8yxUGf71fMtniC863mqdxVuVw"
 * "scope" => "buyorder sellorder history wallet_history wallet_transfer user_identity"
 * ]
 *
 */
// Route::get('reg/{client_id}/{client_secret}/', 'CoinsController@coin');
