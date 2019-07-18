<?php

namespace App\Services\User\Auth\ResetPassword;

use App\Exceptions\CustomException;

# Import needed models
use App\User;
use App\Models\PasswordReset;

use App\Services\ActionInterface;
use App\Events\UserResetPasswordRequestEvent;

class CreateResetPasswordRequestService implements ActionInterface
{
    protected $ResetPasswordRequestEvent;

    protected $User;

    protected $PasswordReset;

    public function __construct(UserResetPasswordRequestEvent $ResetPasswordRequestEvent, User $User, PasswordReset $PasswordReset)
    {
        $this->ResetPasswordRequestEvent = $ResetPasswordRequestEvent;

        $this->User = $User;

        $this->PasswordReset = $PasswordReset;
    }

    public function execute(array $data) : User
    {
        $user = $this->User::where('email', $data['email'])->first();

        if (!$user) {
            throw new CustomException('We can\'t find a user with that e-mail address.', 404);
        }

        $passwordReset = $this->PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        );

        // dispatch an event to send reset password request email notifcation
        if ($user && $passwordReset) {
            event(new $this->ResetPasswordRequestEvent($passwordReset));
        }

        return $user;
    }
}
