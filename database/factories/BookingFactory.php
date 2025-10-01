<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $ticketQuantity = fake()->numberBetween(1, 5);
        $event = Event::factory()->create();
        $totalAmount = $event->price * $ticketQuantity;

        return [
            'event_id' => $event,
            'user_id' => User::factory()->attendee(),
            'booking_reference' => 'BK' . strtoupper(fake()->bothify('########')),
            'ticket_quantity' => $ticketQuantity,
            'total_amount' => $totalAmount,
            'currency' => $event->currency,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'payment_reference' => fake()->uuid(),
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'special_requirements' => fake()->optional(0.3)->paragraph(),
            'attendee_info' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_date' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
