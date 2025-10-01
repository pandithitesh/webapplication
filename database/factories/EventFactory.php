<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 day', '+3 months');
        $endDate = clone $startDate;
        $endDate->modify('+2 hours');
        $registrationDeadline = fake()->dateTimeBetween('now', $startDate->format('Y-m-d') . ' -1 day');

        return [
            'organizer_id' => User::factory()->organizer(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraphs(3, true),
            'slug' => Str::slug(fake()->sentence(3)),
            'venue' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'registration_deadline' => $registrationDeadline,
            'capacity' => fake()->numberBetween(10, 1000),
            'price' => fake()->randomFloat(2, 0, 500),
            'currency' => 'USD',
            'image' => null,
            'images' => null,
            'tags' => fake()->words(3),
            'status' => 'published',
            'is_featured' => false,
            'requires_approval' => fake()->boolean(30),
            'cancellation_policy' => fake()->paragraph(),
            'refund_policy' => fake()->paragraph(),
            'additional_info' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = fake()->dateTimeBetween('+1 day', '+3 months');
            $endDate = clone $startDate;
            $endDate->modify('+2 hours');
            $registrationDeadline = fake()->dateTimeBetween('now', $startDate->format('Y-m-d') . ' -1 day');

            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'registration_deadline' => $registrationDeadline,
                'status' => 'published',
            ];
        });
    }
}
