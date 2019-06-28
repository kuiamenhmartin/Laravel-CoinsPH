<?php

namespace App\Services\Profile\ApiCredential;

use App\Exceptions\CustomException;

use App\User;

use Illuminate\Support\Str;

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
