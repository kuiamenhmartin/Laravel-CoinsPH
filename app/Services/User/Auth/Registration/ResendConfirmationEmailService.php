<?php

namespace App\Services\User\Auth\Registration;

use App\User;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Events\UserSignedUpEvent;
use Carbon\Carbon;
use App\Helpers\QioskApp;

class ResendConfirmationEmailService implements ActionInterface
{
    protected $signedUpEvent;

    protected $User;

    public function __construct(UserSignedUpEvent $signedUpEvent, User $User)
    {
        $this->signedUpEvent = $signedUpEvent;

        $this->User = $User;
    }

    public function execute(array $data) : User
    {
        $user = $this->User::where(['id' => $data[0]])->first();

        if (!$user) {
            throw new CustomException('Something went wrong.', 404);
        }

        if (!is_null($user->email_verified_at)) {
            throw new CustomException('Your email was already verified.', 403);
        }

        //We will create activation code to be used for Email Confirmation
        $user->activation_token = QioskApp::createToken('email');

        //then save the new token
        $user->save();

        //dispatch an event to send confirmation email and log user creation
        event(new $this->signedUpEvent($user));

        return $user;
    }
}
