<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Exceptions\CustomException;
use App\Services\Server\CoinsPh\ApiCredentialAbstract;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\User;

class ApiCredentialService extends ApiCredentialAbstract
{
    protected $User;

    public function __construct(User $User)
    {
        $this->User = $User;

        parent::__construct($User);
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
         * We need to get the configuration from laravel config
         * that we set from CheckCredentialMiddleware.php
         */
        $yourApiConfig = $this->getApiCredentials($data['user_id'], $data['app_name']);

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
        $serializedYourStateParams = base64_encode(serialize($yourStateParams));

        //merge state param and the config data to become one array
        return array_merge(
            ['state' => $serializedYourStateParams],
            Arr::except($yourApiConfig->toArray(), ['client_secret'])
        );
    }
}
