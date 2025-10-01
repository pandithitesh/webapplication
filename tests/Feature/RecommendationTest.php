<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Booking;
use App\Services\EventRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendations_show_for_logged_in_attendees()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Event::factory()->count(5)->create(['is_featured' => true]);
        $this->actingAs($attendee);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee('Recommended for You');
    }

    public function test_recommendations_not_show_for_guests()
    {
        Event::factory()->count(5)->create(['is_featured' => true]);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertDontSee('Recommended for You');
    }

    public function test_recommendations_not_show_for_organizers()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        Event::factory()->count(5)->create(['is_featured' => true]);
        $this->actingAs($organizer);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertDontSee('Recommended for You');
    }

    public function test_category_based_recommendations()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $category = Category::factory()->create();
        
        $bookedEvent = Event::factory()->create();
        $bookedEvent->categories()->attach($category->id);
        
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create();
        $recommendedEvent->categories()->attach($category->id);

        $this->actingAs($attendee);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee('Recommended for You');
    }

    public function test_location_based_recommendations()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $city = 'New York';
        
        $bookedEvent = Event::factory()->create(['city' => $city]);
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create(['city' => $city]);

        $this->actingAs($attendee);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee('Recommended for You');
    }

    public function test_price_based_recommendations()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $price = 50.00;
        
        $bookedEvent = Event::factory()->create(['price' => $price]);
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create(['price' => 45.00]);

        $this->actingAs($attendee);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee('Recommended for You');
    }

    public function test_recommendation_service_returns_featured_events_for_new_users()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create(['is_featured' => true]);

        $service = new EventRecommendationService();
        $recommendations = $service->getRecommendationsForUser($attendee, 6);

        $this->assertCount(1, $recommendations);
        $this->assertTrue($recommendations->first()->is_featured);
    }

    public function test_recommendation_service_excludes_already_booked_events()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();
        
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
        ]);

        $service = new EventRecommendationService();
        $recommendations = $service->getRecommendationsForUser($attendee, 6);

        $this->assertFalse($recommendations->contains('id', $event->id));
    }

    public function test_recommendation_service_excludes_past_events()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create([
            'start_date' => now()->subDays(1),
            'is_featured' => true,
        ]);

        $service = new EventRecommendationService();
        $recommendations = $service->getRecommendationsForUser($attendee, 6);

        $this->assertCount(0, $recommendations);
    }

    public function test_recommendation_service_excludes_events_with_past_registration_deadline()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create([
            'registration_deadline' => now()->subDays(1),
            'is_featured' => true,
        ]);

        $service = new EventRecommendationService();
        $recommendations = $service->getRecommendationsForUser($attendee, 6);

        $this->assertCount(0, $recommendations);
    }

    public function test_recommendation_reasons_api_endpoint()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['is_featured' => true]);
        $this->actingAs($attendee);

        $response = $this->get("/events/{$event->id}/recommendation-reasons");

        $response->assertStatus(200);
        $response->assertJsonStructure(['reasons']);
    }

    public function test_recommendation_reasons_api_requires_authentication()
    {
        $event = Event::factory()->create();

        $response = $this->get("/events/{$event->id}/recommendation-reasons");

        $response->assertStatus(401);
    }

    public function test_recommendation_reasons_api_requires_attendee_role()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create();
        $this->actingAs($organizer);

        $response = $this->get("/events/{$event->id}/recommendation-reasons");

        $response->assertStatus(401);
    }

    public function test_recommendation_service_calculates_scores_correctly()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $category = Category::factory()->create();
        
        $bookedEvent = Event::factory()->create();
        $bookedEvent->categories()->attach($category->id);
        
        Booking::factory()->count(3)->create([
            'user_id' => $attendee->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create();
        $recommendedEvent->categories()->attach($category->id);

        $service = new EventRecommendationService();
        $recommendations = $service->getRecommendationsForUser($attendee, 6);

        $this->assertTrue($recommendations->contains('id', $recommendedEvent->id));
        $this->assertGreaterThan(0, $recommendations->first()->recommendation_score);
    }

    public function test_recommendations_show_on_home_page()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create(['is_featured' => true]);
        $this->actingAs($attendee);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Personalized Recommendations');
    }

    public function test_recommendations_limit_respected()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        Event::factory()->count(10)->create(['is_featured' => true]);
        $this->actingAs($attendee);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $recommendations = $response->viewData('recommendations');
        $this->assertLessThanOrEqual(6, $recommendations->count());
    }
}
