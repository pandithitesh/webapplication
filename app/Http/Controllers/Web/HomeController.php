<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Services\EventRecommendationService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::with(['organizer', 'categories'])
                              ->published()
                              ->upcoming()
                              ->orderBy('start_date', 'asc')
                              ->paginate(8);

        $featuredEvents = Event::with(['organizer', 'categories'])
                              ->published()
                              ->featured()
                              ->upcoming()
                              ->limit(6)
                              ->get();

        $categories = Category::active()
                            ->ordered()
                            ->limit(8)
                            ->get();

        $stats = [
            'total_events' => Event::published()->count(),
            'total_attendees' => \App\Models\Booking::confirmed()->sum('ticket_quantity'),
            'total_organizers' => \App\Models\User::where('role', 'organizer')->count(),
            'cities' => Event::published()->distinct('city')->count('city'),
        ];

        $recommendations = collect();
        
        if (auth()->check() && auth()->user()->isAttendee()) {
            $recommendationService = new EventRecommendationService();
            $recommendations = $recommendationService->getRecommendationsForUser(auth()->user(), 4);
        }

        return view('home', compact('upcomingEvents', 'featuredEvents', 'categories', 'stats', 'recommendations'));
    }
}
