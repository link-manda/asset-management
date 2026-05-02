<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::create(['name' => 'IT Equipment', 'description' => 'Laptop, PC, Server, dan perangkat IT lainnya']);
        \App\Models\Category::create(['name' => 'Furniture', 'description' => 'Meja, kursi, lemari, dan perlengkapan kantor']);
        \App\Models\Category::create(['name' => 'Kendaraan', 'description' => 'Mobil, motor, dan armada operasional']);
    }
}
