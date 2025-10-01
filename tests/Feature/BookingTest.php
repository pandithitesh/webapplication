<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_can_book_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'capacity' => 100,
            'registration_deadline' => now()->addDays(10),
            'start_date' => now()->addDays(30),
        ]);
        $this->actingAs($attendee);

        $bookingData = [
            'ticket_quantity' => 2,
        ];

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 2,
        ]);

        $response->assertRedirect('/bookings');
        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'ticket_quantity' => 2,
        ]);
    }

    public function test_attendee_cannot_book_same_event_twice()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
        ]);
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_attendee_cannot_book_full_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'capacity' => 2,
        ]);
        
        Booking::factory()->create([
            'event_id' => $event->id,
            'ticket_quantity' => 2,
            'status' => 'confirmed',
        ]);
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_attendee_cannot_book_past_registration_deadline()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'registration_deadline' => now()->subDays(1),
        ]);
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_attendee_cannot_book_past_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create([
            'start_date' => now()->subDays(1),
        ]);
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_guest_cannot_book_event()
    {
        $event = Event::factory()->create();

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_organizer_cannot_book_own_event()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);
        $this->actingAs($organizer);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_booking_validation()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $this->actingAs($attendee);

        $response = $this->post("/bookings", []);

        $response->assertSessionHasErrors(['event_id', 'ticket_quantity']);
    }

    public function test_attendee_can_view_own_bookings()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $booking = Booking::factory()->create([
            'user_id' => $attendee->id,
            'status' => 'confirmed',
        ]);
        $this->actingAs($attendee);

        $response = $this->get('/bookings');

        $response->assertStatus(200);
        $response->assertSee($booking->event->title);
    }

    public function test_booking_calculates_total_amount_correctly()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['price' => 50.00]);
        $this->actingAs($attendee);

        $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 3,
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'ticket_quantity' => 3,
            'total_amount' => 150.00,
        ]);
    }

    public function test_booking_updates_event_capacity()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['capacity' => 100]);
        $this->actingAs($attendee);

        $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 5,
        ]);

        $this->assertEquals(95, $event->fresh()->available_spots);
    }

    public function test_booking_status_defaults_to_pending()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();
        $this->actingAs($attendee);

        $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);
    }

    public function test_booking_payment_status_defaults_to_pending()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();
        $this->actingAs($attendee);

        $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 1,
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'payment_status' => 'pending',
        ]);
    }

    public function test_attendee_cannot_book_more_than_available()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['capacity' => 5]);
        
        Booking::factory()->create([
            'event_id' => $event->id,
            'ticket_quantity' => 4,
            'status' => 'confirmed',
        ]);
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 2,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_booking_with_zero_quantity_is_invalid()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => 0,
        ]);

        $response->assertSessionHasErrors(['ticket_quantity']);
    }

    public function test_booking_with_negative_quantity_is_invalid()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();
        $this->actingAs($attendee);

        $response = $this->post("/bookings", [
            'event_id' => $event->id,
            'ticket_quantity' => -1,
        ]);

        $response->assertSessionHasErrors(['ticket_quantity']);
    }
}
