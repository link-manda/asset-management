<?php

namespace Database\Factories;

use App\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitOfMeasurementFactory extends Factory
{
    protected $model = UnitOfMeasurement::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'symbol' => $this->faker->unique()->lexify('???'),
        ];
    }
}
