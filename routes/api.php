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
