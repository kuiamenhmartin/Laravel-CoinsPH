<?php
namespace App\Models\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissionsTrait
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole(... $roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermissionThroughRole($permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission);
    }
}
