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
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Bram Asset Manager',
            'email' => 'bram@asset.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Staff Accounts
        User::create([
            'name' => 'Andi Staff IT',
            'email' => 'andi@staff.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Budi Staff Ops',
            'email' => 'budi@staff.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
        
        $this->command->info('Default users created: admin@admin.com, bram@asset.com (Admin) and andi@staff.com, budi@staff.com (Staff). Pass: password');
    }
}
