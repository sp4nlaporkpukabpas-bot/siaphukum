<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User
        $user = User::create([
            'name' => 'Operator Hukum',
            'username' => 'opt_hukum',
            'nip' => null,
            'password' => Hash::make('password'),
        ]);

        // 2. Ambil Role Admin
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            // 3. Masukkan ke tabel pivot (role_user)
            $user->roles()->attach($adminRole->id);

            // 4. Set sebagai role aktif
            $user->update([
                'active_role_id' => $adminRole->id
            ]);
        }
    }
}