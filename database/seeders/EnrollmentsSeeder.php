<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollments;
use App\Models\Student;
use App\Models\Courses;

class EnrollmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Courses::all();

        // Create enrollments for each student in multiple courses
        foreach ($students as $student) {
            // Each student enrolls in 2-3 random courses
            $randomCourses = $courses->random(fake()->numberBetween(2, 3));
            
            foreach ($randomCourses as $course) {
                Enrollments::factory()->create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                ]);
            }
        }

        // Create additional enrollments with different characteristics
        Enrollments::factory(5)->recent()->create();
        Enrollments::factory(3)->old()->create();
    }
} 