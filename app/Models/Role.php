<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Permission;
use App\User;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
