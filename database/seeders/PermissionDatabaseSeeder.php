<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PermissionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        // User
        Permission::firstOrCreate(['name' => 'view_users', 'label' => 'View Users', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'view_users_profiles', 'label' => 'View Users Profiles', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'view_users_activity', 'label' => 'View Users Activity', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'add_users', 'label' => 'Add Users', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'edit_users', 'label' => 'Edit Users', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'edit_own_account', 'label' => 'Edit Own Account', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'delete_users', 'label' => 'Delete Users', 'module' => 'Users']);

        // Roles
        Permission::firstOrCreate(['name' => 'view_roles', 'label' => 'View Roles', 'module' => 'Roles']);
        Permission::firstOrCreate(['name' => 'add_roles', 'label' => 'Add Roles', 'module' => 'Roles']);
        Permission::firstOrCreate(['name' => 'edit_roles', 'label' => 'Edit Roles', 'module' => 'Roles']);
        Permission::firstOrCreate(['name' => 'delete_roles', 'label' => 'Delete Roles', 'module' => 'Roles']);

        // Settings
        Permission::firstOrCreate(['name' => 'view_audit_trails', 'label' => 'View Audit Trails', 'module' => 'Settings']);
        Permission::firstOrCreate(['name' => 'view_system_settings', 'label' => 'View System Settings', 'module' => 'Settings']);

        // App
        Permission::firstOrCreate(['name' => 'view_dashboard', 'label' => 'View Dashboard', 'module' => 'App']);
        Permission::firstOrCreate(['name' => 'view_notifications', 'label' => 'View Notifications', 'module' => 'App']);
    }
}
