<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Department;
use Illuminate\Database\Seeder;

class OrgStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'ROOMS' => [
                'ROOMS MANAGEMENT',
                'FRONT OFFICE',
                'RESERVATIONS',
                'HOUSEKEEPING',
            ],
            'FOOD & BEVERAGE' => [
                'F&B MANAGEMENT',
                'F&B STEWARDING',
                'F&B MAIN KITCHEN',
                'F&B PASTRY KITCHEN',
                'F&B KITCHEN ADMIN',
                'BANQUETING',
                'ROOM SERVICE',
                'MINIBAR/PRIVATE BAR',
                'SEGARAN DINING TERRACE',
                'AKASA GASTRO GRILL & WINE CELLAR',
                'MAJA SUNSET POOL LOUNGE',
                'SEGARAN DINING TERRACE KITCHEN',
                'AKASA GASTRO GRILL KITCHEN',
                'BANQUET KITCHEN',
            ],
            'OTHER OPERATING DEPTS' => [
                'TALISE WELLNESS & SPA',
                'RETAIL SHOP',
                'GUEST LAUNDRY & VALET',
                'PEAFOWL KIDS CLUB',
                'WELLNESS OPERATION',
            ],
            'ADMIN & GENERAL' => [
                'EXECUTIVE OFFICE',
                'ACCOUNTING & FINANCE',
                'INFORMATION TECHNOLOGY (A&G)',
                'SECURITY',
                'HUMAN RESOURCES',
                'LEARNING & DEVELOPMENT',
                'SALES',
                'MARKETING',
            ],
            'POM' => [
                'PROPERTY OPERATION & MAINT DEP',
            ],
        ];

        foreach ($data as $divisionName => $departments) {
            $division = Division::firstOrCreate(['name' => $divisionName]);

            foreach ($departments as $deptName) {
                Department::firstOrCreate([
                    'division_id' => $division->id,
                    'name' => $deptName,
                ]);
            }
        }
    }
}
