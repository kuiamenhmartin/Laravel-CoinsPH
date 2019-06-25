<?php

namespace App\Services\User;

use App\User;

interface SendConfirmationEmailInterface
{
    public function sendEmail(User $user);
}
