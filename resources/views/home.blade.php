@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Discover Amazing Events
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Find, book, and create unforgettable experiences
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    <i class="fas fa-search mr-2"></i>Browse Events
                </a>
                @auth
                    @if(auth()->user()->isOrganizer())
                        <a href="{{ route('organizer.events.create') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Create Event
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i>Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['total_events']) }}</h3>
                <p class="text-gray-600">Total Events</p>
            </div>
            
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['total_attendees']) }}</h3>
                <p class="text-gray-600">Total Attendees</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-tie text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['total_organizers']) }}</h3>
                <p class="text-gray-600">Organizers</p>
            </div>
            
            <div class="text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($stats['cities']) }}</h3>
                <p class="text-gray-600">Cities</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Events Section -->
@if($featuredEvents->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Events</h2>
            <p class="text-gray-600">Handpicked events you don't want to miss</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredEvents as $event)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-4xl"></i>
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            Featured
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $event->city }}, {{ $event->state }}</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ $event->start_date->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-blue-600">
                            @if($event->price > 0)
                                ${{ number_format($event->price, 2) }}
                            @else
                                Free
                            @endif
                        </span>
                        <a href="{{ route('events.show', $event->slug) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('events.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                View All Events
            </a>
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Browse by Category</h2>
            <p class="text-gray-600">Find events that match your interests</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('events.index', ['category' => $category->slug]) }}" class="group">
                <div class="bg-gray-50 rounded-lg p-6 text-center hover:bg-gray-100 transition duration-300">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: {{ $category->color }}20;">
                        <i class="{{ $category->icon ?? 'fas fa-tag' }}" style="color: {{ $category->color }};"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">{{ $category->name }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Upcoming Events Section -->
@if($upcomingEvents->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
            <p class="text-gray-600">Discover what's happening around you</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($upcomingEvents as $event)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-4xl"></i>
                    </div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        <a href="{{ route('events.show', $event->slug) }}" class="hover:text-blue-600 transition duration-300">
                            {{ $event->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($event->description, 80) }}</p>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ $event->start_date->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="fas fa-clock mr-2"></i>
                        <span>{{ $event->start_date->format('g:i A') }}</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $event->venue }}, {{ $event->city }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold text-blue-600">
                            @if($event->price > 0)
                                ${{ number_format($event->price, 2) }}
                            @else
                                Free
                            @endif
                        </span>
                        <a href="{{ route('events.show', $event->slug) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $upcomingEvents->links() }}
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('events.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                View All Events
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl mb-8 text-blue-100">Join thousands of event organizers and attendees</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Create Account
                </a>
                <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    Sign In
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection
