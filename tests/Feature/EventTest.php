<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_events()
    {
        $response = $this->get('/api/events');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'description',
                                'start_date',
                                'end_date',
                                'price',
                                'organizer',
                                'categories'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_can_create_event_as_organizer()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $category = Category::factory()->create();

        $eventData = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'venue' => 'Test Venue',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'Test Country',
            'postal_code' => '12345',
            'start_date' => now()->addDays(30)->toISOString(),
            'end_date' => now()->addDays(31)->toISOString(),
            'registration_deadline' => now()->addDays(25)->toISOString(),
            'max_attendees' => 100,
            'price' => 50.00,
            'currency' => 'USD',
            'category_ids' => [$category->id]
        ];

        $response = $this->actingAs($organizer, 'sanctum')
                         ->postJson('/api/events', $eventData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'organizer',
                        'categories'
                    ]
                ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'organizer_id' => $organizer->id
        ]);
    }

    public function test_cannot_create_event_as_attendee()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);

        $eventData = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            // ... other required fields
        ];

        $response = $this->actingAs($attendee, 'sanctum')
                         ->postJson('/api/events', $eventData);

        $response->assertStatus(403);
    }

    public function test_can_view_event_details()
    {
        $event = Event::factory()->create();

        $response = $this->get("/api/events/{$event->slug}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'organizer',
                        'categories'
                    ]
                ]);
    }

    public function test_can_search_events()
    {
        Event::factory()->create(['title' => 'Tech Conference']);
        Event::factory()->create(['title' => 'Art Exhibition']);

        $response = $this->get('/api/events?search=Tech');

        $response->assertStatus(200);
        
        $events = $response->json('data.data');
        $this->assertCount(1, $events);
        $this->assertEquals('Tech Conference', $events[0]['title']);
    }

    public function test_can_filter_events_by_category()
    {
        $category = Category::factory()->create(['slug' => 'technology']);
        $event = Event::factory()->create();
        $event->categories()->attach($category);

        $response = $this->get("/api/events?category=technology");

        $response->assertStatus(200);
        
        $events = $response->json('data.data');
        $this->assertCount(1, $events);
    }
}
