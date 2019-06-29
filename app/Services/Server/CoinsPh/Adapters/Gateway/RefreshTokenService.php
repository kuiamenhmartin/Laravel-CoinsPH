<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Services\Server\CoinsPh\ApiCredentialAbstract;
use App\User;
use App\Models\UserExternalApiCredentialTokens;

use Illuminate\Support\Arr;
use GuzzleHttp\Client;

class RefreshTokenService extends ApiCredentialAbstract
{
    public function __construct(User $UserModel, UserExternalApiCredentialTokens $ApiTokenModel, Client $GuzzleClient)
    {
        //Pass User instance to our Parent Abstract Class
        parent::__construct($UserModel, $ApiTokenModel, $GuzzleClient);
    }

    /**
     * Get access_token using code provided by your api auth server
     *
     * @method execute
     * @param  array $data state and code data
     * @return string
     */
    public function execute(array $data): string
    {
        $yourApiConfig = $this->getApiCredentials($data['subid'], $data['app_name']);

        $refreshToken = $this->getRefreshToken($yourApiConfig->id);

        //Get access_token
        $tokens = $this->requestForAccessToken($yourApiConfig->authentication_uri, [
            'client_id' => $yourApiConfig->client_id,
            'client_secret' => $yourApiConfig->client_secret,
            'refresh_token' => $refreshToken->refresh_token,
            'grant_type' => 'refresh_token',
            'redirect_uri' => $yourApiConfig->redirect_uri
        ]);

        $tokens = Arr::add($tokens, 'api_id', $yourApiConfig->id);

        //Save refresh token to db
        $this->saveRefreshToken($tokens);

        //throw access_token
        return $tokens['access_token'];
    }
}
