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
     * @param  array  $data  [description]
     * @param  string $appName [description]
     * @return UserExternalApiCredentials
     */
    public function execute(array $data): UserExternalApiCredentials
    {
        $yourApiConfig = $this->UserModel::find($data[0])->getApiCredential()->first();

        throw_if(
            is_null($yourApiConfig),
            CustomException::class,
            sprintf('You haven\'t configured %s yet!', 'your app'),
            403
        );

        return $yourApiConfig;
    }
}
