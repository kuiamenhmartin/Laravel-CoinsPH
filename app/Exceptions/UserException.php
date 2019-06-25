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

    }

    public function render($request)
    {
    }
}
