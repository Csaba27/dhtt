<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::create([
            'name' => 'admin',
            'label' => 'Admin',
        ]);

        Role::create([
            'name' => 'user',
            'label' => 'User',
        ]);
    }
}
