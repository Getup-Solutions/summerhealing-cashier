<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'=>fake()->firstName(),
            'last_name'=>fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number'=>fake()->phoneNumber(),
            'message'=>fake()->sentence(),
            'source'=>fake()->randomElement(['contact_page','campaign_landing_page']),
        ];
    }
}
