<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
    $imageArr=['phone1.jpg','phone2.jpg','phone3.jpg'];
    return [
        'name' => $this->faker->word,
        'image' => 'images/' . Arr::random($imageArr),
        'price' => $this->faker->randomFloat(2, 10, 500),
        'quantity' => $this->faker->numberBetween(1, 100),
        'description' => $this->faker->paragraph,
    ];
}

}
