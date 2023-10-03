<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionplanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(50),
            'thumbnail'=> 'assets/static/img/subscription.png',
            'published' => fake()->randomElement([true,false]),
            'price'=>fake()->randomFloat(1, 20, 30),
            'validity'=> fake()->randomElement([7,30,90])
        ];
    }
}
