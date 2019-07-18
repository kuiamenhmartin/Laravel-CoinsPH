<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

#Import Custom Requests
use App\Http\Requests\User\Auth\CreateUserRequest;
use App\Http\Requests\User\Auth\LoginUserRequest;

#Import Custom Services
use App\Services\User\Auth\Login\LoginUserService;
use App\Services\User\Auth\Registration\CreateUserService;
use App\Services\User\Auth\Registration\ConfirmEmailService;
use App\Services\User\Auth\Registration\ResendConfirmationEmailService;

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
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'You have successfully signed up. Please verify your email to start using this app.', ['user_id' => $user->id]);
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
        return QioskApp::httpResponse(QioskApp::SUCCESS, '', ['token' => $user->token, 'name' => $user->name]);
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
    public function emailActivation($token, ConfirmEmailService $action): Response
    {
        $user = $action->execute([$token]);

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'Your email is now verified!');
    }

    /**
     * REsend Confirm email
     *
     * @return Response
     */
    public function resendConfirmationEmail($userId, ResendConfirmationEmailService $action): Response
    {
        $user = $action->execute([$userId]);

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'Email confirmation has been resent.');
    }
}

/**
* Reference https://medium.com/modulr/create-api-authentication-passport-in-laravel-5-6-confirm-account-notifications-part-2-5e221b021f07
*
*/
