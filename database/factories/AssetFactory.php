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
            'purchase_date' => $this->faker->date(),
            'price' => $this->faker->randomFloat(2, 500000, 15000000), // Unit Price
            'status' => $this->faker->randomElement(['Available', 'Deployed', 'Maintenance', 'Broken']),
            'notes' => $this->faker->sentence(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Asset $asset) {
            // Create 1-3 random stock distributions for each asset
            $locations = \App\Models\Location::inRandomOrder()->take(rand(1, 3))->get();
            
            foreach ($locations as $location) {
                \App\Models\AssetStock::create([
                    'asset_id' => $asset->id,
                    'location_id' => $location->id,
                    'quantity' => rand(1, 20),
                    'status' => $asset->status,
                ]);
            }
        });
    }
}
