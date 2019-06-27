<?php

namespace App\Services\User;

use App\User;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Events\UserSignedUpEvent;

class CreateUserService implements ActionInterface
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
        //Hash user password before saving
        $data['password']= Hash::make($data['password']);

        //We will create activation code to be used for Email Confirmation
        $data['activation_token'] = str_random(60);

        //Now we create the user by adding it to our DB
        $user = $this->User::create($data);

        //throws an exception if user hasnt yet created
        if (!$user) {
            throw new CustomException('Registration falied!', 500);
        }

        //Create User access token for stateless connection
        $token = $user->createToken('Laravel Password Grant Clients')->accessToken;
        $user->token = $token;

        //dispatch an event to send confirmation email and log user creation
        event(new $this->signedUpEvent($user));

        return $user;
    }
}
