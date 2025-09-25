<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'refunded']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'payment_reference' => fake()->uuid(),
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'special_requirements' => fake()->optional(0.3)->paragraph(),
            'attendee_info' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ];
    }

    /**
     * Create a confirmed booking.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Create a pending booking.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_date' => null,
        ]);
    }

    /**
     * Create a cancelled booking.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    /**
     * Create a paid booking.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
