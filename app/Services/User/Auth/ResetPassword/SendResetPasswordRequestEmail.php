<?php

namespace App\Services\User\Auth\ResetPassword;

use App\Notifications\PasswordResetRequest;
use App\Services\User\Auth\ResetPassword\SendResetPasswordRequestEmailInterface;
use App\Models\PasswordReset;
use Log;

class SendResetPasswordRequestEmail implements SendResetPasswordRequestEmailInterface
{
    public function sendEmail(PasswordReset $passwordReset)
    {
        $passwordReset->notify(new PasswordResetRequest($passwordReset));
    }
}
