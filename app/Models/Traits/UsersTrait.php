<?php
namespace App\Models\Traits;

use App\Models\UserExternalApiCredentials;

trait UsersTrait
{
    public function externalApis()
    {
        return $this->hasMany(UserExternalApiCredentials::class);
    }
}
