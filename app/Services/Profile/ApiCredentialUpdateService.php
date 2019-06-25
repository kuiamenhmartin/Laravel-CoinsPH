<?php

namespace App\Services\Profile;

use App\Exceptions\UserException;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Arr;

class ApiCredentialUpdateService
{
    protected $Auth;

    public function __construct(AuthManager $auth)
    {
        $this->Auth = $auth;
    }

    public function execute(array $data)
    {
        $upadatedValue = Arr::except($data, ['id']);

        $result = $this->Auth->guard('api')->user()->externalApis()->find($data['id']);

        if (!$result) {
            throw new UserException('No record/s found.', 200);
        }

        $finalResult = $result->update($upadatedValue);

        if (!$finalResult) {
            throw new UserException('Something went wrong, data not saved.', 200);
        }

        return true;
    }
}
