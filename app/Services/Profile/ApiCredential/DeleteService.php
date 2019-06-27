<?php

namespace App\Services\Profile\ApiCredential;

use App\Exceptions\CustomException;

use App\User;

class DeleteService
{
    public function __construct()
    {
        // $this->User = $User;
    }

    /**
     * Delete api credential
     * @method execute
     * @param  array   $data data that holds api id
     * @return void
     */
    public function execute(array $data, User $User): void
    {
        //find api credentials
        $apiCredential = $User->apis()->find($data["id"]);

        //deactivate first then,
        $apiCredential->update(['is_active' => 0]);

        //delete softly
        $result = $apiCredential->delete();

        if (!$result) {
            throw new CustomException('Something went wrong, data not saved.', 500);
        }
    }
}
