<?php

namespace App\Services\User\Auth\Registration;

use App\User;

interface SendConfirmationEmailServiceInterface
{
    public function sendEmail(User $user);
}
