<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
     public function report()
     {
       // dd($exception);
       //   parent::report($exception);
     }

      public function render($request)
      {
        // dd($exception);
      }
}
