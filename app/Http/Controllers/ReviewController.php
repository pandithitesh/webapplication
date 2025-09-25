<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => ['required', 'exists:events,id'],
            'booking_id' => ['required', 'exists:bookings,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::where('id', $request->booking_id)
                         ->where('user_id', $request->user()->id)
                         ->where('event_id', $request->event_id)
                         ->where('status', 'confirmed')
                         ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking or booking not confirmed'
            ], 400);
        }

        $existingReview = Review::where('event_id', $request->event_id)
                               ->where('user_id', $request->user()->id)
                               ->where('booking_id', $request->booking_id)
                               ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this event'
            ], 400);
        }

        $event = Event::findOrFail($request->event_id);
        if ($event->end_date > now()) {
            return response()->json([
                'success' => false,
                'message' => 'Reviews can only be submitted after the event ends'
            ], 400);
        }

        $review = Review::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user()->id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => true, 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $review->load(['user', 'event'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
                       ->where('user_id', $request->user()->id)
                       ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $review->update($request->only(['rating', 'comment']));

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => $review->load(['user', 'event'])
        ]);
    }

    public function destroy($id)
    {
        $review = Review::where('id', $id)
                       ->where('user_id', request()->user()->id)
                       ->firstOrFail();

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }

    public function getEventReviews(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $query = $event->reviews()
                      ->with(['user'])
                      ->verified();

        if ($request->has('rating')) {
            $query->byRating($request->get('rating'));
        }

        if ($request->get('comments_only', false)) {
            $query->withComments();
        }

        $perPage = $request->get('per_page', 10);
        $reviews = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    public function getUserReviews(Request $request)
    {
        $query = $request->user()->reviews()
                     ->with(['event.organizer']);

        $perPage = $request->get('per_page', 10);
        $reviews = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
