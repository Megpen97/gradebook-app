<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StudentSeeder::class,
            InstructorSeeder::class,
            CoursesSeeder::class,
            AssignmentsSeeder::class,
            EnrollmentsSeeder::class,
            GradesSeeder::class,
        ]);
    }
}
