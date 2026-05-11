<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = ['assets', 'categories', 'locations', 'users', 'departments', 'divisions'];
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Create roles and assign permissions
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->syncPermissions([
            'view assets',
            'view categories',
            'view locations',
            'view departments',
            'view divisions',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        // Manager gets everything EXCEPT user management and sensitive delete actions
        $managerPermissions = Permission::all()->filter(function ($permission) {
            return !str_contains($permission->name, 'users') && 
                   !in_array($permission->name, ['delete categories', 'delete locations']);
        });
        $managerRole->syncPermissions($managerPermissions);

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super Admin gets everything
        $superAdminRole->syncPermissions(Permission::all());
    }
}
