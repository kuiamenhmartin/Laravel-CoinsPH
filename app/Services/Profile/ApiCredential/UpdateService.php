<?php

namespace App\Services\Profile\ApiCredential;

use App\Exceptions\CustomException;
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
        $apiCredential = $user->apis()->find($data['id'])->where('is_active', 1);

        throw_if(
            !$apiCredential,
            CustomException::class,
            sprintf('Resource not found.'),
            402
        );

        //update the record
        $finalResult = $apiCredential->update($updatedValue);

        throw_if(
            !$finalResult,
            CustomException::class,
            sprintf('Something went wrong, data not saved.'),
            500
        );

        return true;
    }
}
