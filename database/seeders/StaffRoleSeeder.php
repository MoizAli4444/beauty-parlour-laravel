<?php

namespace Database\Seeders;

use App\Models\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaffRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Manager', 'Cashier', 'Cleaner', 'Support'];

        foreach ($roles as $role) {
            StaffRole::create([
                'name' => $role,
                'slug' => Str::slug($role),
                'description' => $role . ' role',
                'status' => 'active',
            ]);
        }
    }
}
