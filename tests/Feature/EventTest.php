<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_events()
    {
        Event::factory()->count(5)->create();

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertViewHas('events');
    }

    public function test_guest_can_view_single_event()
    {
        $event = Event::factory()->create();

        $response = $this->get("/events/{$event->slug}");

        $response->assertStatus(200);
        $response->assertViewHas('event', $event);
    }

    public function test_organizer_can_create_event()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $this->actingAs($organizer);

        $category = Category::factory()->create();

        $eventData = [
            'title' => 'Test Event',
            'description' => 'Test Description',
            'start_date' => now()->addDays(30)->format('Y-m-d\TH:i'),
            'end_date' => now()->addDays(30)->addHours(2)->format('Y-m-d\TH:i'),
            'registration_deadline' => now()->addDays(25)->format('Y-m-d\TH:i'),
            'venue' => 'Test Venue',
            'city' => 'Test City',
            'capacity' => 100,
            'price' => 50.00,
            'categories' => [$category->id],
        ];

        $response = $this->post('/events', $eventData);

        $response->assertRedirect('/events/manage');
        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'organizer_id' => $organizer->id,
        ]);
    }

    public function test_organizer_can_update_event()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);
        $this->actingAs($organizer);

        $updateData = [
            'title' => 'Updated Event Title',
            'description' => 'Updated Description',
            'start_date' => now()->addDays(30)->format('Y-m-d\TH:i'),
            'end_date' => now()->addDays(30)->addHours(2)->format('Y-m-d\TH:i'),
            'registration_deadline' => now()->addDays(25)->format('Y-m-d\TH:i'),
            'venue' => 'Updated Venue',
            'city' => 'Updated City',
            'capacity' => 150,
            'price' => 75.00,
        ];

        $response = $this->put("/events/{$event->id}", $updateData);

        $response->assertRedirect('/events/manage');
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Event Title',
        ]);
    }

    public function test_organizer_can_delete_event_without_bookings()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);
        $this->actingAs($organizer);

        $response = $this->delete("/events/{$event->id}");

        $response->assertRedirect('/events/manage');
        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }

    public function test_organizer_cannot_delete_event_with_bookings()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['organizer_id' => $organizer->id]);
        $event->bookings()->create([
            'user_id' => User::factory()->create()->id,
            'ticket_quantity' => 1,
            'total_amount' => $event->price,
            'currency' => 'USD',
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
        $this->actingAs($organizer);

        $response = $this->delete("/events/{$event->id}");

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }

    public function test_attendee_cannot_create_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $this->actingAs($attendee);

        $response = $this->get('/events/create');
        $response->assertStatus(403);

        $response = $this->post('/events', []);
        $response->assertStatus(403);
    }

    public function test_event_creation_validation()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $this->actingAs($organizer);

        $response = $this->post('/events', []);

        $response->assertSessionHasErrors([
            'title', 'start_date', 'registration_deadline', 'venue', 'city', 'capacity', 'price'
        ]);
    }

    public function test_event_pagination()
    {
        Event::factory()->count(15)->create();

        $response = $this->get('/events');

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertEquals(8, $events->count());
    }

    public function test_event_filtering_by_category()
    {
        $category = Category::factory()->create();
        $event1 = Event::factory()->create();
        $event2 = Event::factory()->create();
        
        $event1->categories()->attach($category->id);

        $response = $this->get("/events?category={$category->id}");

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertTrue($events->contains('id', $event1->id));
        $this->assertFalse($events->contains('id', $event2->id));
    }

    public function test_event_search()
    {
        Event::factory()->create(['title' => 'Laravel Workshop']);
        Event::factory()->create(['title' => 'PHP Conference']);

        $response = $this->get('/events?search=Laravel');

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertTrue($events->contains('title', 'Laravel Workshop'));
        $this->assertFalse($events->contains('title', 'PHP Conference'));
    }

    public function test_event_sorting()
    {
        $event1 = Event::factory()->create([
            'title' => 'Alpha Event',
            'price' => 100,
        ]);
        $event2 = Event::factory()->create([
            'title' => 'Beta Event',
            'price' => 50,
        ]);

        $response = $this->get('/events?sort=title&order=asc');

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertEquals($event1->id, $events->first()->id);

        $response = $this->get('/events?sort=price&order=asc');
        $events = $response->viewData('events');
        $this->assertEquals($event2->id, $events->first()->id);
    }

    public function test_ajax_event_filtering()
    {
        Event::factory()->count(5)->create();

        $response = $this->post('/events/ajax-filter', [
            'search' => '',
            'category' => '',
            'city' => '',
            'sort' => 'start_date',
            'order' => 'asc',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'total'
        ]);
    }

    public function test_organizer_can_view_event_management()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        Event::factory()->count(3)->create(['organizer_id' => $organizer->id]);
        $this->actingAs($organizer);

        $response = $this->get('/events/manage');

        $response->assertStatus(200);
        $response->assertViewHas('events');
    }

    public function test_organizer_dashboard_shows_events_report()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
            'capacity' => 100,
        ]);
        $this->actingAs($organizer);

        $response = $this->get('/dashboard/organizer');

        $response->assertStatus(200);
        $response->assertSee('Events Report');
        $response->assertSee($event->title);
    }
}
