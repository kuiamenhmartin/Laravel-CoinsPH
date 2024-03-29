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
    Route::get('signup/activate/{token}', 'Api\Auth\AuthController@emailActivation');
    Route::get('signup/resend-confirmation-email/{user_id}/', 'Api\Auth\AuthController@resendConfirmationEmail');


    Route::post('password/create', 'Api\Auth\PasswordResetController@create');
    Route::get('password/find/{token}', 'Api\Auth\PasswordResetController@find');
    Route::post('password/reset', 'Api\Auth\PasswordResetController@reset');

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
    Route::get('credential', 'Api\Profile\ApiCredentialController@index')->name('api.credential.index');
    Route::patch('credential/{api_credential_id}', 'Api\Profile\ApiCredentialController@update')->name('api.credential.update');
    Route::delete('credential/{api_credential_id}', 'Api\Profile\ApiCredentialController@destroy')->name('api.credential.delete');
});

#Connect to CoinsPH
Route::group([
    'middleware' => ['auth:api','verified', 'api.credential'],
    'prefix' => 'generate'
], function () {
    Route::get('config-as-params', 'Api\Server\CoinsPh\GatewayController@getConfig')->name('api.connect');
    Route::get('access-token', 'Api\Server\CoinsPh\GatewayController@generateAccessToken')->name('api.connect.accesstoken');
});

Route::get('{app_name}/callback', 'Api\Server\CoinsPh\GatewayController@callback')->name('api.callback');

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
