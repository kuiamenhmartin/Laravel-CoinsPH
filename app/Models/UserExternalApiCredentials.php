<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserExternalApiCredentialTokens;

class UserExternalApiCredentials extends Model
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_name', 'client_id', 'client_secret', 'scopes', 'redirect_uri', 'authentication_uri', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'client_secret'
    ];

    /**
     * Table name used by this model
     * @var string
     */
    protected $table = 'user_external_api_credentials';

    /**
     * Set app_name to lowercase
     *
     * @param  string  $value
     * @return void
     */
    public function setAppNameAttribute($value)
    {
        $this->attributes['app_name'] = strtolower($value);
    }

    /**
     * Set scopes to lowercase
     *
     * @param  string  $value
     * @return void
     */
    public function setScopesAttribute($value)
    {
        $this->attributes['scopes'] = strtolower($value);
    }

    public function tokens()
    {
        return $this->hasOne(UserExternalApiCredentialTokens::class, 'api_id', 'id');
    }
}
