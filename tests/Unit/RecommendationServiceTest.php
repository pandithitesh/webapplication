<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Booking;
use App\Services\EventRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    private EventRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EventRecommendationService();
    }

    public function test_get_recommendations_for_new_user_returns_featured_events()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create(['is_featured' => true]);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $this->assertCount(1, $recommendations);
        $this->assertTrue($recommendations->first()->is_featured);
    }

    public function test_get_recommendations_for_organizer_returns_empty()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $recommendations = $this->service->getRecommendationsForUser($organizer, 6);

        $this->assertCount(0, $recommendations);
    }

    public function test_get_recommendations_excludes_already_booked_events()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $bookedEvent = Event::factory()->create();
        
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $this->assertFalse($recommendations->contains('id', $bookedEvent->id));
    }

    public function test_get_recommendations_excludes_past_events()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create([
            'start_date' => now()->subDays(1),
            'is_featured' => true,
        ]);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $this->assertCount(0, $recommendations);
    }

    public function test_get_recommendations_excludes_events_with_past_registration_deadline()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        Event::factory()->create([
            'registration_deadline' => now()->subDays(1),
            'is_featured' => true,
        ]);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $this->assertCount(0, $recommendations);
    }

    public function test_get_recommendations_respects_limit()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        Event::factory()->count(10)->create(['is_featured' => true]);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $this->assertLessThanOrEqual(6, $recommendations->count());
    }

    public function test_get_recommendations_prioritizes_by_score()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $category = Category::factory()->create();
        
        $bookedEvent = Event::factory()->create();
        $bookedEvent->categories()->attach($category->id);
        
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent1 = Event::factory()->create();
        $recommendedEvent1->categories()->attach($category->id);
        
        $recommendedEvent2 = Event::factory()->create(['is_featured' => true]);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $this->assertTrue($recommendations->contains('id', $recommendedEvent1->id));
        $this->assertTrue($recommendations->contains('id', $recommendedEvent2->id));
        
        $categoryEvent = $recommendations->where('id', $recommendedEvent1->id)->first();
        $featuredEvent = $recommendations->where('id', $recommendedEvent2->id)->first();
        
        $this->assertGreaterThan($featuredEvent->recommendation_score, $categoryEvent->recommendation_score);
    }

    public function test_get_recommendation_reasons_for_featured_event()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create(['is_featured' => true]);

        $reasons = $this->service->getRecommendationReasons($event, $user);

        $this->assertContains('This is a featured event', $reasons);
    }

    public function test_get_recommendation_reasons_for_category_match()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $category = Category::factory()->create();
        
        $bookedEvent = Event::factory()->create();
        $bookedEvent->categories()->attach($category->id);
        
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create();
        $recommendedEvent->categories()->attach($category->id);

        $reasons = $this->service->getRecommendationReasons($recommendedEvent, $user);

        $this->assertContains('Similar to events you\'ve booked', $reasons);
    }

    public function test_get_recommendation_reasons_for_location_match()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $city = 'New York';
        
        $bookedEvent = Event::factory()->create(['city' => $city]);
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create(['city' => $city]);

        $reasons = $this->service->getRecommendationReasons($recommendedEvent, $user);

        $this->assertContains('In a city you\'ve visited', $reasons);
    }

    public function test_get_recommendation_reasons_for_price_match()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $price = 50.00;
        
        $bookedEvent = Event::factory()->create(['price' => $price]);
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent = Event::factory()->create(['price' => 45.00]);

        $reasons = $this->service->getRecommendationReasons($recommendedEvent, $user);

        $this->assertContains('Similar price range', $reasons);
    }

    public function test_get_recommendation_reasons_fallback()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $event = Event::factory()->create();

        $reasons = $this->service->getRecommendationReasons($event, $user);

        $this->assertContains('Popular in your area', $reasons);
    }

    public function test_category_based_recommendations_calculate_score_correctly()
    {
        $user = User::factory()->create(['role' => 'attendee']);
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        $bookedEvent1 = Event::factory()->create();
        $bookedEvent1->categories()->attach($category1->id);
        
        $bookedEvent2 = Event::factory()->create();
        $bookedEvent2->categories()->attach($category1->id);
        
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent1->id,
            'status' => 'confirmed',
        ]);
        
        Booking::factory()->create([
            'user_id' => $user->id,
            'event_id' => $bookedEvent2->id,
            'status' => 'confirmed',
        ]);

        $recommendedEvent1 = Event::factory()->create();
        $recommendedEvent1->categories()->attach($category1->id);
        
        $recommendedEvent2 = Event::factory()->create();
        $recommendedEvent2->categories()->attach($category2->id);

        $recommendations = $this->service->getRecommendationsForUser($user, 6);

        $category1Event = $recommendations->where('id', $recommendedEvent1->id)->first();
        $category2Event = $recommendations->where('id', $recommendedEvent2->id)->first();
        
        $this->assertNotNull($category1Event);
        $this->assertGreaterThan(0, $category1Event->recommendation_score);
        
        if ($category2Event) {
            $this->assertLessThan($category1Event->recommendation_score, $category2Event->recommendation_score);
        }
    }
}
