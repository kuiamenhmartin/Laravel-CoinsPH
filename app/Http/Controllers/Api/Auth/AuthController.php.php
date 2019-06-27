<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    /**
     * Register a user to our platform
     *
     * @return Response
     */
    public function register(CreateUserRequest $request, CreateUserService $action): Response
    {
        $user = $action->execute($request->validated());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, '', ['token' => $user->token]);
    }

    /**
     * Login a user to our platform
     *
     * @return Response
     */
    public function login(LoginUserRequest $request, LoginUserService $action): Response
    {
        $user = $action->execute($request->validated());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, '', ['token' => $user->token]);
    }

    /**
     * Logout user to our platform
     *
     * @return Response
     */
    public function logout(Request $request): Response
    {
        $token = $request->user()->token();
        $token->revoke();

        return QioskApp::httpResponse(QioskApp::SUCCESS, 'You have been succesfully logged out!');
    }

    /**
     * Confirm email upon successfull registration
     *
     * @return Response
     */
    public function signupActivate($token, ConfirmEmailService $action): Response
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
