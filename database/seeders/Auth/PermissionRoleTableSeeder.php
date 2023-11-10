<?php

namespace Database\Seeders\Auth;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Create Roles
        $super_admin = Role::firstOrCreate(['name' => 'super admin']);
        $admin = Role::firstOrCreate(['name' => 'administrator']);
        $executive = Role::firstOrCreate(['name' => 'kepala']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Create Permissions
        Permission::firstOrCreate(['name' => 'view_backend']);
        Permission::firstOrCreate(['name' => 'edit_settings']);
        Permission::firstOrCreate(['name' => 'view_logs']);

        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        \Artisan::call('auth:permission', [
            'name' => 'posts',
        ]);
        echo "\n _Posts_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'categories',
        ]);
        echo "\n _Categories_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'tags',
        ]);
        echo "\n _Tags_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'comments',
        ]);
        echo "\n _Comments_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'students',
        ]);
        echo "\n _Students_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'reports',
        ]);
        echo "\n _Reports_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'types',
        ]);
        echo "\n _Types_ Permissions Created.";


        echo "\n\n";

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
        $executive->givePermissionTo('view_backend');
        
        // $user->givePermissionTo(['view_backend','view_reports','show_reports','create_reports']);

        Schema::enableForeignKeyConstraints();
    }
}
