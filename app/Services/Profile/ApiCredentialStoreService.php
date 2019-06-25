<?php

namespace App\Services\Profile;

use App\Exceptions\UserException;
use Illuminate\Auth\AuthManager;

use Symfony\Component\HttpKernel\Profiler\Profile;

class ApiCredentialStoreService
{
    protected $usersExternalApiModel;

    protected $Auth;

    public function __construct(AuthManager $auth)
    {
        $this->Auth = $auth;
    }

    public function execute($data)
    {
        $result = $this->Auth->guard('api')->user()->externalApis()->create($data);

        if (!$result) {
            throw new UserException('Oops something went wrong!. Please try again later.', 200);
        }

        return true;
    }
}
// TODO: Make an exception for Profile
// TODO: Make sure the guard is not limited to api only it should be open for web guard
// TODO: Create delete service
// TODO: Make use of the resource
// TODO: Clean clean clean codes!!!
