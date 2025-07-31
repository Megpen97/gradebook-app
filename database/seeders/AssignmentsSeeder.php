<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assignments;
use App\Models\Courses;

class AssignmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Courses::all();

        // Create assignments for each course
        foreach ($courses as $course) {
            // Create different types of assignments for each course
            Assignments::factory(1)->homework()->create([
                'course_id' => $course->id,
            ]);
            
            Assignments::factory(1)->exam()->create([
                'course_id' => $course->id,
            ]);
            
            Assignments::factory(1)->project()->create([
                'course_id' => $course->id,
            ]);
        }

        // Create additional random assignments
        Assignments::factory(5)->create();
    }
} 