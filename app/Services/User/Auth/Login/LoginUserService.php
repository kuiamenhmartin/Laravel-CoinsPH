<?php

namespace App\Services\User\Auth\Login;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Exceptions\CustomException;
use Carbon;
use App\Helpers\QioskApp;

class LoginUserService implements ActionInterface
{
    protected $User;

    public function __construct(User $User)
    {
        $this->User = $User;
    }

    public function execute(array $data): User
    {
        $user = $this->User::where('email', $data['email'])->first();

        if (!$user) {
            throw new CustomException('Email or password is invalid!', 500);
        }

        if (is_null($user->email_verified_at) || !$user->isEmailVerifiedDateValid()) {
            throw new CustomException("Email ".$data['email']." not verified!", 500);
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new CustomException('Email or password missmatched!', 500);
        }

        $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return $user;
    }
}

/**
* Reference https://medium.com/modulr/create-api-authentication-passport-in-laravel-5-6-confirm-account-notifications-part-2-5e221b021f07
*
*/
