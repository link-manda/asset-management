<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Category;
use App\Models\UnitOfMeasurement;
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
            'category_id' => Category::factory(),
            'uom_id' => UnitOfMeasurement::factory(),
            'price' => $this->faker->randomFloat(2, 500000, 15000000), // Unit Price
            'notes' => $this->faker->sentence(),
        ];
    }
}
