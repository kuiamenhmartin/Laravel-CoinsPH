<?php

namespace App\Services\User\Auth\ResetPassword;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Hash;

# Import needed models
use App\User;
use App\Models\PasswordReset;

use App\Services\ActionInterface;
use App\Events\UserResetPasswordSuccessEvent;

class ResetPasswordService implements ActionInterface
{
    protected $UserResetPasswordSuccessEvent;

    protected $User;

    protected $PasswordReset;

    public function __construct(UserResetPasswordSuccessEvent $UserResetPasswordSuccessEvent, User $User, PasswordReset $PasswordReset)
    {
        $this->UserResetPasswordSuccessEvent = $UserResetPasswordSuccessEvent;

        $this->User = $User;

        $this->PasswordReset = $PasswordReset;
    }

    public function execute(array $data) : PasswordReset
    {
        $passwordReset = $this->PasswordReset::where([
            ['token', $data['token']]
        ])->first();

        if (!$passwordReset) {
            throw new CustomException('This password reset token is invalid.', 404);
        }

        $user = $this->User::where('email', $passwordReset->email)->first();

        if (!$user) {
            throw new CustomException('We can\'t find a user with that e-mail address.', 404);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        $passwordReset->delete();
        event(new $this->UserResetPasswordSuccessEvent($user));

        return $passwordReset;
    }
}
