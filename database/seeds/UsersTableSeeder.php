<?php

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dev_role = Role::where('slug', 'developer')->first();
        $manager_role = Role::where('slug', 'manager')->first();
        $dev_perm = Permission::where('slug', 'create-tasks')->first();
        $manager_perm = Permission::where('slug', 'edit-users')->first();

        $developer = new User();
        $developer->name = 'Quiamenh';
        $developer->email = 'qsarza@wylog.com';
        $developer->password = bcrypt('12345');
        $developer->email_verified_at = date('Y-m-d H:i:s');
        $developer->save();
        $developer->roles()->attach($dev_role);
    }
}
