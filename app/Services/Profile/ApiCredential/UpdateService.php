<?php

namespace App\Services\Profile\ApiCredential;

use App\Exceptions\UserException;
use Illuminate\Support\Arr;
use App\User;

class UpdateService
{
    /**
     * Save changes to api credentials
     * @method execute
     * @param  array   $data data that holds the changes
     * @return boolean
     */
    public function execute(array $data, User $user): bool
    {
        //id: is the primary key of api record
        $updatedValue = Arr::except($data, ['id']);

        //find the record in the db before update
        $apiCredential = $user->externalApis()->find($data['id'])->where('is_active', 1);

        if (!$apiCredential) {
            throw new UserException('No record found.', 200);
        }

        //update the record
        $finalResult = $apiCredential->update($updatedValue);

        if (!$finalResult) {
            throw new UserException('Something went wrong, data not saved.', 200);
        }

        return true;
    }
}
