<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

#Import Custom Requests
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;

#Import Custom Services
use App\Services\User\CreateUserService;
use App\Services\User\LoginUserService;
use App\Services\User\ConfirmEmailService;

#Import App Helper
use App\Helpers\QioskApp;

use Illuminate\Support\Facades\Gate;

class AuthController extends Controller
{
    public function __construct()
    {
        //
    }

    public function register(CreateUserRequest $request, CreateUserService $action)
    {
        $user = $action->execute($request->validated());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, '', ['token' => $user->token]);
    }

    public function login(LoginUserRequest $request, LoginUserService $action)
    {
        $user = $action->execute($request->validated());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, '', ['token' => $user->token]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return QioskApp::httpResponse(QioskApp::SUCCESS, 'You have been succesfully logged out!');
    }

    public function signupActivate($token, ConfirmEmailService $action)
    {
        $user = $action->execute([$token]);

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'Your email is now verified!');
    }
}

/**
* Reference https://medium.com/modulr/create-api-authentication-passport-in-laravel-5-6-confirm-account-notifications-part-2-5e221b021f07
*
*/
