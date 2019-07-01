<?php

namespace App\Services\Server\CoinsPh;

use App\Exceptions\CustomException;

use App\Models\UserExternalApiCredentials;
use App\Models\UserExternalApiCredentialTokens;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\User;

abstract class ApiCredentialAbstract
{
    protected $UserModel;

    protected $ApiTokenModel;

    protected $GuzzleClient;

    public function __construct(User $UserModel, UserExternalApiCredentialTokens $ApiTokenModel, Client $GuzzleClient)
    {
        //App\User
        $this->UserModel = $UserModel;
        //App\Models\UserExternalApiCredentialTokens
        $this->ApiTokenModel = $ApiTokenModel;
        //GuzzleHttp\Client
        $this->GuzzleClient = $GuzzleClient;
    }

    /**
     * Get the Api Credential from DB
     * @param  int  $userId  [description]
     * @param  string $appName [description]
     * @return UserExternalApiCredentials
     */
    protected function getApiCredentials(int $userId, string $appName): UserExternalApiCredentials
    {
        $yourApiConfig = $this->UserModel::find($userId)->getAppCredential($appName)->first();

        throw_if(
            is_null($yourApiConfig),
            CustomException::class,
            sprintf('You haven\'t configured %s yet!', $appName),
            403
        );

        return $yourApiConfig;
    }

    /**
     * Request Authorization Server for access_token using the Code
     *
     * @param string $code response code
     * @param string $appName application name
     *
     * @return array
     */
    protected function requestForAccessToken(string $url, array $query): array
    {
        try {
            $result = $this->GuzzleClient->post($url, ['query' => $query,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json'
                ]
            ]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();

            throw_if(
                !is_null($error),
                CustomException::class,
                $error,
                401
            );
        }

        $tokens = json_decode($result->getBody(), true);

        //Validate response
        $this->validateResponse($tokens);

        return $tokens;
    }

    /**
     * Get api refresh token
     * @param  int    $userId
     * @param  string $appName
     * @return UserExternalApiCredentialTokens
     */
    protected function getRefreshToken(int $apiId)
    {
        $refreshToken = $this->ApiTokenModel::find($apiId);

        throw_if(
            is_null($refreshToken),
            CustomException::class,
            'No refresh token found. Please login again in your app to generate new access token.',
            500
        );

        return $refreshToken->first();
    }

    /**
     * Save Refresh Token to DB for future use
     * @param  array  $token includes refresh_token
     * @return void
     */
    protected function saveRefreshToken(array $token): void
    {
        throw_if(
            !$this->ApiTokenModel::updateOrCreate(
                ['api_id' => $token['api_id']],
                ['refresh_token' => $token['refresh_token']]
            ),
            CustomException::class,
            'Something went worng!',
            500
        );
    }

    /**
     * Validate Authorization Server Response
     * Make sure the authorization server returns the ff:
     * - Refresh Token
     * - Access Token
     * - Token Type
     * - Scope
     * @param array $tokens array of tokens
     * @return void
     */
    private function validateResponse(array $tokens): void
    {
        $filteredTokens = array_filter($tokens);

        throw_if(
            (!array_key_exists('access_token', $filteredTokens) || !array_key_exists('token_type', $filteredTokens)
            || !array_key_exists('refresh_token', $filteredTokens)
            || !array_key_exists('scope', $filteredTokens)),
            CustomException::class,
            'Api token is invalid',
            500
        );
    }
}
