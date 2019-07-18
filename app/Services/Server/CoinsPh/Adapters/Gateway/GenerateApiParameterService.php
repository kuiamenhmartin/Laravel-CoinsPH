<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Exceptions\CustomException;
use App\Services\Server\CoinsPh\ApiCredentialService;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GenerateApiParameterService
{
    protected $ApiCredential;

    public function __construct(ApiCredentialService $ApiCredential)
    {
        $this->ApiCredential = $ApiCredential;
    }

    /**
     * Get API configuration of the App
     *
     * @method execute
     * @param  array $data array of data filters
     * @return array
     */
    public function execute(array $data): array
    {
        /**
         * We need to get the Api Credentials from DB
         * that we previously added
         */
        $yourApiConfig = $this->ApiCredential->execute($data['user_id'], $data['app_name']);

        throw_if(
            is_null($yourApiConfig),
            CustomException::class,
            sprintf('You haven\'t configured %s yet!', $data['app_name']),
            403
        );

        $yourStateParams = [
          'token' => $data['token'],
          'subid' => $yourApiConfig->user_id,
          'created' => date('Y-m-d H:i:s'),
          'app_name' => $data['app_name'],
        ];

        /**
         * we need to serialize the array as we will include them as state parameter when we call the api
         * to revert to original form, we need to unserialize and base64_decode to get the exact same array above
         * e.g dd(unserialize(base64_decode($serializedYourStateParams)))
         *
         * @var string
         */
        $serializedYourStateParams = \QioskApp::serializeParams($yourStateParams);

        //merge state param and the config data to become one array
        return Arr::add(
            Arr::except(
                $yourApiConfig->toArray(),
                ['user_id', 'id', 'app_name', 'is_active', 'deleted_at', 'created_at', 'updated_at', 'authentication_uri']
            ),
            'state',
            $serializedYourStateParams
        );
    }
}
