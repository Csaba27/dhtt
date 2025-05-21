<?php

namespace Database\Seeders\Dhtt;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Alap admin felhasználó
        if (! User::exists()) {
            $user = User::create([
                'name' => 'Admin',
                'slug' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('secret123'),
                'is_active' => 1,
                'is_office_login_only' => 0,
                'email_verified_at' => now()->toDateTimeString(),
            ]);

            $user->assignRole('admin');

            $name = get_initials($user->name);
            $id = $user->id.'.png';
            $path = 'users/';
            $imagePath = create_avatar($name, $id, $path);

            $user->image = $imagePath;
            $user->save();
        }
    }
}
