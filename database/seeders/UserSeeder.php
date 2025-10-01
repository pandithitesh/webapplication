<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0130',
                'bio' => 'Marketing professional and event networking enthusiast.',
            ],
            [
                'name' => 'Frank Johnson',
                'email' => 'frank@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0131',
                'bio' => 'Software developer passionate about tech conferences.',
            ],
            [
                'name' => 'Grace Lee',
                'email' => 'grace@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0132',
                'bio' => 'Designer and creative workshop participant.',
            ],
            [
                'name' => 'Henry Chen',
                'email' => 'henry@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0133',
                'bio' => 'Entrepreneur and startup event regular.',
            ],
            [
                'name' => 'Ivy Rodriguez',
                'email' => 'ivy@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0134',
                'bio' => 'Community manager and social event organizer.',
            ],
            [
                'name' => 'Jack Thompson',
                'email' => 'jack@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0135',
                'bio' => 'Consultant and business networking expert.',
            ],
            [
                'name' => 'Kate Anderson',
                'email' => 'kate@example.com',
                'password' => Hash::make('password'),
                'role' => 'attendee',
                'phone' => '+1-555-0136',
                'bio' => 'Content creator and digital marketing specialist.',
            ],
        ];

        foreach ($attendees as $attendee) {
            User::create($attendee);
        }
    }
}
