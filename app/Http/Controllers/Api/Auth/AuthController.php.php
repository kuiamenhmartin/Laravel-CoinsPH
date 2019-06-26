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
        return QioskApp::checkAction(function() use($request, $action)
        {
            $user = $action->execute($request->validated());

            //throw success when action executes succesfully
            return QioskApp::httpResponse(QioskApp::SUCCESS, ['token' => $user->token], 200);
        });
    }

    public function login(LoginUserRequest $request, LoginUserService $action)
    {
        return QioskApp::checkAction(function() use($request, $action)
        {
            $user = $action->execute($request->validated());

            //throw success when action executes succesfully
            return QioskApp::httpResponse(QioskApp::SUCCESS, ['token' => $user->token], 200);
        });
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return QioskApp::httpResponse(QioskApp::SUCCESS, ['message' => 'You have been succesfully logged out!'], 200);
    }

    public function signupActivate($token, ConfirmEmailService $action)
    {
        return QioskApp::checkAction(function() use($token, $action)
        {
            $user = $action->execute([$token]);

            //throw success when action executes succesfully
            return QioskApp::httpResponse(QioskApp::SUCCESS, ['message' => 'Your email is now verified!'], 200);
        });
    }
}

/**
* Reference https://medium.com/modulr/create-api-authentication-passport-in-laravel-5-6-confirm-account-notifications-part-2-5e221b021f07
*
*/
