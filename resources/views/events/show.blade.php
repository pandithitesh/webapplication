@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <div class="event-card" style="margin-bottom: 2rem;">
        <h1 class="text-3xl font-bold mb-4">{{ $event->title }}</h1>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded">
                @endif
            </div>
            
            <div>
                <div class="space-y-3 mb-6">
                    <p><strong>Date:</strong> {{ $event->start_date->format('M d, Y') }}</p>
                    <p><strong>Time:</strong> {{ $event->start_date->format('h:i A') }}</p>
                    <p><strong>Location:</strong> {{ $event->venue }}, {{ $event->city }}</p>
                    <p><strong>Capacity:</strong> {{ $event->max_attendees }} attendees</p>
                    <p><strong>Available:</strong> {{ $event->available_spots }} spots left</p>
                    <p><strong>Price:</strong> 
                        @if($event->price > 0)
                            ${{ number_format($event->price, 2) }}
                        @else
                            Free
                        @endif
                    </p>
                    <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
                </div>

                @if($event->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p>{{ $event->description }}</p>
                    </div>
                @endif

                @auth
                    @if(auth()->user()->isAttendee() && $event->isRegistrationOpen() && !$event->isSoldOut())
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Book This Event</h3>
                            <form action="{{ route('attendee.bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <div class="flex items-center space-x-4">
                                    <label class="font-semibold">Tickets:</label>
                                    <select name="ticket_quantity" class="border rounded px-3 py-2">
                                        @for($i = 1; $i <= min(5, $event->available_spots); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                        Book Now
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if(auth()->user()->isOrganizer() && auth()->user()->id === $event->organizer_id)
                        <div class="flex space-x-4">
                            <a href="{{ route('organizer.events.edit', $event->id) }}" class="bg-green-600 text-white px-4 py-2 rounded">
                                Edit Event
                            </a>
                            <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded" onclick="return confirm('Delete this event?')">
                                    Delete Event
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                @guest
                    <div class="mb-6">
                        <p class="text-gray-600 mb-4">Please login to book this event.</p>
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded">
                            Login to Book
                        </a>
                    </div>
                @endguest
            </div>
        </div>

        @if($event->reviews->count() > 0)
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Reviews</h2>
                <div class="space-y-4">
                    @foreach($event->reviews->take(5) as $review)
                        <div class="border-b pb-4">
                            <div class="flex items-center mb-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}" alt="{{ $review->user->name }}" class="h-10 w-10 rounded-full mr-3">
                                <div>
                                    <div class="font-medium">{{ $review->user->name }}</div>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            @if($review->comment)
                                <p>{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection