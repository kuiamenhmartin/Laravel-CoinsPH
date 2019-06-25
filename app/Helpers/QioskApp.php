<?php

namespace App\Helpers;

use Illuminate\Http\Response;

#Import Custom Exceptions
use App\Exceptions\UserException;

/**
* GLOBAL CONSTANTS of the APP
*/
class QioskApp
{
    #Determine if the error is a VALIDATION error
    const FORM_ERROR = 'FORM_ERROR';

    #Determine if the error is a REGULAR error
    const ERROR = 'ERROR';

    #Successful transaction
    const SUCCESS = 'SUCCESS';

    #User is not authenticated
    const UNAUTHENTICATED = 'UNAUTHENTICATED';


    /**
     * To avoid redundantly use try catch we will just use closure function
     * @method checkAction
     * @param  closure $action Closure function
     * @return User|HttpResponse
     */
    public static function checkAction($action)
    {
        try {
            return $action();
        } catch (UserException $exception) {

            return QioskApp::httpResponse(
                QioskApp::ERROR,
                ['message' => $exception->getMessage()],
                $exception->getCode()
            );
        }
    }

    /**
     * Create custom response for entire app
     * @method customResponse
     * @param  string $status error code from constant variable above
     * @param  array $data   payload
     * @param  int $code   repose code
     * @return Response http reponse type
     */
    public static function httpResponse(string $status, array $data, int $code): Response
    {
        return response([
            'status' => $status,
            'payload' => $data
          ], $code ?? 400);
    }
}
