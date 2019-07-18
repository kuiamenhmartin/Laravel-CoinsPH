<?php

namespace App\Services\User\Auth\Registration;

use App\User;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Events\UserSignedUpEvent;
use App\Helpers\Traits\UserServiceTrait;
use Carbon\Carbon;

class ResendConfirmationEmailService implements ActionInterface
{
    use UserServiceTrait;

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

        //We will create activation code to be used for Email Confirmation
        $user->activation_token = $this->createEmailActivationToken();

        //then save the new token
        $user->save();

        //dispatch an event to send confirmation email and log user creation
        event(new $this->signedUpEvent($user));

        return $user;
    }
}
