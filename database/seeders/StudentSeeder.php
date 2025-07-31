<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create students with different characteristics
        Student::factory(3)->freshman()->create();
        Student::factory(3)->senior()->create();
        Student::factory(5)->create(); // Regular students
    }
} 