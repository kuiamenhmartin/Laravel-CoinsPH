<?php

namespace App\Services\User\Auth\Registration;

use App\Notifications\SignupActivate;
use App\Services\User\Auth\Registration\SendConfirmationEmailServiceInterface;
use App\User;
use Log;

class SendConfirmationEmailService implements SendConfirmationEmailServiceInterface
{
    public function sendEmail(User $user)
    {
        $user->notify(new SignupActivate($user));
    }
}
