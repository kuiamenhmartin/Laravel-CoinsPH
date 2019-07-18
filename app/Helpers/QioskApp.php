<?php

namespace App\Helpers;

use Illuminate\Http\Response;

#Import Custom Exceptions
use App\Exceptions\UserException;

use Illuminate\Support\Arr;

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

    #Successful transaction
    const DANGER = 'DANGER';

    #Successful transaction
    const INFO = 'INFO';

    #User is not authenticated
    const UNAUTHENTICATED = 'UNAUTHENTICATED';

    /**
     * Create custom response for entire app
     * @method customResponse
     * @param  string $status error code from constant variable above
     * @param  array $data   payload
     * @param  int $code   repose code
     * @return Response http reponse type
     */
    public static function httpResponse(string $status, string $msg = '', array $pload = [], int $code = 200): Response
    {
        $responseData = [
            'status' => $status,
            'code' => $code,
            'message' => $msg,
            'payload' => $pload
        ];

        return response(array_filter($responseData), $code);
    }

    /**
     * Serialize data param to create a token like string which originally contains information
     * structured in an array
     * @method serializeParams
     * @param  array $data array of data
     * @return string
     */
    public static function serializeParams(array $data): string {
      return base64_encode(serialize($data));
    }

    /**
     * Decode and Unserialize $data to make it readable as array
     *
     * @param string $state will be converted to array to get the subid, created and appname
     * @return array
     */
    public static function unserializeParams(string $data): array
    {
         return unserialize(base64_decode($data));
    }
}
