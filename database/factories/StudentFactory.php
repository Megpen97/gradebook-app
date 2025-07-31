<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip' => fake()->postcode(),
            'date_of_birth' => fake()->dateTimeBetween('-25 years', '-18 years'),
            'enrollment_date' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * Create a student with specific age range
     */
    public function freshman(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_of_birth' => fake()->dateTimeBetween('-19 years', '-18 years'),
            'enrollment_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Create a student with specific age range
     */
    public function senior(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_of_birth' => fake()->dateTimeBetween('-22 years', '-21 years'),
            'enrollment_date' => fake()->dateTimeBetween('-4 years', '-3 years'),
        ]);
    }
} 