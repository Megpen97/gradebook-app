<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollments>
 */
class EnrollmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => \App\Models\Student::factory(),
            'course_id' => \App\Models\Courses::factory(),
            'enrollment_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create a recent enrollment
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'enrollment_date' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Create an old enrollment
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'enrollment_date' => fake()->dateTimeBetween('-2 years', '-1 year'),
        ]);
    }
} 