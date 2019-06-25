<?php

namespace App\Services\User;

use App\Notifications\SignupActivate;
use App\Services\User\SendConfirmationEmailInterface;
use App\User;
use Log;

class SendConfirmationEmailService implements SendConfirmationEmailInterface
{
    public function sendEmail(User $user)
    {
        $user->notify(new SignupActivate($user));
    }
}
