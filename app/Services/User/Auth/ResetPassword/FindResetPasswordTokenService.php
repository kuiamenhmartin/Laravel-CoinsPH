<?php

namespace App\Services\User\Auth\ResetPassword;

use App\Exceptions\CustomException;

use Carbon\Carbon;
# Import needed models
use App\Models\PasswordReset;

use App\Services\ActionInterface;

class FindResetPasswordTokenService implements ActionInterface
{

    protected $PasswordReset;

    public function __construct(PasswordReset $PasswordReset)
    {
        $this->PasswordReset = $PasswordReset;
    }

    public function execute(array $data) : PasswordReset
    {
        $passwordReset = $this->PasswordReset::where('token', $data[0])->first();

        if (!$passwordReset) {
            throw new CustomException('This password reset token is invalid.', 404);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            throw new CustomException('This password reset token is invalid.', 404);
        }

        return $passwordReset;
    }
}
