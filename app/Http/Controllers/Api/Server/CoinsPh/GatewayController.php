<?php

namespace App\Http\Controllers\Api\Server\CoinsPh;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Models\UserExternalApiCredentials;
use App\User;

#Import Service
use App\Services\Server\CoinsPh\Adapters\Gateway\FetchConfigService;

#Import App Helpers
use App\Helpers\QioskApp;

class GatewayController extends Controller
{
      /**
       * Create an new buyer
       *
       * For complete ref -> https://docs.coins.asia/docs/create-buyorder
       *
       * @param Request
       *
       * @return Response
       */
      public function getConfig(FetchConfigService $action)
      {
          $parameters = ['app_name' => request()->route('app_name'), 'user_id' => request()->user()->id];

          //Get configuration
          $result = $action->execute($parameters);

          //throw success when action executes succesfully
          return QioskApp::httpResponse(QioskApp::SUCCESS, 'Your config for app : '.$parameters['app_name'], $result);
      }

      public function callback()
      {
          $respo = request()->all();

          if(isset($respo['code'])) {
              return redirect('api/coinsph/token/'.$respo['code']);
          }

          return  redirect('api/reg/'.$respo['access_token']);
      }

}
