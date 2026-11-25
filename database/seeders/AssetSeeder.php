<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $uomUnit = \App\Models\UnitOfMeasurement::where('symbol', 'Unit')->first();
        $categories = \App\Models\Category::all();
        $locations = \App\Models\Location::all();
        
        $brands = [
            'IT Equipment' => ['Apple', 'Dell', 'HP', 'Lenovo', 'Asus', 'Logitech'],
            'Office Furniture' => ['Herman Miller', 'IKEA', 'Informa', 'Custom Wood', 'Steelcase'],
            'Vehicles' => ['Toyota', 'Honda', 'Mitsubishi', 'Suzuki', 'Yamaha'],
            'Office Electronics' => ['Epson', 'Canon', 'Sony', 'Samsung', 'LG'],
            'Tools & Equipment' => ['Makita', 'Bosch', 'Krisbow', 'Stanley'],
        ];

        $assetTypes = [
            'IT Equipment' => ['Laptop', 'Desktop', 'Monitor', 'Tablet', 'Smartphone', 'Server'],
            'Office Furniture' => ['Chair', 'Desk', 'Cabinet', 'Sofa', 'Meeting Table'],
            'Vehicles' => ['Sedan', 'SUV', 'Van', 'Motorcycle'],
            'Office Electronics' => ['Printer', 'Projector', 'Scanner', 'Camera', 'Television'],
            'Tools & Equipment' => ['Drill', 'Saw', 'Multimeter', 'Toolbox'],
        ];

        $usedItemCodes = [];

        for ($i = 1; $i <= 110; $i++) {
            $cat = $categories->random();
            $brandList = $brands[$cat->name] ?? ['Generic'];
            $typeList = $assetTypes[$cat->name] ?? ['General Asset'];
            
            $brand = $brandList[array_rand($brandList)];
            $type = $typeList[array_rand($typeList)];
            $name = "$brand $type " . ($i + 100);
            
            // 8-char alphanumeric asset code, uppercase
            $assetCode = strtoupper(Str::random(8));

            $asset = \App\Models\Asset::updateOrCreate(
                ['asset_code' => $assetCode],
                [
                    'name' => $name,
                    'category_id' => $cat->id,
                    'uom_id' => $uomUnit->id,
                    'price' => rand(500000, 50000000),
                    'brand' => $brand,
                    'notes' => 'Bulk seeded asset ' . $i,
                ]
            );

            // Seed 10-15 physical items per asset
            $itemCount = rand(10, 15);
            for ($j = 1; $j <= $itemCount; $j++) {
                $purchaseDate = now()->subDays(rand(10, 1000));
                
                // 8-digit numeric item code, unique
                do {
                    $itemCode = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                } while (in_array($itemCode, $usedItemCodes));
                $usedItemCodes[] = $itemCode;
                
                \App\Models\AssetItem::create([
                    'asset_id' => $asset->id,
                    'item_code' => $itemCode,
                    'serial_number' => strtoupper(bin2hex(random_bytes(5))),
                    'location_id' => $locations->random()->id,
                    'status' => rand(0, 10) > 8 ? 'Deployed' : 'Available',
                    'condition' => rand(0, 10) > 8 ? 'Fair' : 'Good',
                    'purchase_date' => $purchaseDate,
                    'purchase_price' => $asset->price,
                    'useful_life_months' => ($asset->category->fiscal_group == 'Group 1' ? 48 : 96),
                ]);
            }
        }
    }
}
