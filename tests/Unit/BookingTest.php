<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_has_user_relationship()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals($user->id, $booking->user->id);
    }

    public function test_booking_has_event_relationship()
    {
        $event = Event::factory()->create();
        $booking = Booking::factory()->create(['event_id' => $event->id]);

        $this->assertInstanceOf(Event::class, $booking->event);
        $this->assertEquals($event->id, $booking->event->id);
    }

    public function test_booking_ticket_quantity_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Booking::factory()->create(['ticket_quantity' => null]);
    }

    public function test_booking_total_amount_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Booking::factory()->create(['total_amount' => null]);
    }

    public function test_booking_user_id_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Booking::factory()->create(['user_id' => null]);
    }

    public function test_booking_event_id_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Booking::factory()->create(['event_id' => null]);
    }

    public function test_booking_status_defaults_to_pending()
    {
        $booking = Booking::factory()->create();
        
        $this->assertEquals('pending', $booking->status);
    }

    public function test_booking_payment_status_defaults_to_pending()
    {
        $booking = Booking::factory()->create();
        
        $this->assertEquals('pending', $booking->payment_status);
    }

    public function test_booking_currency_defaults_to_usd()
    {
        $booking = Booking::factory()->create();
        
        $this->assertEquals('USD', $booking->currency);
    }

    public function test_booking_confirmed_scope()
    {
        Booking::factory()->create(['status' => 'confirmed']);
        Booking::factory()->create(['status' => 'pending']);

        $confirmedBookings = Booking::confirmed()->get();
        $this->assertCount(1, $confirmedBookings);
        $this->assertEquals('confirmed', $confirmedBookings->first()->status);
    }

    public function test_booking_paid_scope()
    {
        Booking::factory()->create(['payment_status' => 'paid']);
        Booking::factory()->create(['payment_status' => 'pending']);

        $paidBookings = Booking::paid()->get();
        $this->assertCount(1, $paidBookings);
        $this->assertEquals('paid', $paidBookings->first()->payment_status);
    }

    public function test_booking_total_amount_calculation()
    {
        $event = Event::factory()->create(['price' => 50.00]);
        $booking = Booking::factory()->create([
            'event_id' => $event->id,
            'ticket_quantity' => 3,
            'total_amount' => 50.00 * 3,
        ]);

        $this->assertEquals(150.00, $booking->total_amount);
    }

    public function test_booking_status_enum_values()
    {
        $booking = Booking::factory()->create(['status' => 'confirmed']);
        
        $this->assertTrue(in_array($booking->status, ['pending', 'confirmed', 'cancelled']));
    }

    public function test_booking_payment_status_enum_values()
    {
        $booking = Booking::factory()->create(['payment_status' => 'paid']);
        
        $this->assertTrue(in_array($booking->payment_status, ['pending', 'paid', 'refunded']));
    }

    public function test_booking_ticket_quantity_is_positive()
    {
        $booking = Booking::factory()->create(['ticket_quantity' => 2]);
        
        $this->assertGreaterThan(0, $booking->ticket_quantity);
    }

    public function test_booking_total_amount_is_positive()
    {
        $booking = Booking::factory()->create(['total_amount' => 50.00]);
        
        $this->assertGreaterThan(0, $booking->total_amount);
    }
}
