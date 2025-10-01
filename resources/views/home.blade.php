@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">Discover Amazing Events</h1>
        <p class="hero-subtitle">Join thousands of attendees at exciting events happening around you</p>
    </div>

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_events'] ?? 0 }}</div>
            <div class="stat-label">Total Events</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_attendees'] ?? 0 }}</div>
            <div class="stat-label">Active Attendees</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_organizers'] ?? 0 }}</div>
            <div class="stat-label">Event Organizers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_cities'] ?? 0 }}</div>
            <div class="stat-label">Cities Covered</div>
        </div>
    </div>

    <!-- Personalized Recommendations Section -->
    @auth
        @if(auth()->user()->isAttendee() && $recommendations->count() > 0)
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <i class="fas fa-magic text-indigo-600 text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Personalized Recommendations</h2>
                </div>
                <p class="text-gray-600 mb-6">Events we think you'll love based on your preferences</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($recommendations as $event)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-heart text-red-500 text-xs mr-1"></i>
                                    <span class="text-xs text-indigo-600 font-semibold">For You</span>
                                </div>
                                
                                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">{{ $event->title }}</h3>
                                
                                <div class="space-y-1 mb-3">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1 w-3"></i>
                                        <span>{{ $event->city }}</span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1 w-3"></i>
                                        <span>{{ $event->start_date->format('M d') }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-indigo-600">
                                        @if($event->price > 0)
                                            ${{ number_format($event->price, 0) }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                    <a href="{{ route('events.show', $event->slug) }}" class="bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700 transition duration-300">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    <!-- Events Section -->
    <div class="page-header">
        <h2 class="page-title">Upcoming Events</h2>
        <p class="page-subtitle">Don't miss out on these amazing experiences</p>
    </div>
    
    @if($upcomingEvents->count() > 0)
        <div class="event-grid">
            @foreach($upcomingEvents as $event)
                <div class="event-card">
                    <h3 class="event-title">
                        <a href="{{ route('events.show', $event->slug) }}">
                            {{ $event->title }}
                        </a>
                    </h3>
                    <p class="event-detail">
                        <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; color: #9ca3af;"></i>
                        {{ $event->start_date->format('M d, Y') }} at {{ $event->start_date->format('h:i A') }}
                    </p>
                    <p class="event-detail">
                        <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; color: #9ca3af;"></i>
                        {{ $event->venue }}, {{ $event->city }}
                    </p>
                    <p class="event-detail">
                        <i class="fas fa-users" style="margin-right: 0.5rem; color: #9ca3af;"></i>
                        {{ $event->available_spots }} spots available
                    </p>
                    <p class="event-detail" style="font-weight: 600; color: #2563eb; margin-top: 1rem; font-size: 1.125rem;">
                        <i class="fas fa-ticket-alt" style="margin-right: 0.5rem;"></i>
                        @if($event->price > 0)
                            ${{ number_format($event->price, 2) }}
                        @else
                            Free
                        @endif
                    </p>
                </div>
            @endforeach
        </div>
        
        <div style="margin-top: 3rem; text-align: center;">
            {{ $upcomingEvents->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <i class="fas fa-calendar-times" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
            <p style="color: #6b7280; font-size: 1.125rem;">No upcoming events available at the moment.</p>
            <p style="color: #9ca3af; margin-top: 0.5rem;">Check back soon for new events!</p>
        </div>
    @endif
</div>
@endsection