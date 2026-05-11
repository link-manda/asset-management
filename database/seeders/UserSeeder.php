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
        $admin1 = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $admin1->assignRole('Super Admin');

        $admin2 = User::create([
            'name' => 'Bram Asset Manager',
            'email' => 'bram@asset.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $admin2->assignRole('Super Admin');

        // Staff Accounts
        $staff1 = User::create([
            'name' => 'Andi Staff IT',
            'email' => 'andi@staff.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
        $staff1->assignRole('Staff');

        $staff2 = User::create([
            'name' => 'Budi Staff Ops',
            'email' => 'budi@staff.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
        $staff2->assignRole('Staff');
        
        $this->command->info('Default users created: admin@admin.com, bram@asset.com (Admin) and andi@staff.com, budi@staff.com (Staff). Pass: password');
    }
}
