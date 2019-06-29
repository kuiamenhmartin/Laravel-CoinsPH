<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExternalApiCredentialTokens extends Model
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'api_id', 'refresh_token'
    ];

    protected $primaryKey = 'api_id';

    /**
     * Table name used by this model
     * @var string
     */
    protected $table = 'user_external_api_credential_tokens';
}
