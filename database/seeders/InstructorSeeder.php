<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instructor;
use App\Models\User;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create instructors for specific users (first 2 users)
        $users = User::take(2)->get();
        
        foreach ($users as $user) {
            Instructor::factory()->create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1] ?? '',
                'email' => $user->email,
            ]);
        }

        // Create additional standalone instructors with different characteristics
        Instructor::factory(1)->senior()->create();
        Instructor::factory(1)->junior()->create();
        Instructor::factory(2)->create(); // Regular instructors
    }
} 