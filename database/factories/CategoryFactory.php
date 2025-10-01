<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(2, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'color' => fake()->hexColor(),
            'icon' => fake()->randomElement([
                'fas fa-laptop-code',
                'fas fa-briefcase',
                'fas fa-graduation-cap',
                'fas fa-heart',
                'fas fa-palette',
                'fas fa-dumbbell',
                'fas fa-utensils',
                'fas fa-music'
            ]),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
