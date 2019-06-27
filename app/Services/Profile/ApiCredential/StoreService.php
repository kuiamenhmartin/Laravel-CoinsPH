<?php

namespace App\Services\Profile\ApiCredential;

use App\Exceptions\CustomException;

use App\User;

class StoreService
{
    /**
     * Save new api credentials
     * @method execute
     * @param  array   $data data that holds new api details
     * @return boolean
     */
    public function execute(array $data, User $user): void
    {
        //add new api credentials
        $result = $user->apis()->create($data);

        if (!$result) {
            throw new CustomException('Something went wrong, data not saved.', 500);
        }
    }
}
// TODO: Make sure the guard is not limited to api only it should be open for web guard
// TODO: Create delete service
// TODO: Make use of the resource
// TODO: Clean clean clean codes!!!
