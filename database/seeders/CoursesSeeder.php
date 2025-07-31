<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Courses;
use App\Models\Instructor;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructors = Instructor::all();

        // Create courses with existing instructors
        foreach ($instructors as $instructor) {
            Courses::factory(1)->create([
                'instructor_id' => $instructor->id,
            ]);
        }

        // Create additional courses with different types
        Courses::factory(2)->computerScience()->create();
        Courses::factory(2)->mathematics()->create();
        Courses::factory(3)->create(); // Regular courses
    }
} 