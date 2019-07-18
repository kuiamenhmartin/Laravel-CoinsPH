<?php

namespace App\Services\User\Auth\ResetPassword;

use App\Models\PasswordReset;

interface SendResetPasswordRequestEmailInterface
{
    public function sendEmail(PasswordReset $passwordReset);
}
