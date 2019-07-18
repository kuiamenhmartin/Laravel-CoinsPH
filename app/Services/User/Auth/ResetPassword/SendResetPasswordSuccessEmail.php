<?php

namespace App\Services\User\Auth\ResetPassword;

use App\Notifications\PasswordResetSuccess;
use App\Services\User\Auth\ResetPassword\SendResetPasswordSuccessEmailInterface;
use App\User;
use Log;

class SendResetPasswordSuccessEmail implements SendResetPasswordSuccessEmailInterface
{
    public function sendEmail(User $user)
    {
        $user->notify(new PasswordResetSuccess($user));
    }
}
