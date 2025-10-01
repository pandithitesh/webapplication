<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->bookings()
                     ->with(['event.organizer', 'event.categories']);

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->get('payment_status'));
        }

        $perPage = $request->get('per_page', 12);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => ['required', 'exists:events,id'],
            'ticket_quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
            'attendee_info' => ['nullable', 'array'],
            'attendee_info.*.name' => ['required_with:attendee_info', 'string', 'max:255'],
            'attendee_info.*.email' => ['required_with:attendee_info', 'email', 'max:255'],
            'attendee_info.*.phone' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $event = Event::findOrFail($request->event_id);

        if (!$event->isRegistrationOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration is not open for this event'
            ], 400);
        }

        if ($event->available_spots < $request->ticket_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough spots available. Only ' . $event->available_spots . ' spots left.'
            ], 400);
        }

        $existingBooking = $request->user()->bookings()
            ->where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a booking for this event'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $totalAmount = $event->price * $request->ticket_quantity;

            $booking = Booking::create([
                'event_id' => $event->id,
                'user_id' => $request->user()->id,
                'ticket_quantity' => $request->ticket_quantity,
                'total_amount' => $totalAmount,
                'currency' => $event->currency,
                'special_requirements' => $request->special_requirements,
                'attendee_info' => $request->attendee_info,
                'status' => $event->requires_approval ? 'pending' : 'confirmed',
                'payment_status' => $event->requires_approval ? 'pending' : 'paid',
                'payment_date' => $event->requires_approval ? null : now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $event->requires_approval 
                    ? 'Booking created successfully. Awaiting approval.' 
                    : 'Booking confirmed successfully.',
                'data' => $booking->load(['event.organizer'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.'
            ], 500);
        }
    }

    public function show($id)
    {
        $booking = $request->user()->bookings()
                     ->with(['event.organizer', 'event.categories'])
                     ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $booking = $request->user()->bookings()->findOrFail($id);

        if (!$booking->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'This booking cannot be cancelled'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => ['nullable', 'string', 'max:500']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking->cancel($request->cancellation_reason);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => $booking->fresh()
        ]);
    }

    public function statistics(Request $request)
    {
        $organizerId = $request->user()->id;

        $stats = [
            'total_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->count(),
            
            'confirmed_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->confirmed()->count(),
            
            'pending_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->pending()->count(),
            
            'cancelled_bookings' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->cancelled()->count(),
            
            'total_revenue' => Booking::whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })->confirmed()->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function organizerBookings(Request $request)
    {
        $query = Booking::with(['user', 'event'])
                     ->whereHas('event', function ($q) use ($request) {
                         $q->where('organizer_id', $request->user()->id);
                     });

        if ($request->has('event_id')) {
            $query->where('event_id', $request->get('event_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->get('payment_status'));
        }

        $perPage = $request->get('per_page', 12);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::whereHas('event', function ($query) use ($request) {
            $query->where('organizer_id', $request->user()->id);
        })->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:confirmed,cancelled'],
            'reason' => ['nullable', 'string', 'max:500']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->status === 'confirmed') {
            $booking->confirm();
        } else {
            $booking->cancel($request->reason);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'data' => $booking->fresh()
        ]);
    }
}
