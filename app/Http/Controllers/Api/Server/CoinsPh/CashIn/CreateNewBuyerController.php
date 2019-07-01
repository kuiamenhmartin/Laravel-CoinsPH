<?php

namespace App\Http\Controllers\Api\Server\CoinsPh\CashIn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Models\UserExternalApiCredentials;
use App\User;

#Import Custom Requests

#Import Custom Services

#Import App Helpers
use App\Helpers\QioskApp;

class CreateNewBuyerController extends Controller
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
    public function __invoke(Request $request): Response
    {
      // dd('dsada');
      // $apis = UserExternalApiCredentials::all();
      // dd($apis);
        // $user = $action->execute($request->validated(), $request->user());
        //
        // //throw success when action executes succesfully
        // return QioskApp::httpResponse(QioskApp::SUCCESS, 'New Api has been added!');
    }
}
