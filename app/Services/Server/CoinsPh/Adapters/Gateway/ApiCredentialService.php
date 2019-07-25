<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Exceptions\CustomException;
use App\Models\UserExternalApiCredentials;
use App\User;

class ApiCredentialService
{
    protected $UserModel;

    public function __construct(User $UserModel)
    {
        $this->UserModel = $UserModel;
    }

    /**
     * Get the Api Credential from DB
     * @param  int  $userId  [description]
     * @param  string $appName [description]
     * @return UserExternalApiCredentials
     */
    public function execute(int $userId, string $appName): UserExternalApiCredentials
    {
        $yourApiConfig = $this->UserModel::find($userId)->getApiCredential($appName)->first();

        throw_if(
            is_null($yourApiConfig),
            CustomException::class,
            sprintf('You haven\'t configured %s yet!', $appName),
            403
        );

        return $yourApiConfig;
    }
}
