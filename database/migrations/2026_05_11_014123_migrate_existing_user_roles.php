<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure core roles exist before migration
        $roles = ['Super Admin', 'Manager', 'Staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        User::query()->chunk(200, function ($users) {
            foreach ($users as $user) {
                $roleName = $user->role;
                
                if (empty($roleName)) {
                    continue;
                }

                // Map existing roles to Spatie roles
                $mappedRole = match(strtolower($roleName)) {
                    'admin' => 'Super Admin',
                    'staff' => 'Staff',
                    default => ucwords(str_replace('_', ' ', $roleName)),
                };

                if (Role::where('name', $mappedRole)->exists()) {
                    $user->assignRole($mappedRole);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse data migration without losing information.
    }
};
