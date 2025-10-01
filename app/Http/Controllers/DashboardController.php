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

        // Raw SQL query for events report
        $eventsReport = DB::select("
            SELECT 
                e.title as title,
                e.start_date as event_date,
                e.capacity as total_capacity,
                COALESCE(COUNT(b.id), 0) as current_bookings,
                (e.capacity - COALESCE(COUNT(b.id), 0)) as remaining_spots
            FROM events e
            LEFT JOIN bookings b ON e.id = b.event_id AND b.status = 'confirmed'
            WHERE e.organizer_id = ? AND e.deleted_at IS NULL
            GROUP BY e.id, e.title, e.start_date, e.capacity
            ORDER BY e.start_date DESC
        ", [$organizerId]);

        return view('dashboard.organizer', compact('eventStats', 'bookingStats', 'recentEvents', 'recentBookings', 'revenueByMonth', 'eventsReport'));
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
                               ->where('bookings.status', 'confirmed')
                               ->whereHas('event', function ($query) {
                                   $query->where('start_date', '>', now());
                               })
                               ->with(['event.organizer', 'event.categories'])
                               ->join('events', 'bookings.event_id', '=', 'events.id')
                               ->orderBy('events.start_date', 'asc')
                               ->limit(5)
                               ->get();

        $pastEvents = Booking::where('user_id', $userId)
                           ->where('bookings.status', 'confirmed')
                           ->whereHas('event', function ($query) {
                               $query->where('end_date', '<', now());
                           })
                           ->with(['event.organizer', 'event.categories'])
                           ->join('events', 'bookings.event_id', '=', 'events.id')
                           ->orderBy('events.end_date', 'desc')
                           ->limit(5)
                           ->get();

        $reviewsWritten = Review::where('user_id', $userId)
                              ->with(['event'])
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return view('dashboard.attendee', compact('bookingStats', 'recentBookings', 'upcomingEvents', 'pastEvents', 'reviewsWritten'));
    }

    public function bookings(Request $request)
    {
        $organizerId = $request->user()->id;
        
        $bookings = Booking::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        })
        ->with(['user', 'event'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('dashboard.organizer', compact('bookings'));
    }

    public function myBookings(Request $request)
    {
        $userId = $request->user()->id;
        
        $bookings = Booking::where('user_id', $userId)
            ->with(['event.organizer', 'event.categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.attendee', compact('bookings'));
    }

    public function createBooking(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_quantity' => 'required|integer|min:1|max:10',
        ]);

        $userId = $request->user()->id;
        $eventId = $request->event_id;
        $ticketQuantity = $request->ticket_quantity;

        $event = Event::findOrFail($eventId);

        if (Booking::where('user_id', $userId)->where('event_id', $eventId)->exists()) {
            return back()->with('error', 'You have already booked this event.');
        }

        if ($event->available_spots < $ticketQuantity) {
            return back()->with('error', 'Not enough available spots for this event.');
        }

        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'Registration for this event is closed.');
        }

        $totalAmount = $event->price * $ticketQuantity;

        $booking = Booking::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'ticket_quantity' => $ticketQuantity,
            'total_amount' => $totalAmount,
            'booking_reference' => 'BK-' . strtoupper(uniqid()),
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('attendee.bookings.index')->with('success', 'Booking created successfully!');
    }

    public function cancelBooking(Request $request, $id)
    {
        $userId = $request->user()->id;
        
        $booking = Booking::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        if ($booking->status === 'confirmed') {
            return back()->with('error', 'Cannot cancel confirmed booking.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
