<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Collection;

class EventRecommendationService
{
    /**
     * Generate personalized event recommendations for a user
     * 
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public function getRecommendationsForUser(User $user, int $limit = 6): Collection
    {
        if (!$user->isAttendee()) {
            return collect();
        }

        $userBookings = $this->getUserBookingHistory($user);
        
        if ($userBookings->isEmpty()) {
            return $this->getPopularEvents($limit);
        }

        $recommendations = collect();

        $recommendations = $recommendations->merge(
            $this->getCategoryBasedRecommendations($user, $userBookings, $limit)
        );

        $recommendations = $recommendations->merge(
            $this->getLocationBasedRecommendations($user, $userBookings, $limit)
        );

        $recommendations = $recommendations->merge(
            $this->getPriceBasedRecommendations($user, $userBookings, $limit)
        );

        return $this->prioritizeAndLimit($recommendations, $limit);
    }

    private function getUserBookingHistory(User $user): Collection
    {
        return Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with(['event.categories'])
            ->get()
            ->pluck('event')
            ->filter();
    }

    private function getCategoryBasedRecommendations(User $user, Collection $userBookings, int $limit): Collection
    {
        $userCategories = $userBookings
            ->flatMap(fn($event) => $event->categories)
            ->groupBy('id')
            ->map(fn($categories) => $categories->count())
            ->sortDesc();

        if ($userCategories->isEmpty()) {
            return collect();
        }

        $topCategories = $userCategories->take(3)->keys();

        return Event::with(['organizer', 'categories'])
            ->published()
            ->upcoming()
            ->whereHas('categories', function ($query) use ($topCategories) {
                $query->whereIn('categories.id', $topCategories);
            })
            ->whereNotIn('id', $userBookings->pluck('id'))
            ->where('registration_deadline', '>', now())
            ->limit($limit * 2)
            ->get()
            ->map(function ($event) use ($userCategories) {
                $event->recommendation_score = $this->calculateCategoryScore($event, $userCategories);
                return $event;
            });
    }

    private function getLocationBasedRecommendations(User $user, Collection $userBookings, int $limit): Collection
    {
        $userCities = $userBookings
            ->pluck('city')
            ->groupBy(fn($city) => $city)
            ->map(fn($cities) => $cities->count())
            ->sortDesc();

        if ($userCities->isEmpty()) {
            return collect();
        }

        $preferredCities = $userCities->take(3)->keys();

        return Event::with(['organizer', 'categories'])
            ->published()
            ->upcoming()
            ->whereIn('city', $preferredCities)
            ->whereNotIn('id', $userBookings->pluck('id'))
            ->where('registration_deadline', '>', now())
            ->limit($limit)
            ->get()
            ->map(function ($event) use ($userCities) {
                $event->recommendation_score = $userCities->get($event->city, 0) * 2;
                return $event;
            });
    }

    private function getPriceBasedRecommendations(User $user, Collection $userBookings, int $limit): Collection
    {
        $userPrices = $userBookings->pluck('price')->filter();
        
        if ($userPrices->isEmpty()) {
            return collect();
        }

        $avgPrice = $userPrices->avg();
        $priceRange = $avgPrice * 0.5;

        return Event::with(['organizer', 'categories'])
            ->published()
            ->upcoming()
            ->whereBetween('price', [$avgPrice - $priceRange, $avgPrice + $priceRange])
            ->whereNotIn('id', $userBookings->pluck('id'))
            ->where('registration_deadline', '>', now())
            ->limit($limit)
            ->get()
            ->map(function ($event) use ($avgPrice) {
                $event->recommendation_score = 1 - (abs($event->price - $avgPrice) / $avgPrice);
                return $event;
            });
    }

    private function getPopularEvents(int $limit): Collection
    {
        return Event::with(['organizer', 'categories'])
            ->published()
            ->upcoming()
            ->where('is_featured', true)
            ->where('registration_deadline', '>', now())
            ->limit($limit)
            ->get()
            ->map(function ($event) {
                $event->recommendation_score = 1.0;
                return $event;
            });
    }

    private function calculateCategoryScore(Event $event, Collection $userCategories): float
    {
        $score = 0;
        $totalWeight = $userCategories->sum();

        foreach ($event->categories as $category) {
            if ($userCategories->has($category->id)) {
                $score += $userCategories->get($category->id) / $totalWeight;
            }
        }

        return $score;
    }

    private function prioritizeAndLimit(Collection $recommendations, int $limit): Collection
    {
        return $recommendations
            ->unique('id')
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }

    public function getRecommendationReasons(Event $event, User $user): array
    {
        $reasons = [];
        $userBookings = $this->getUserBookingHistory($user);

        if ($userBookings->isEmpty()) {
            return ['This is a featured event'];
        }

        $userCategories = $userBookings->flatMap(fn($e) => $e->categories)->pluck('name')->unique();
        $eventCategories = $event->categories->pluck('name');

        if ($userCategories->intersect($eventCategories)->isNotEmpty()) {
            $reasons[] = 'Similar to events you\'ve booked';
        }

        $userCities = $userBookings->pluck('city')->unique();
        if ($userCities->contains($event->city)) {
            $reasons[] = 'In a city you\'ve visited';
        }

        $userPrices = $userBookings->pluck('price')->filter();
        if ($userPrices->isNotEmpty()) {
            $avgPrice = $userPrices->avg();
            $priceDiff = abs($event->price - $avgPrice);
            if ($priceDiff <= $avgPrice * 0.3) {
                $reasons[] = 'Similar price range';
            }
        }

        if (empty($reasons)) {
            $reasons[] = 'Popular in your area';
        }

        return $reasons;
    }
}
