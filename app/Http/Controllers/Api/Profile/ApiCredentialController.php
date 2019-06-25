<?php

namespace App\Http\Controllers\Api\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

#Import Custom Requests
use App\Http\Requests\User\CreateUserRequest;

#Import Custom Services
use App\Services\User\CreateUserService;

#Import App Helper
use App\Helpers\QioskApp;

class ApiCredentialController extends Controller
{
    public function __construct()
    {
        //
    }

    public function register(CreateUserRequest $request, CreateUserService $action)
    {
        return QioskApp::checkAction(function() use($request, $action)
        {
            $user = $action->execute($request->all());

            //throw success when action executes succesfully
            return QioskApp::httpResponse(QioskApp::SUCCESS, ['token' => $user->token], 200);
        });
    }
}
