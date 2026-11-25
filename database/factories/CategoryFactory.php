<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'fiscal_group' => $this->faker->randomElement(['Group 1', 'Group 2', 'Group 3', 'Group 4']),
        ];
    }
}
