<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignments>
 */
class AssignmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignmentTypes = [
            'Homework', 'Quiz', 'Exam', 'Project', 'Lab Report', 
            'Essay', 'Presentation', 'Research Paper', 'Midterm', 'Final'
        ];

        return [
            'name' => fake()->randomElement($assignmentTypes) . ' ' . fake()->numberBetween(1, 10),
            'description' => fake()->paragraph(),
            'course_id' => \App\Models\Courses::factory(),
            'due_date' => fake()->dateTimeBetween('now', '+2 months'),
            'max_score' => fake()->randomElement([10, 25, 50, 100, 150, 200]),
        ];
    }

    /**
     * Create a homework assignment
     */
    public function homework(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Homework ' . fake()->numberBetween(1, 15),
            'max_score' => fake()->randomElement([10, 15, 20, 25]),
            'due_date' => fake()->dateTimeBetween('now', '+1 week'),
        ]);
    }

    /**
     * Create an exam assignment
     */
    public function exam(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement(['Midterm Exam', 'Final Exam', 'Quiz']) . ' ' . fake()->numberBetween(1, 5),
            'max_score' => fake()->randomElement([50, 75, 100, 150, 200]),
            'due_date' => fake()->dateTimeBetween('+2 weeks', '+3 months'),
        ]);
    }

    /**
     * Create a project assignment
     */
    public function project(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement(['Group Project', 'Individual Project', 'Research Project']) . ' ' . fake()->numberBetween(1, 5),
            'max_score' => fake()->randomElement([100, 150, 200, 250, 300]),
            'due_date' => fake()->dateTimeBetween('+1 month', '+4 months'),
        ]);
    }
} 