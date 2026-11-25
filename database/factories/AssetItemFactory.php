<?php

namespace Database\Factories;

use App\Models\AssetItem;
use App\Models\Asset;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetItemFactory extends Factory
{
    protected $model = AssetItem::class;

    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'item_code' => 'SN-' . strtoupper(bin2hex(random_bytes(4))),
            'serial_number' => 'SER-' . strtoupper(bin2hex(random_bytes(4))),
            'location_id' => Location::factory(),
            'condition' => $this->faker->randomElement(['Good', 'Fair', 'Poor']),
            'status' => 'Available',
            'purchase_date' => now()->subDays(rand(30, 365)),
            'purchase_price' => $this->faker->randomFloat(2, 500000, 15000000),
        ];
    }
}
