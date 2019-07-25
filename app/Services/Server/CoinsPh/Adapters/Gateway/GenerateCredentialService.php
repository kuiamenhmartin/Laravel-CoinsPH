<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Exceptions\CustomException;
use App\Models\UserExternalApiCredentials;
use App\Models\UserExternalApiCredentialTokens;
use App\User;

class GenerateCredentialService
{
    protected $UserModel;

    protected $ApiTokenModel;

    public function __construct(User $UserModel, UserExternalApiCredentialTokens $ApiTokenModel)
    {
        $this->UserModel = $UserModel;
        //App\Models\UserExternalApiCredentialTokens
        $this->ApiTokenModel = $ApiTokenModel;
    }

    /**
     * Get the Api Credential from DB
     * @param  int  $userId  [description]
     * @param  string $appName [description]
     * @return Array
     */
    public function execute(int $userId, string $appName): array
    {
        $yourApiConfig = $this->UserModel::find($userId)->getApiCredential($appName)->first();

        throw_if(
            is_null($yourApiConfig),
            CustomException::class,
            sprintf('You haven\'t configured %s yet!', $appName),
            403
        );

        return [
          'appName' => $yourApiConfig->app_name,
          'clientId' => $yourApiConfig->client_id,
          'clientSecret' => $yourApiConfig->client_secret,
          'redirectUri' => $yourApiConfig->redirect_uri,
          'scopes' => $yourApiConfig->scopes,
          'isRefreshTokenExist' => $this->isRefreshTokenExist($yourApiConfig->id)
        ];
    }

    private function isRefreshTokenExist($configId)
    {
        try {
            $refreshToken = $this->ApiTokenModel::find($configId);
            return ($refreshToken) ? true : false;
        } catch(CustomException $e) {
            return false;
        }
    }
}
