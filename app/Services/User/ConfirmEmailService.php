<?php

namespace App\Services\User;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Exceptions\CustomException;
use Carbon\Carbon;

class ConfirmEmailService implements ActionInterface
{
    protected $signedUp;

    public function __construct()
    {
        //
    }

    public function execute(array $token) : User
    {
        $user = User::where(['activation_token' => $token[0], 'email_verified_at' => null])->first();

        if (!$user) {
            throw new CustomException('This activation token is invalid.', 404);
        }

        $user->email_verified_at = Carbon::now();
        $user->activation_token = '';
        $user->save();

        return $user;
    }
}
