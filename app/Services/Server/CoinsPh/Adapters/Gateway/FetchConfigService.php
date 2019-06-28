<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Exceptions\CustomException;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FetchConfigService
{
    public function __construct()
    {
        //get token
    }

    /**
     * Create a new buyorder
     * @method createNewBuyer
     * @param  string   $appName data that holds api id
     * @return array
     */
    public function execute(array $data): array
    {
      //We need to get the configuration we added to config from CheckCredentialMiddleware.php
      $yourAppConfig = config($data['app_name']);

      $yourStateParams = [
          'subid' => $data['user_id'],
          'created' => date('Y-m-d H:i:s'),
          'appname' => $data['app_name']
      ];

      /**
       * we need to serialize the array as we will include them as state parameter when we call the api
       * to revert to original form, we need to unserialize and base64_decode to get the exact same array above
       * e.g dd(unserialize(base64_decode($serializedYourStateParams)))
       * @var string
       */
      $serializedYourStateParams = base64_encode(serialize($yourStateParams));

      //merge state param and the config data to become one array
      return array_merge(
           ['state' => $serializedYourStateParams],
           Arr::except($yourAppConfig, ['client_secret'])
      );
    }
}
