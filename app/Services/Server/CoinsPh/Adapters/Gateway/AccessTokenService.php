<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Services\Server\CoinsPh\ApiCredentialAbstract;
use App\Models\UserExternalApiCredentialTokens;
use App\Services\Server\CoinsPh\Adapters\Gateway\ApiCredentialService;
use App\User;

use Illuminate\Support\Arr;
use GuzzleHttp\Client;

class AccessTokenService extends ApiCredentialAbstract
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
        $yourApiConfig =  $this->ApiCredential->execute([$data['subid']]);

        //Get access_token via authorization_code
        $tokens = $this->requestForAccessToken($yourApiConfig->authentication_uri, [
            'client_id' => $yourApiConfig->client_id,
            'client_secret' => $yourApiConfig->client_secret,
            'code' => $data['code'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => $yourApiConfig->redirect_uri
        ]);

        $tokens = Arr::add($tokens, 'api_id', $yourApiConfig->id);

        //Save refresh token to db
        $this->saveRefreshToken($tokens);

        //throw new access_token
        return $tokens['access_token'];
    }
}
