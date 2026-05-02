<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_code' => 'AST-' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->randomElement(['Laptop', 'Monitor', 'Printer', 'Meja', 'Kursi', 'Kamera']) . ' ' . $this->faker->lastName(),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id,
            'uom_id' => \App\Models\UnitOfMeasurement::inRandomOrder()->first()?->id,
            'price' => $this->faker->randomFloat(2, 500000, 15000000), // Unit Price
            'notes' => $this->faker->sentence(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Asset $asset) {
            // Create 1-10 physical items for each asset
            $count = rand(1, 10);
            $locations = \App\Models\Location::all();
            
            for ($i = 1; $i <= $count; $i++) {
                \App\Models\AssetItem::create([
                    'asset_id'      => $asset->id,
                    'item_code'     => 'SN-' . strtoupper(bin2hex(random_bytes(4))),
                    'serial_number' => 'SER-' . strtoupper(bin2hex(random_bytes(4))),
                    'location_id'   => $locations->random()->id,
                    'condition'     => $this->faker->randomElement(['Good', 'Fair', 'Poor']),
                    'status'        => 'Available',
                    'purchase_date' => now()->subDays(rand(30, 365)),
                    'purchase_price' => $asset->price,
                ]);
            }
        });
    }
}
