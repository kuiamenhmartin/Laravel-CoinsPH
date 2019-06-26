<?php

namespace App\Services\User;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Exceptions\CustomException;

class LoginUserService implements ActionInterface
{
    protected $signedUp;

    public function __construct()
    {
        //
    }

    public function execute(array $data) : User
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw new CustomException('User not found : '.$data['email'], 200);
        }

        if (is_null($user->email_verified_at)) {
            throw new CustomException("Email ".$data['email']." not verified!", 200);
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new CustomException('Password missmatch!', 200);
        }

        $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return $user;
    }
}

/**
* Reference https://medium.com/modulr/create-api-authentication-passport-in-laravel-5-6-confirm-account-notifications-part-2-5e221b021f07
*
*/
