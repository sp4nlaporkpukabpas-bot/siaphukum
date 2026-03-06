<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'komisioner', 'display_name' => 'Komisioner'],
            ['name' => 'sekretaris', 'display_name' => 'Sekretaris'],
            ['name' => 'kasubag_hukum', 'display_name' => 'Kepala Sub Bagian Hukum'],
            ['name' => 'kasubag_parmas', 'display_name' => 'Kepala Sub Bagian Parmas SDM'],
            ['name' => 'kasubag_rendatin', 'display_name' => 'Kepala Sub Bagian Rendatin'],
            ['name' => 'kasubag_kul', 'display_name' => 'Kepala Sub Bagian KUL'],
            ['name' => 'staf', 'display_name' => 'Staf'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}