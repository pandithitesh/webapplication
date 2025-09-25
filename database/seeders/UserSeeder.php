<?php

namespace Database\Seeders;

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
        // Create sample organizers
        $organizers = [
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'phone' => '+1-555-0123',
                'bio' => 'Experienced event organizer with over 10 years in the industry. Specializing in tech conferences and business events.',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'phone' => '+1-555-0124',
                'bio' => 'Creative event planner focused on arts, culture, and entertainment events.',
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'phone' => '+1-555-0125',
                'bio' => 'Sports and fitness event coordinator with a passion for community building.',
            ],
        ];

        foreach ($organizers as $organizer) {
            User::create($organizer);
        }

        // Create sample attendees
        $attendees = [
            [
                'name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0126',
                'bio' => 'Tech enthusiast and frequent event attendee.',
            ],
            [
                'name' => 'Bob Davis',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0127',
                'bio' => 'Business professional interested in networking and professional development.',
            ],
            [
                'name' => 'Carol Miller',
                'email' => 'carol@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0128',
                'bio' => 'Art lover and cultural event enthusiast.',
            ],
            [
                'name' => 'David Garcia',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0129',
                'bio' => 'Fitness enthusiast and sports event participant.',
            ],
        ];

        foreach ($attendees as $attendee) {
            User::create($attendee);
        }
    }
}
