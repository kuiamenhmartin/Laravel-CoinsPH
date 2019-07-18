<?php

namespace App\Services\User;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Exceptions\CustomException;
use App\Helpers\Traits\UserServiceTrait;
use Carbon\Carbon;

class ConfirmEmailService implements ActionInterface
{
    use UserServiceTrait;

    protected $User;

    public function __construct(User $User)
    {
        $this->User = $User;
    }

    public function execute(array $token) : User
    {
        // Decode and Unserialize token to extract created and activation_token it contians
        $filteredData = array_filter(\QioskApp::unserializeParams($token[0]));

        if (!$this->checkIfEmailTokenIsValid($filteredData['email'])) {
            throw new CustomException('This activation token is invalid.', 404);
        }

        $user = $this->User::where(['activation_token' => $token[0], 'email_verified_at' => null])->first();

        if (!$user) {
            throw new CustomException('This activation token is has expired.', 404);
        }

        $user->email_verified_at = Carbon::now();
        $user->activation_token = '';
        $user->save();

        return $user;
    }
}
