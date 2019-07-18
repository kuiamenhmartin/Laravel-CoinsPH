<?php

namespace App\Services\User\Auth\Registration;

use App\User;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Events\UserSignedUpEvent;
use App\Helpers\Traits\UserServiceTrait;

class CreateUserService implements ActionInterface
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
        //Hash user password before saving
        $data['password'] = Hash::make($data['password']);

        //We will create activation code to be used for Email Confirmation
        $data['activation_token'] = $this->createEmailActivationToken();

        //Now we create the user by adding it to our database
        $user = $this->User::create($data);

        //throws an exception if user hasnt yet created
        if (!$user) {
            throw new CustomException('Registration falied!', 500);
        }

        //dispatch an event to send confirmation email and log user creation
        event(new $this->signedUpEvent($user));

        return $user;
    }
}
