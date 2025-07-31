<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip' => fake()->postcode(),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-30 years'),
            'hire_date' => fake()->dateTimeBetween('-10 years', '-1 year'),
        ];
    }

    /**
     * Create a senior instructor
     */
    public function senior(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-50 years'),
            'hire_date' => fake()->dateTimeBetween('-20 years', '-15 years'),
        ]);
    }

    /**
     * Create a junior instructor
     */
    public function junior(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_of_birth' => fake()->dateTimeBetween('-40 years', '-30 years'),
            'hire_date' => fake()->dateTimeBetween('-5 years', '-1 year'),
        ]);
    }
} 