<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_has_organizer_relationship()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);

        $this->assertInstanceOf(User::class, $event->organizer);
        $this->assertEquals($organizer->id, $event->organizer->id);
    }

    public function test_event_has_bookings_relationship()
    {
        $event = Event::factory()->create();
        Booking::factory()->count(3)->create(['event_id' => $event->id]);

        $this->assertCount(3, $event->bookings);
        $this->assertInstanceOf(Booking::class, $event->bookings->first());
    }

    public function test_event_has_categories_relationship()
    {
        $event = Event::factory()->create();
        $category = Category::factory()->create();
        $event->categories()->attach($category->id);

        $this->assertCount(1, $event->categories);
        $this->assertInstanceOf(Category::class, $event->categories->first());
    }

    public function test_event_available_spots_calculation()
    {
        $event = Event::factory()->create(['capacity' => 100]);
        
        Booking::factory()->create([
            'event_id' => $event->id,
            'ticket_quantity' => 30,
            'status' => 'confirmed',
        ]);

        $this->assertEquals(70, $event->available_spots);
    }

    public function test_event_is_sold_out_method()
    {
        $event = Event::factory()->create(['capacity' => 10]);
        
        $this->assertFalse($event->isSoldOut());

        Booking::factory()->create([
            'event_id' => $event->id,
            'ticket_quantity' => 10,
            'status' => 'confirmed',
        ]);

        $this->assertTrue($event->fresh()->isSoldOut());
    }

    public function test_event_is_registration_open_method()
    {
        $event = Event::factory()->create([
            'registration_deadline' => now()->addDays(10),
            'start_date' => now()->addDays(30),
        ]);

        $this->assertTrue($event->isRegistrationOpen());

        $event->update(['registration_deadline' => now()->subDays(1)]);
        $this->assertFalse($event->fresh()->isRegistrationOpen());
    }

    public function test_event_published_scope()
    {
        Event::factory()->create(['status' => 'published']);
        Event::factory()->create(['status' => 'draft']);

        $publishedEvents = Event::published()->get();
        $this->assertCount(1, $publishedEvents);
        $this->assertEquals('published', $publishedEvents->first()->status);
    }

    public function test_event_upcoming_scope()
    {
        Event::factory()->create(['start_date' => now()->addDays(30)]);
        Event::factory()->create(['start_date' => now()->subDays(1)]);

        $upcomingEvents = Event::upcoming()->get();
        $this->assertCount(1, $upcomingEvents);
        $this->assertTrue($upcomingEvents->first()->start_date->isFuture());
    }

    public function test_event_title_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Event::factory()->create(['title' => null]);
    }

    public function test_event_organizer_id_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Event::factory()->create(['organizer_id' => null]);
    }

    public function test_event_capacity_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Event::factory()->create(['capacity' => null]);
    }

    public function test_event_price_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Event::factory()->create(['price' => null]);
    }

    public function test_event_uses_soft_deletes()
    {
        $event = Event::factory()->create();
        $event->delete();

        $this->assertSoftDeleted('events', ['id' => $event->id]);
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }

    public function test_event_slug_is_generated_from_title()
    {
        $event = Event::factory()->create(['title' => 'Test Event Title', 'slug' => null]);
        
        $this->assertNotNull($event->slug);
        $this->assertEquals('test-event-title', $event->slug);
    }

    public function test_event_currency_defaults_to_usd()
    {
        $event = Event::factory()->create();
        
        $this->assertEquals('USD', $event->currency);
    }

    public function test_event_status_defaults_to_published()
    {
        $event = Event::factory()->create();
        
        $this->assertEquals('published', $event->status);
    }

    public function test_event_featured_defaults_to_false()
    {
        $event = Event::factory()->create();
        
        $this->assertFalse($event->is_featured);
    }
}
