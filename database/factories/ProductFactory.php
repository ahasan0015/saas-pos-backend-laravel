<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 🥤 বাস্তবসম্মত জুস এবং সুপারশপ প্রোডাক্টের নামের একটি চমৎকার লিস্ট
        $products = [
            'Fresh Orange Juice', 'Apple Blast Juice', 'Mango Smoothy', 
            'Strawberry Milkshake', 'Pineapple Fresh Juice', 'Avocado Green Shake',
            'Lemon Mint Cooler', 'Pomegranate Juice', 'Watermelon Chill Juice',
            'Full Cream Milk 1L', 'Organic Brown Eggs', 'Cheddar Cheese 200g',
            'Premium Basmati Rice', 'Crunchy Chocolate Cookies', 'Potato Chips Salted'
        ];

        return [
            'tenant_id'   => 1, // সিডারে ডাইনামিকালি ওভাররাইড হবে
            'category_id' => null, // সিডারে রিলেশন অনুযায়ী বসবে
            
            // 🌟 আমাদের লিস্ট থেকে প্রতিবার একটা করে র্যান্ডম জুস বা প্রোডাক্টের নাম নেবে
            'name'        => $this->faker->randomElement($products), 
            
            'sku'         => strtoupper($this->faker->unique()->bothify('PROD-####')),
            'cost_price'  => $this->faker->randomFloat(2, 50, 500),
            'sale_price'  => $this->faker->randomFloat(2, 60, 700),
            'status'      => true,
        ];
    }
}