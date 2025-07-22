<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            // Services
            'view services',
            'create services',
            'edit services',
            'delete services',

            // Service Variants
            'view variants',
            'create variants',
            'edit variants',
            'delete variants',

            // Appointments
            'view appointments',
            'create appointments',
            'edit appointments',
            'delete appointments',

            // Customers
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',

            // Staff
            'view staff',
            'create staff',
            'edit staff',
            'delete staff',

            // Roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Permissions
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // Reports / Settings
            'view reports',
            'manage settings',
            'manage profile',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $receptionist = Role::firstOrCreate(['name' => 'receptionist']);
        $beautician = Role::firstOrCreate(['name' => 'beautician']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Assign permissions to roles
        $admin->syncPermissions(Permission::all());

        // Receptionist - limited access
        $receptionist->syncPermissions([
            'view services',
            'view variants',
            'view appointments',
            'create appointments',
            'edit appointments',
            'view customers',
            'create customers',
            'edit customers',
        ]);

        // Beautician - minimal access
        $beautician->syncPermissions([
            'view appointments',
            'view services',
            'view variants',
            'manage profile',
        ]);


        $customer->syncPermissions([]);
    }
}
