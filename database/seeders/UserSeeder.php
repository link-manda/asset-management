<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configuredPassword = env('SEED_DEFAULT_PASSWORD');
        $isProduction = app()->environment('production');
        $defaultPassword = $configuredPassword ?: ($isProduction ? Str::password(16) : 'password');
        $hashedPassword = Hash::make($defaultPassword);

        // Admin Accounts
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => $hashedPassword,
            ]
        );
        $admin1->assignRole('Super Admin');

        $admin2 = User::firstOrCreate(
            ['email' => 'bram@asset.com'],
            [
                'name' => 'Bram Asset Manager',
                'password' => $hashedPassword,
            ]
        );
        $admin2->assignRole('Super Admin');

        // Staff Accounts
        $staff1 = User::firstOrCreate(
            ['email' => 'andi@staff.com'],
            [
                'name' => 'Andi Staff IT',
                'password' => $hashedPassword,
            ]
        );
        $staff1->assignRole('Staff');

        $staff2 = User::firstOrCreate(
            ['email' => 'budi@staff.com'],
            [
                'name' => 'Budi Staff Ops',
                'password' => $hashedPassword,
            ]
        );
        $staff2->assignRole('Staff');

        if ($isProduction && ! $configuredPassword) {
            $this->command->warn('Generated one-time seed password: '.$defaultPassword);
            $this->command->warn('Store this password securely and change it after the first login.');
        } else {
            $this->command->info('Default users created: admin@admin.com, bram@asset.com (Admin) and andi@staff.com, budi@staff.com (Staff). Pass: '.$defaultPassword);
        }
    }
}
