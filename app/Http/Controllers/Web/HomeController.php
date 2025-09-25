<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // All upcoming events (main requirement)
        $upcomingEvents = Event::with(['organizer', 'categories'])
                              ->published()
                              ->upcoming()
                              ->orderBy('start_date', 'asc')
                              ->paginate(8);

        // Featured events for hero section
        $featuredEvents = Event::with(['organizer', 'categories'])
                              ->published()
                              ->featured()
                              ->upcoming()
                              ->limit(6)
                              ->get();

        // Categories
        $categories = Category::active()
                            ->ordered()
                            ->limit(8)
                            ->get();

        // Statistics
        $stats = [
            'total_events' => Event::published()->count(),
            'total_attendees' => \App\Models\Booking::confirmed()->sum('ticket_quantity'),
            'total_organizers' => \App\Models\User::where('role', 'organizer')->count(),
            'cities' => Event::published()->distinct('city')->count('city'),
        ];

        return view('home', compact('upcomingEvents', 'featuredEvents', 'categories', 'stats'));
    }
}
