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

    /**
     * Validate Email Token, make sure it has the data that we originally pass to the api
     *
     * @param string $emailToken will be converted to array to get created and activation_token
     * @return boolean
     */
    private function checkIfEmailTokenIsValid(array $filteredData): bool
    {
        if (array_key_exists('activation_token', $filteredData) && array_key_exists('created', $filteredData)) {
            return true;
        }

        if (Carbon::parse($filteredData['created']) >= Carbon::now()) {
            return true;
        }

        return false;
    }
}
