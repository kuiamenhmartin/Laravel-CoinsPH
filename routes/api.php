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

Route::get('callback', function () {
    $respo = request()->all();



    if(isset($respo['code'])) {
        echo "here";
        return redirect('api/token/'.$respo['code']);
    }

    dd($respo);

    return redirect('api/reg/'.$respo['access_token']);
    // dd($respo);
});

Route::get('reg', function ($token) {
    $client = new \GuzzleHttp\Client(); //GuzzleHttp\Client
    $result = $client->get('https://coins.ph/api/v2/buyorder', [
            'headers' => array(
              'accept' => 'application/json',
              'authorization' => 'Bearer '.$token
            )
    ]);

    // dd($result);

    dd(json_decode($result->getBody(), true));

});
Route::get('token/{code}', function ($code) {

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

});

/**
 *  Step by Step Authentication with coins.
 *
 * 1. https://site.coins.ph/user/api/authorize?client_id=AvqyiU4aXflEti601FV5UMQLl44mEEdBymaSbGhY&response_type=code&scope=wallet_history+wallet_transfer+user_identity&redirect_uri=http://localhost:8001/api/callback
 *
 * return: Authore=ization Code
 * redirect: api/callback with code
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
 *
 */
// Route::get('reg/{client_id}/{client_secret}/', 'CoinsController@coin');
