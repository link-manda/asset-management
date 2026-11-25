<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Accounts
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin1->assignRole('Super Admin');

        $admin2 = User::firstOrCreate(
            ['email' => 'bram@asset.com'],
            [
                'name' => 'Bram Asset Manager',
                'password' => Hash::make('password'),
            ]
        );
        $admin2->assignRole('Super Admin');

        // Staff Accounts
        $staff1 = User::firstOrCreate(
            ['email' => 'andi@staff.com'],
            [
                'name' => 'Andi Staff IT',
                'password' => Hash::make('password'),
            ]
        );
        $staff1->assignRole('Staff');

        $staff2 = User::firstOrCreate(
            ['email' => 'budi@staff.com'],
            [
                'name' => 'Budi Staff Ops',
                'password' => Hash::make('password'),
            ]
        );
        $staff2->assignRole('Staff');
        
        $this->command->info('Default users created: admin@admin.com, bram@asset.com (Admin) and andi@staff.com, budi@staff.com (Staff). Pass: password');
    }
}
