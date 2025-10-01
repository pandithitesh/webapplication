<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'categories', 'reviews'])
                     ->published()
                     ->upcoming();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        if ($request->has('city')) {
            $query->byCity($request->get('city'));
        }

        if ($request->has('min_price') || $request->has('max_price')) {
            $minPrice = $request->get('min_price', 0);
            $maxPrice = $request->get('max_price', 999999);
            $query->byPriceRange($minPrice, $maxPrice);
        }

        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->get('start_date'));
        }
        if ($request->has('end_date')) {
            $query->where('start_date', '<=', $request->get('end_date'));
        }

        $sortBy = $request->get('sort_by', 'start_date');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['title', 'start_date', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        if ($request->get('featured_first', false)) {
            $query->orderBy('is_featured', 'desc');
        }

        $perPage = $request->get('per_page', 12);
        $events = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    public function show($slug)
    {
        $event = Event::with(['organizer', 'categories', 'reviews.user'])
                     ->where('slug', $slug)
                     ->published()
                     ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'start_date' => ['required', 'date', 'after:now'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'registration_deadline' => ['required', 'date', 'before:start_date'],
            'max_attendees' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_featured' => ['boolean'],
            'requires_approval' => ['boolean'],
            'cancellation_policy' => ['nullable', 'string'],
            'refund_policy' => ['nullable', 'string'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $eventData = $request->except(['category_ids', 'images']);
        $eventData['organizer_id'] = $request->user()->id;
        $eventData['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('events', 'public');
            }
            $eventData['images'] = $imagePaths;
        }

        $event = Event::create($eventData);

        $event->categories()->attach($request->category_ids);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event->load(['organizer', 'categories'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', $request->user()->id)
                     ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'venue' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:100'],
            'state' => ['sometimes', 'string', 'max:100'],
            'country' => ['sometimes', 'string', 'max:100'],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'start_date' => ['sometimes', 'date', 'after:now'],
            'end_date' => ['sometimes', 'date', 'after:start_date'],
            'registration_deadline' => ['sometimes', 'date', 'before:start_date'],
            'max_attendees' => ['sometimes', 'integer', 'min:1'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_featured' => ['boolean'],
            'requires_approval' => ['boolean'],
            'cancellation_policy' => ['nullable', 'string'],
            'refund_policy' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:draft,published,cancelled'],
            'category_ids' => ['sometimes', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $eventData = $request->except(['category_ids', 'images']);

        if ($request->hasFile('image')) {
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('events', 'public');
            }
            $eventData['images'] = $imagePaths;
        }

        $event->update($eventData);

        if ($request->has('category_ids')) {
            $event->categories()->sync($request->category_ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event->load(['organizer', 'categories'])
        ]);
    }

    public function destroy($id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', request()->user()->id)
                     ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found or unauthorized'
            ], 404);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    public function featured()
    {
        $events = Event::with(['organizer', 'categories'])
                      ->published()
                      ->featured()
                      ->upcoming()
                      ->limit(6)
                      ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    public function byOrganizer(Request $request, $organizerId)
    {
        $query = Event::with(['organizer', 'categories'])
                     ->where('organizer_id', $organizerId)
                     ->published();

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $perPage = $request->get('per_page', 12);
        $events = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}
