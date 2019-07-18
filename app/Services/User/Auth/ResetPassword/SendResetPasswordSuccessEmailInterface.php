<?php

namespace App\Services\User\Auth\ResetPassword;

use App\User;

interface SendResetPasswordSuccessEmailInterface
{
    public function sendEmail(User $user);
}
