<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grades>
 */
class GradesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $score = fake()->numberBetween(60, 100);
        
        return [
            'enrollment_id' => \App\Models\Enrollments::factory(),
            'assignment_id' => \App\Models\Assignments::factory(),
            'score' => $score,
            'letter_grade' => $this->getLetterGrade($score),
            'comments' => fake()->optional(0.7)->sentence(),
            'graded_on' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Create an excellent grade
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->numberBetween(90, 100),
            'letter_grade' => fake()->randomElement(['A', 'A+']),
        ]);
    }

    /**
     * Create a good grade
     */
    public function good(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->numberBetween(80, 89),
            'letter_grade' => fake()->randomElement(['B', 'B+']),
        ]);
    }

    /**
     * Create an average grade
     */
    public function average(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->numberBetween(70, 79),
            'letter_grade' => fake()->randomElement(['C', 'C+']),
        ]);
    }

    /**
     * Create a poor grade
     */
    public function poor(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->numberBetween(60, 69),
            'letter_grade' => fake()->randomElement(['D', 'D+']),
        ]);
    }

    /**
     * Convert score to letter grade
     */
    private function getLetterGrade(int $score): string
    {
        if ($score >= 97) return 'A+';
        if ($score >= 93) return 'A';
        if ($score >= 90) return 'A-';
        if ($score >= 87) return 'B+';
        if ($score >= 83) return 'B';
        if ($score >= 80) return 'B-';
        if ($score >= 77) return 'C+';
        if ($score >= 73) return 'C';
        if ($score >= 70) return 'C-';
        if ($score >= 67) return 'D+';
        if ($score >= 63) return 'D';
        if ($score >= 60) return 'D-';
        return 'F';
    }
} 