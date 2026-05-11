<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetDisposal;
use App\Models\AssetItem;
use App\Models\AssetMaintenance;
use App\Models\Category;
use App\Models\Department;
use App\Models\Division;
use App\Models\Location;
use App\Models\UnitOfMeasurement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Organization Structure
        $divIT = Division::firstOrCreate(['name' => 'Information Technology']);
        $divOps = Division::firstOrCreate(['name' => 'Operations']);
        $divFin = Division::firstOrCreate(['name' => 'Finance & Accounting']);

        $deptInfra = Department::firstOrCreate(['division_id' => $divIT->id, 'name' => 'Infrastructure']);
        $deptSupport = Department::firstOrCreate(['division_id' => $divIT->id, 'name' => 'IT Support']);
        $deptGA = Department::firstOrCreate(['division_id' => $divOps->id, 'name' => 'General Affairs']);

        // 2. Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Super Admin Demo',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Super Admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@demo.com'],
            [
                'name' => 'Manager Demo',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
                'department_id' => $deptGA->id,
            ]
        );
        $manager->assignRole('Manager');

        $staffs = [];
        $staffNames = ['Budi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Dewi Lestari', 'Eko Prasetyo'];
        foreach ($staffNames as $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@demo.com';
            $staff = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'email_verified_at' => now(),
                    'department_id' => $deptSupport->id,
                ]
            );
            $staff->assignRole('Staff');
            $staffs[] = $staff;
        }

        // 3. Create Locations
        $locHQ = Location::firstOrCreate(['name' => 'Head Office - Jakarta'], ['address' => 'Jl. Jend. Sudirman No. 1', 'type' => 'Office']);
        $locWH = Location::firstOrCreate(['name' => 'Central Warehouse'], ['address' => 'Kawasan Industri Pulogadung', 'type' => 'Warehouse']);
        $locBranch = Location::firstOrCreate(['name' => 'Branch Office - Bali'], ['address' => 'Jl. Sunset Road No. 100', 'type' => 'Office']);

        // 4. Create Categories & UoM
        $uomUnit = UnitOfMeasurement::firstOrCreate(['name' => 'Unit'], ['symbol' => 'UNT']);
        $uomSet = UnitOfMeasurement::firstOrCreate(['name' => 'Set'], ['symbol' => 'SET']);

        $catIT = Category::firstOrCreate(['name' => 'IT Equipment'], [
            'default_useful_life_months' => 48,
            'fiscal_group' => 'Kelompok 1',
            'default_residual_percentage' => 10
        ]);
        $catFurniture = Category::firstOrCreate(['name' => 'Office Furniture'], [
            'default_useful_life_months' => 96,
            'fiscal_group' => 'Kelompok 2',
            'default_residual_percentage' => 5
        ]);
        $catVehicles = Category::firstOrCreate(['name' => 'Vehicles'], [
            'default_useful_life_months' => 96,
            'fiscal_group' => 'Kelompok 2',
            'default_residual_percentage' => 20
        ]);

        // 5. Create Assets (Master Catalog)
        $assets = [
            [
                'category_id' => $catIT->id,
                'uom_id' => $uomUnit->id,
                'asset_code' => 'LAP-MBP',
                'name' => 'MacBook Pro M3 14"',
                'brand' => 'Apple',
                'price' => 28000000,
            ],
            [
                'category_id' => $catIT->id,
                'uom_id' => $uomUnit->id,
                'asset_code' => 'MON-DELL',
                'name' => 'Dell UltraSharp 27"',
                'brand' => 'Dell',
                'price' => 8500000,
            ],
            [
                'category_id' => $catFurniture->id,
                'uom_id' => $uomUnit->id,
                'asset_code' => 'CHR-HM',
                'name' => 'Herman Miller Aeron',
                'brand' => 'Herman Miller',
                'price' => 22000000,
            ],
            [
                'category_id' => $catVehicles->id,
                'uom_id' => $uomUnit->id,
                'asset_code' => 'CAR-TOY',
                'name' => 'Toyota Avanza Veloz',
                'brand' => 'Toyota',
                'price' => 290000000,
            ],
        ];

        foreach ($assets as $assetData) {
            $asset = Asset::firstOrCreate(['asset_code' => $assetData['asset_code']], $assetData);

            // 6. Create Asset Items (Physical Units)
            $count = ($assetData['asset_code'] == 'CAR-TOY') ? 2 : 5;
            for ($i = 1; $i <= $count; $i++) {
                $itemCode = $asset->asset_code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                // Variasi tanggal perolehan (antara 2 tahun lalu s/d 1 bulan lalu)
                $purchaseDate = Carbon::now()->subMonths(rand(1, 24));
                
                $item = AssetItem::firstOrCreate(
                    ['item_code' => $itemCode],
                    [
                        'asset_id' => $asset->id,
                        'serial_number' => strtoupper(Str::random(10)),
                        'location_id' => ($i % 2 == 0) ? $locHQ->id : $locWH->id,
                        'condition' => 'Good',
                        'status' => 'Available',
                        'purchase_date' => $purchaseDate,
                        'purchase_price' => $asset->price,
                        'residual_value' => $asset->price * ($asset->category->default_residual_percentage / 100),
                        'useful_life_months' => $asset->category->default_useful_life_months,
                        'fiscal_group' => $asset->category->fiscal_group,
                    ]
                );

                // 7. Create Assignments (Peminjaman)
                if ($i <= 3 && $assetData['asset_code'] != 'CAR-TOY') {
                    $staff = $staffs[array_rand($staffs)];
                    $isReturned = ($i == 1); // Unit pertama anggap sudah dikembalikan
                    
                    AssetAssignment::firstOrCreate(
                        ['asset_item_id' => $item->id, 'assigned_to' => $staff->id, 'assigned_date' => $purchaseDate->copy()->addDays(5)->toDateString()],
                        [
                            'return_date' => $isReturned ? $purchaseDate->copy()->addMonths(3) : null,
                            'condition_on_checkout' => 'Good',
                            'condition_on_return' => $isReturned ? 'Good' : null,
                        ]
                    );

                    if (!$isReturned) {
                        $item->update(['status' => 'Deployed']);
                    }
                }

                // 8. Create Maintenance (Simulasi Servis)
                if ($i == 4) {
                    AssetMaintenance::firstOrCreate(
                        ['asset_item_id' => $item->id, 'maintenance_date' => Carbon::now()->subDays(15)->toDateString()],
                        [
                            'cost' => 1500000,
                            'description' => 'Upgrade RAM & Cleaning Service oleh Vendor IT Solution',
                            'status' => 'Completed',
                        ]
                    );
                    $item->update(['status' => 'Maintenance']);
                }

                // 9. Create Disposal (Aset Keluar/Dijual)
                if ($i == 5) {
                    AssetDisposal::firstOrCreate(
                        ['asset_item_id' => $item->id],
                        [
                            'disposal_date' => Carbon::now()->subDays(5),
                            'reason' => 'Sold',
                            'selling_price' => $asset->price * 0.4,
                            'notes' => 'Aset dijual karena upgrade hardware massal.',
                            'created_by' => $admin->id,
                        ]
                    );
                    $item->update(['status' => 'Disposed']);
                }
            }
        }

        $this->command->info('Demo System Seeder completed successfully!');
        $this->command->info('Admin Login: admin@demo.com / password');
    }
}
