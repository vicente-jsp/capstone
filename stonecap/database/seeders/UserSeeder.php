<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lms.test'], // Find by email first
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Change in production!
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Optional: Create Sample Educator
        User::updateOrCreate(
            ['email' => 'educator@lms.test'],
            [
                'name' => 'Sample Educator',
                'password' => Hash::make('password'),
                'role' => 'educator',
                'email_verified_at' => now(),
            ]
        );

        // Optional: Create Sample Student
         User::updateOrCreate(
             ['email' => 'student@lms.test'],
             [
                 'name' => 'Sample Student',
                 'password' => Hash::make('password'),
                 'role' => 'student',
                 'email_verified_at' => now(),
             ]
         );

         // Optional: Create more users using Factories
         // User::factory(10)->create(['role' => 'student']);
    }
}
