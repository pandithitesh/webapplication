<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Tech conferences, workshops, and meetups',
                'color' => '#3B82F6',
                'icon' => 'fas fa-laptop-code',
                'sort_order' => 1,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business conferences, networking events, and seminars',
                'color' => '#10B981',
                'icon' => 'fas fa-briefcase',
                'sort_order' => 2,
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational workshops, courses, and training sessions',
                'color' => '#F59E0B',
                'icon' => 'fas fa-graduation-cap',
                'sort_order' => 3,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'description' => 'Health seminars, fitness events, and wellness workshops',
                'color' => '#EF4444',
                'icon' => 'fas fa-heart',
                'sort_order' => 4,
            ],
            [
                'name' => 'Arts & Culture',
                'slug' => 'arts-culture',
                'description' => 'Art exhibitions, cultural events, and performances',
                'color' => '#8B5CF6',
                'icon' => 'fas fa-palette',
                'sort_order' => 5,
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'description' => 'Sports events, fitness classes, and athletic competitions',
                'color' => '#06B6D4',
                'icon' => 'fas fa-dumbbell',
                'sort_order' => 6,
            ],
            [
                'name' => 'Food & Drink',
                'slug' => 'food-drink',
                'description' => 'Food festivals, wine tastings, and culinary events',
                'color' => '#F97316',
                'icon' => 'fas fa-utensils',
                'sort_order' => 7,
            ],
            [
                'name' => 'Entertainment',
                'slug' => 'entertainment',
                'description' => 'Concerts, shows, and entertainment events',
                'color' => '#EC4899',
                'icon' => 'fas fa-music',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
