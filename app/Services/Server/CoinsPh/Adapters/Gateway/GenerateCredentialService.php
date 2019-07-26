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
     * @param  array  data  [description]
     * @param  string $appName [description]
     * @return Array
     */
    public function execute(array $data): array
    {
        $yourApiConfig = $this->UserModel::find($data[0])->getApiCredential()->first();

        throw_if(
            is_null($yourApiConfig),
            CustomException::class,
            sprintf('You haven\'t configured %s yet!', 'your app'),
            403
        );

        return [
          'appId' => $yourApiConfig->id,
          'columns' => [
            'app_name' => $yourApiConfig->app_name,
            'client_id' => $yourApiConfig->client_id,
            'client_secret' => $yourApiConfig->client_secret,
            'redirect_uri' => $yourApiConfig->redirect_uri,
            'authentication_uri'=> $yourApiConfig->authentication_uri,
            'scopes' => $yourApiConfig->scopes,
            'isRefreshTokenExist' => $this->isRefreshTokenExist($yourApiConfig->id)
          ]
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
