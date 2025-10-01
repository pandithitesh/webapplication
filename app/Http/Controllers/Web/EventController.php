<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Services\EventRecommendationService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'categories'])
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

        $sortBy = $request->get('sort', 'start_date');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['title', 'start_date', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $events = $query->paginate(8);
        $categories = Category::active()->ordered()->get();

        $recommendations = collect();
        
        if (auth()->check() && auth()->user()->isAttendee()) {
            $recommendationService = new EventRecommendationService();
            $recommendations = $recommendationService->getRecommendationsForUser(auth()->user(), 6);
        }

        return view('events.index', compact('events', 'categories', 'recommendations'));
    }

    public function ajaxFilter(Request $request)
    {
        $query = Event::with(['organizer', 'categories'])
            ->published()
            ->upcoming();

        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->get('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        if ($request->has('city') && $request->get('city')) {
            $query->byCity($request->get('city'));
        }

        $sortBy = $request->get('sort', 'start_date');
        if (in_array($sortBy, ['title', 'start_date', 'price', 'created_at'])) {
            $query->orderBy($sortBy, 'asc');
        }

        $events = $query->paginate(8);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('events.partials.events-grid', compact('events'))->render(),
                'total' => $events->total()
            ]);
        }

        return view('events.index', compact('events', 'categories'));
    }

    public function getRecommendationReasons($id)
    {
        if (!auth()->check() || !auth()->user()->isAttendee()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($id);
        $recommendationService = new EventRecommendationService();
        $reasons = $recommendationService->getRecommendationReasons($event, auth()->user());

        return response()->json(['reasons' => $reasons]);
    }

    public function show($slug)
    {
        $event = Event::with(['organizer', 'categories', 'reviews.user'])
                     ->where('slug', $slug)
                     ->published()
                     ->firstOrFail();

        $relatedEvents = Event::with(['organizer', 'categories'])
                            ->published()
                            ->upcoming()
                            ->where('id', '!=', $event->id)
                            ->whereHas('categories', function ($query) use ($event) {
                                $query->whereIn('categories.id', $event->categories->pluck('id'));
                            })
                            ->limit(4)
                            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'start_date' => ['required', 'date', 'after:now'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'registration_deadline' => ['required', 'date', 'after:now', 'before:start_date'],
            'max_attendees' => ['required', 'integer', 'min:1', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $eventData = $request->except(['category_ids']);
        $eventData['organizer_id'] = $request->user()->id;
        $eventData['slug'] = \Illuminate\Support\Str::slug($request->title);
        $eventData['currency'] = 'USD';
        $eventData['status'] = 'draft';

        if ($request->hasFile('image')) {
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($eventData);
        $event->categories()->attach($request->category_ids);

        return redirect()->route('organizer.events.index')
                        ->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', request()->user()->id)
                     ->firstOrFail();
        
        $categories = Category::active()->ordered()->get();
        
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', $request->user()->id)
                     ->firstOrFail();

        $request->validate([
            'title' => ['sometimes', 'string', 'max:100'],
            'description' => ['sometimes', 'string'],
            'venue' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:100'],
            'state' => ['sometimes', 'string', 'max:100'],
            'country' => ['sometimes', 'string', 'max:100'],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'start_date' => ['sometimes', 'date', 'after:now'],
            'end_date' => ['sometimes', 'date', 'after:start_date'],
            'registration_deadline' => ['sometimes', 'date', 'before:start_date'],
            'max_attendees' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['sometimes', 'in:draft,published,cancelled'],
            'category_ids' => ['sometimes', 'array', 'min:1'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        $eventData = $request->except(['category_ids']);

        if ($request->hasFile('image')) {
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($eventData);

        if ($request->has('category_ids')) {
            $event->categories()->sync($request->category_ids);
        }

        return redirect()->route('organizer.events.index')
                        ->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', request()->user()->id)
                     ->firstOrFail();

        $bookingCount = $event->bookings()->count();
        
        if ($bookingCount > 0) {
            return redirect()->back()
                           ->with('error', "Cannot delete event. This event has {$bookingCount} booking(s). Please cancel all bookings before deleting the event.");
        }

        $event->delete();

        return redirect()->route('organizer.events.index')
                        ->with('success', 'Event deleted successfully.');
    }

    public function manage(Request $request)
    {
        $query = Event::with(['categories'])
                     ->where('organizer_id', $request->user()->id);

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('events.manage', compact('events'));
    }
}
