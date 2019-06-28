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
     * @method getAppCredential
     * @param  string $appName the name of your application/api
     * @return collection
     */
    public function getAppCredential(string $appName='')
    {
      return $this->apis()->where(['app_name' => $appName, 'is_active' => 1]);
    }
}
