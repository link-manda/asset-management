<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city(),
            'address' => $this->faker->address(),
            'type' => $this->faker->randomElement(['Office', 'Warehouse', 'Store']),
        ];
    }
}
