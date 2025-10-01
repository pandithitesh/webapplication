<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isOrganizer()) {
            return $this->organizer($request);
        } else {
            return $this->attendee($request);
        }
    }

    public function organizer(Request $request)
    {
        $organizerId = $request->user()->id;

        $eventStats = [
            'total_events' => Event::where('organizer_id', $organizerId)->count(),
            'published_events' => Event::where('organizer_id', $organizerId)->published()->count(),
            'draft_events' => Event::where('organizer_id', $organizerId)->where('status', 'draft')->count(),
            'upcoming_events' => Event::where('organizer_id', $organizerId)->published()->upcoming()->count(),
            'completed_events' => Event::where('organizer_id', $organizerId)->where('status', 'completed')->count(),
        ];

        $bookingStats = [
            'total_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->count(),
            'confirmed_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->confirmed()->count(),
            'pending_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->pending()->count(),
            'total_revenue' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->confirmed()->sum('total_amount'),
        ];

        $recentEvents = Event::where('organizer_id', $organizerId)
                           ->with(['categories'])
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();

        $recentBookings = Booking::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        })
        ->with(['user', 'event'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

        $revenueByMonth = Booking::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        })
        ->confirmed()
        ->select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as revenue')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'event_stats' => $eventStats,
                'booking_stats' => $bookingStats,
                'recent_events' => $recentEvents,
                'recent_bookings' => $recentBookings,
                'revenue_by_month' => $revenueByMonth,
            ]
        ]);
    }

    public function attendee(Request $request)
    {
        $userId = $request->user()->id;

        $bookingStats = [
            'total_bookings' => Booking::where('user_id', $userId)->count(),
            'confirmed_bookings' => Booking::where('user_id', $userId)->confirmed()->count(),
            'pending_bookings' => Booking::where('user_id', $userId)->pending()->count(),
            'cancelled_bookings' => Booking::where('user_id', $userId)->cancelled()->count(),
        ];

        $recentBookings = Booking::where('user_id', $userId)
                               ->with(['event.organizer', 'event.categories'])
                               ->orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get();

        $upcomingEvents = Booking::where('user_id', $userId)
                               ->confirmed()
                               ->whereHas('event', function ($query) {
                                   $query->where('start_date', '>', now());
                               })
                               ->with(['event.organizer', 'event.categories'])
                               ->orderBy('start_date', 'asc')
                               ->limit(5)
                               ->get();

        $pastEvents = Booking::where('user_id', $userId)
                           ->confirmed()
                           ->whereHas('event', function ($query) {
                               $query->where('end_date', '<', now());
                           })
                           ->with(['event.organizer', 'event.categories'])
                           ->orderBy('end_date', 'desc')
                           ->limit(5)
                           ->get();

        $reviewsWritten = Review::where('user_id', $userId)
                              ->with(['event'])
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'booking_stats' => $bookingStats,
                'recent_bookings' => $recentBookings,
                'upcoming_events' => $upcomingEvents,
                'past_events' => $pastEvents,
                'reviews_written' => $reviewsWritten,
            ]
        ]);
    }
}
