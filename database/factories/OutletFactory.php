<?php

namespace Database\Factories;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Outlet>
 */
class OutletFactory extends Factory
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
            'name'      => $this->faker->city() . ' Branch',
            'location'  => $this->faker->address(),
            'phone'     => $this->faker->phoneNumber(),
        ];
    }
}
