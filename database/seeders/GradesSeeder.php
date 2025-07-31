<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grades;
use App\Models\Enrollments;
use App\Models\Assignments;

class GradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = Enrollments::all();
        $assignments = Assignments::all();

        // Create grades for each enrollment
        foreach ($enrollments as $enrollment) {
            // Get assignments for the course this student is enrolled in
            $courseAssignments = $assignments->where('course_id', $enrollment->course_id);
            
            // Grade the student on 50% of their course assignments
            $assignmentsToGrade = $courseAssignments->random(
                min($courseAssignments->count(), fake()->numberBetween(1, 2))
            );
            
            foreach ($assignmentsToGrade as $assignment) {
                Grades::factory()->create([
                    'enrollment_id' => $enrollment->id,
                    'assignment_id' => $assignment->id,
                ]);
            }
        }

        // Create additional grades with different characteristics
        Grades::factory(10)->excellent()->create();
        Grades::factory(10)->good()->create();
        Grades::factory(5)->average()->create();
        Grades::factory(5)->poor()->create();
    }
} 