<?php

namespace App\Services\User\Auth\Registration;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActionInterface;
use App\Exceptions\CustomException;
use Carbon\Carbon;
use App\Helpers\QioskApp;

class ConfirmEmailService implements ActionInterface
{
    protected $User;

    public function __construct(User $User)
    {
        $this->User = $User;
    }

    public function execute(array $token) : User
    {
        // Decode and Unserialize token to extract created and activation_token it contians
        $filteredData = array_filter(QioskApp::unserializeParams($token[0]));

        if (!$this->isEmailTokenValid($filteredData['email'])) {
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

    /**
     * Validate Email Token, make sure it has the data that we originally pass to the api
     *
     * @param string $emailToken will be converted to array to get created and activation_token
     * @return boolean
     */
    private function isEmailTokenValid(array $filteredData): bool
    {
        if (array_key_exists('token', $filteredData) && array_key_exists('created', $filteredData)) {
            return true;
        }

        if (Carbon::parse($filteredData['created']) >= Carbon::now()) {
            return true;
        }

        return false;
    }
}
