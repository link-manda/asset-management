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
        $categories = [
            [
                'name' => 'IT Equipment',
                'description' => 'Laptops, Monitors, Servers, and Networking devices',
                'fiscal_group' => 'Group 1', // 4 Years
            ],
            [
                'name' => 'Office Furniture',
                'description' => 'Chairs, Desks, and Storage units',
                'fiscal_group' => 'Group 2', // 8 Years
            ],
            [
                'name' => 'Vehicles',
                'description' => 'Operational cars and motorcycles',
                'fiscal_group' => 'Group 2', // 8 Years
            ],
            [
                'name' => 'Office Electronics',
                'description' => 'Printers, Scanners, and Projectors',
                'fiscal_group' => 'Group 1',
            ],
            [
                'name' => 'Tools & Equipment',
                'description' => 'Maintenance tools and field gear',
                'fiscal_group' => 'Group 1',
            ],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
