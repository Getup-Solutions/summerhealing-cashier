<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'thumbnail'=> 'assets/static/img/subscription.png',
            'excerpt' => fake()->paragraph(),
            'published' => fake()->randomElement([true,false]),
            'description' => fake()->paragraph(),
            'price'=>fake()->randomFloat(1, 20, 30)
        ];
    }
}
