<?php

namespace App\Helpers\Traits;

use Carbon\Carbon;

trait UserServiceTrait
{
    /**
     * Create and Return email activation token
     * for new user who register to this app
     * @return string email activation code
     */
    protected function createEmailActivationToken(): string
    {
        $emailActivationToken = array('email' => array(
            'created' => Carbon::now(),
            'activation_token' => str_random(20)
        ));
        return \QioskApp::serializeParams($emailActivationToken);
    }
}
