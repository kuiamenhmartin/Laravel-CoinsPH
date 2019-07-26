<?php
namespace App\Models\Traits;

use App\Models\UserExternalApiCredentials;

trait UsersTrait
{
    public function apis()
    {
        return $this->hasMany(UserExternalApiCredentials::class);
    }

    /**
     * Get Api Credential based from the value of $appName
     * @method getApiCredential
     * @param  string $appName the name of your application/api
     * @return collection
     */
    public function getApiCredential()
    {
        return $this->apis()->where(['is_active' => 1]);
    }
}
