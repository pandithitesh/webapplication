<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_role_attribute()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $this->assertEquals('attendee', $user->role);
    }

    public function test_user_is_attendee_method()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $this->assertTrue($attendee->isAttendee());
        $this->assertFalse($organizer->isAttendee());
    }

    public function test_user_is_organizer_method()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $this->assertFalse($attendee->isOrganizer());
        $this->assertTrue($organizer->isOrganizer());
    }

    public function test_user_has_events_relationship()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        Event::factory()->count(3)->create(['organizer_id' => $organizer->id]);

        $this->assertCount(3, $organizer->events);
        $this->assertInstanceOf(Event::class, $organizer->events->first());
    }

    public function test_user_has_bookings_relationship()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Booking::factory()->count(2)->create(['user_id' => $attendee->id]);

        $this->assertCount(2, $attendee->bookings);
        $this->assertInstanceOf(Booking::class, $attendee->bookings->first());
    }

    public function test_user_password_is_hashed()
    {
        $password = 'password123';
        $user = User::factory()->create(['password' => $password]);

        $this->assertNotEquals($password, $user->password);
        $this->assertTrue(password_verify($password, $user->password));
    }

    public function test_user_name_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::factory()->create(['name' => null]);
    }

    public function test_user_email_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::factory()->create(['email' => null]);
    }

    public function test_user_email_is_unique()
    {
        User::factory()->create(['email' => 'test@example.com']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'test@example.com']);
    }

    public function test_user_role_enum_values()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $this->assertTrue(in_array($attendee->role, ['attendee', 'organizer']));
        $this->assertTrue(in_array($organizer->role, ['attendee', 'organizer']));
    }
}
