<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\User;
use App\Models\UserExternalApiCredentialTokens;
use App\Services\Server\CoinsPh\ApiCredentialAbstract;
use App\Services\Server\CoinsPh\Adapters\Gateway\ApiCredentialService;

use Illuminate\Support\Arr;
use GuzzleHttp\Client;

class RefreshTokenService extends ApiCredentialAbstract
{
    protected $ApiCredential;

    public function __construct(
        User $UserModel,
        UserExternalApiCredentialTokens $ApiTokenModel,
        Client $GuzzleClient,
        ApiCredentialService $ApiCredential
    ) {

        $this->ApiCredential = $ApiCredential;

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
        $yourApiConfig = $this->ApiCredential->execute([$data[0]]);

        $refreshToken = $this->getRefreshToken($yourApiConfig->id);

        //Get access_token via refresh_token
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

        //throw new access_token
        return $tokens['access_token'];
    }
}
