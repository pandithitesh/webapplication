<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_booking()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'max_attendees' => 100,
            'price' => 50.00,
            'status' => 'published'
        ]);

        $bookingData = [
            'event_id' => $event->id,
            'ticket_quantity' => 2,
            'special_requirements' => 'Vegetarian meal required'
        ];

        $response = $this->actingAs($attendee, 'sanctum')
                         ->postJson('/api/bookings', $bookingData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'event_id',
                        'user_id',
                        'ticket_quantity',
                        'total_amount'
                    ]
                ]);

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'user_id' => $attendee->id,
            'ticket_quantity' => 2
        ]);
    }

    public function test_cannot_book_sold_out_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'max_attendees' => 1,
            'status' => 'published'
        ]);

        // Create a booking to fill the event
        Booking::factory()->create([
            'event_id' => $event->id,
            'ticket_quantity' => 1,
            'status' => 'confirmed'
        ]);

        $bookingData = [
            'event_id' => $event->id,
            'ticket_quantity' => 1
        ];

        $response = $this->actingAs($attendee, 'sanctum')
                         ->postJson('/api/bookings', $bookingData);

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Not enough spots available. Only 0 spots left.'
                ]);
    }

    public function test_can_view_user_bookings()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $booking = Booking::factory()->create(['user_id' => $attendee->id]);

        $response = $this->actingAs($attendee, 'sanctum')
                         ->getJson('/api/bookings');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'event_id',
                                'ticket_quantity',
                                'total_amount',
                                'status'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_can_cancel_booking()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'start_date' => now()->addDays(30)
        ]);
        $booking = Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($attendee, 'sanctum')
                         ->putJson("/api/bookings/{$booking->id}/cancel", [
                             'cancellation_reason' => 'Change of plans'
                         ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Booking cancelled successfully'
                ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_cannot_cancel_booking_for_past_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'start_date' => now()->subDays(1)
        ]);
        $booking = Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($attendee, 'sanctum')
                         ->putJson("/api/bookings/{$booking->id}/cancel");

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'This booking cannot be cancelled'
                ]);
    }
}
