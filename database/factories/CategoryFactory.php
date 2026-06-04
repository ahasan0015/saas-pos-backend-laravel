<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => 1, // সিডারে এটি ডাইনামিকালি ওভাররাইড হবে
            'name'      => $this->faker->randomElement(['Electronics', 'Clothing', 'Grocery', 'Bakery', 'Stationery']),
        ];
    }
}
