<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courses>
 */
class CoursesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courseNames = [
            'Introduction to Computer Science',
            'Advanced Mathematics',
            'English Literature',
            'Physics Fundamentals',
            'World History',
            'Chemistry Lab',
            'Business Management',
            'Psychology 101',
            'Data Structures and Algorithms',
            'Calculus II',
            'Microeconomics',
            'Macroeconomics',
            'Organic Chemistry',
            'Digital Marketing',
            'Web Development',
            'Database Systems',
            'Artificial Intelligence',
            'Machine Learning',
            'Software Engineering',
            'Computer Networks',
        ];

        $courseCodes = [
            'CS101', 'MATH201', 'ENG101', 'PHYS101', 'HIST101',
            'CHEM101', 'BUS201', 'PSYCH101', 'CS201', 'MATH202',
            'ECON101', 'ECON201', 'CHEM201', 'MKTG101', 'CS102',
            'CS301', 'CS401', 'CS402', 'CS501', 'CS302',
        ];

        return [
            'course_name' => fake()->randomElement($courseNames),
            'course_code' => fake()->randomElement($courseCodes),
            'instructor_id' => \App\Models\Instructor::factory(),
        ];
    }

    /**
     * Create a computer science course
     */
    public function computerScience(): static
    {
        return $this->state(fn (array $attributes) => [
            'course_name' => fake()->randomElement([
                'Introduction to Computer Science',
                'Data Structures and Algorithms',
                'Web Development',
                'Database Systems',
                'Artificial Intelligence',
                'Machine Learning',
                'Software Engineering',
                'Computer Networks',
            ]),
            'course_code' => fake()->randomElement(['CS101', 'CS201', 'CS102', 'CS301', 'CS401', 'CS402', 'CS501', 'CS302']),
        ]);
    }

    /**
     * Create a mathematics course
     */
    public function mathematics(): static
    {
        return $this->state(fn (array $attributes) => [
            'course_name' => fake()->randomElement([
                'Advanced Mathematics',
                'Calculus II',
                'Linear Algebra',
                'Statistics',
                'Discrete Mathematics',
                'Differential Equations',
            ]),
            'course_code' => fake()->randomElement(['MATH201', 'MATH202', 'MATH301', 'MATH401', 'MATH501', 'MATH601']),
        ]);
    }
} 