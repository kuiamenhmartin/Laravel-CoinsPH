<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\CustomException;

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
        if ($request->wantsJson()) {

          $defaultException = array('MethodNotAllowedHttpException', 'HttpException');
          $className = class_basename($exception);

          if ($exception instanceof ModelNotFoundException) {
              return \QioskApp::httpResponse(
                  \QioskApp::ERROR,
                  'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found',
                  [],
                  404
              );
          } elseif ($exception instanceof CustomException) {
              return \QioskApp::httpResponse(
                  \QioskApp::ERROR,
                  $exception->getMessage(),
                  [],
                  $exception->getCode()
              );
          } elseif(in_array($className , $defaultException)) {
            return \QioskApp::httpResponse(
                \QioskApp::ERROR,
                $exception->getMessage(),
                [],
                $exception->getStatusCode()
            );
          }
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
            $exception->getMessage()."dsadsada",
            [],
            401
        );
    }
}
