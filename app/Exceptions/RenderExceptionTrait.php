<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\QioskApp;

trait RenderExceptionTrait
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson()) {
            $response = [
                'status' => QioskApp::ERROR,
                'type' => class_basename($e),
                'code' =>  $e->getCode() ?? $e->getStatusCode() ,
                'message' => (string)$e->getMessage()
            ];

            // if ($this->isDebugMode()) {
            //     $response['debug'] = [
            //         'exception' => get_class($e),
            //         'trace' => $e->getTrace()
            //     ];
            // }
            return response($response, $response['code']);
        }

        return parent::render($request, $e);
    }
}
