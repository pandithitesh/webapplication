<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOrganizer()) {
            return $this->organizer();
        } else {
            return $this->attendee();
        }
    }

    public function organizer()
    {
        $organizerId = auth()->id();

        $eventStats = [
            'total_events' => Event::where('organizer_id', $organizerId)->count(),
            'published_events' => Event::where('organizer_id', $organizerId)->published()->count(),
            'draft_events' => Event::where('organizer_id', $organizerId)->where('status', 'draft')->count(),
            'upcoming_events' => Event::where('organizer_id', $organizerId)->published()->upcoming()->count(),
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

        $eventsReport = DB::select("
            SELECT 
                e.id,
                e.title,
                e.start_date,
                e.max_attendees,
                COALESCE(booking_stats.current_bookings, 0) as current_bookings,
                (e.max_attendees - COALESCE(booking_stats.current_bookings, 0)) as remaining_spots
            FROM events e
            LEFT JOIN (
                SELECT 
                    event_id,
                    SUM(ticket_quantity) as current_bookings
                FROM bookings 
                WHERE status IN ('confirmed', 'pending')
                GROUP BY event_id
            ) booking_stats ON e.id = booking_stats.event_id
            WHERE e.organizer_id = ?
            ORDER BY e.start_date ASC
        ", [$organizerId]);

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

        return view('dashboard.organizer', compact('eventStats', 'bookingStats', 'eventsReport', 'recentEvents', 'recentBookings'));
    }

    public function attendee()
    {
        $userId = auth()->id();

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
                               ->where('bookings.status', 'confirmed')
                               ->whereHas('event', function ($query) {
                                   $query->where('start_date', '>', now());
                               })
                               ->with(['event.organizer', 'event.categories'])
                               ->join('events', 'bookings.event_id', '=', 'events.id')
                               ->orderBy('events.start_date', 'asc')
                               ->limit(5)
                               ->get();

        return view('dashboard.attendee', compact('bookingStats', 'recentBookings', 'upcomingEvents'));
    }

    public function bookings(Request $request)
    {
        $organizerId = auth()->id();

        $query = Booking::whereHas('event', function ($q) use ($organizerId) {
            $q->where('organizer_id', $organizerId);
        })
        ->with(['user', 'event']);

        if ($request->has('event_id')) {
            $query->where('event_id', $request->get('event_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboard.bookings', compact('bookings'));
    }

    public function myBookings(Request $request)
    {
        $query = auth()->user()->bookings()
                     ->with(['event.organizer', 'event.categories']);

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboard.my-bookings', compact('bookings'));
    }

    public function createBooking(Request $request)
    {
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'ticket_quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
        ]);

        $event = Event::findOrFail($request->event_id);

        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'Registration is not open for this event');
        }

        $currentBookings = $event->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->sum('ticket_quantity');
        
        $availableSpots = $event->max_attendees - $currentBookings;
        
        if ($availableSpots < $request->ticket_quantity) {
            return back()->with('error', "Not enough spots available. Only {$availableSpots} spots left.");
        }

        $existingBooking = auth()->user()->bookings()
            ->where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'You already have a booking for this event');
        }

        $totalAmount = $event->price * $request->ticket_quantity;

        $booking = Booking::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'ticket_quantity' => $request->ticket_quantity,
            'total_amount' => $totalAmount,
            'currency' => $event->currency,
            'special_requirements' => $request->special_requirements,
            'status' => $event->requires_approval ? 'pending' : 'confirmed',
            'payment_status' => $event->requires_approval ? 'pending' : 'paid',
            'payment_date' => $event->requires_approval ? null : now(),
        ]);

        $message = $event->requires_approval 
            ? 'Booking created successfully. Awaiting approval.' 
            : 'Booking confirmed successfully.';

        return back()->with('success', $message);
    }

    public function cancelBooking($id)
    {
        $booking = auth()->user()->bookings()->findOrFail($id);

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled');
        }

        $booking->cancel();

        return back()->with('success', 'Booking cancelled successfully');
    }
}
