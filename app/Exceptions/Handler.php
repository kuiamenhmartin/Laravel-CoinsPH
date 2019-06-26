<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return \QioskApp::httpResponse(
                \QioskApp::ERROR,
                'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found',
                [],
                404
            );
        }

        if ($exception instanceof CustomException) {
            return \QioskApp::httpResponse(
                \QioskApp::ERROR,
                $exception->getMessage(),
                [],
                $exception->getCode()
            );
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // dd($exception);
        // $guard = array_get($exception->guards(), 0);
        //
        // switch ($guard) {
        //     case 'admin':
        //         $redirect = route('admin.login');
        //         break;
        //     default:
        //         $redirect = route('login');
        //         break;
        // }
        //
        // return $request->expectsJson()
        //         ? response()->json(['message' => $exception->getMessage()], 401)
        //         : redirect()->guest($redirect);

        return \QioskApp::httpResponse(
            \QioskApp::UNAUTHENTICATED,
            $exception->getMessage(),
            [],
            401
        );
    }
}
