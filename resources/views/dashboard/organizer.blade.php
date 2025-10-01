@extends('layouts.app')

@section('title', 'Organizer Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Organizer Dashboard</h1>
        <p class="text-gray-600">Manage your events and track your performance</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $eventStats['total_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Published Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $eventStats['published_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Draft Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $eventStats['draft_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $eventStats['upcoming_events'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $bookingStats['total_bookings'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Confirmed Bookings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $bookingStats['confirmed_bookings'] }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($bookingStats['total_revenue'], 2) }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Report (Raw SQL) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Events Report</h2>
            <span class="text-sm text-gray-500">Generated using raw SQL query</span>
        </div>
        
        @if(isset($eventsReport) && $eventsReport && count($eventsReport) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Bookings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining Spots</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($eventsReport as $event)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $event->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $event->total_capacity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $event->current_bookings }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($event->remaining_spots > 0) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $event->remaining_spots }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-chart-bar text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Found</h3>
                <p class="text-gray-500">Create your first event to see the report</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Events -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Events</h2>
                <a href="{{ route('organizer.events.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
            
            @if($recentEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($recentEvents as $event)
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="h-12 w-12 object-cover rounded-lg mr-4">
                            @else
                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $event->start_date->format('M d, Y') }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($event->status === 'published') bg-green-100 text-green-800
                                        @elseif($event->status === 'draft') bg-yellow-100 text-yellow-800
                                        @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <a href="{{ route('events.show', $event->slug) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Yet</h3>
                    <p class="text-gray-500 mb-4">Create your first event to get started</p>
                    <a href="{{ route('organizer.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Create Event
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Bookings</h2>
                <a href="{{ route('organizer.bookings.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
            
            @if($recentBookings->count() > 0)
                <div class="space-y-4">
                    @foreach($recentBookings as $booking)
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white font-semibold text-sm">{{ substr($booking->user->name, 0, 1) }}</span>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $booking->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $booking->event->title }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->ticket_quantity }} {{ $booking->ticket_quantity == 1 ? 'ticket' : 'tickets' }}</p>
                            </div>
                            
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($booking->total_amount, 2) }}</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-ticket-alt text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Bookings Yet</h3>
                    <p class="text-gray-500">Bookings will appear here once people start registering for your events</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('organizer.events.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-plus text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Create New Event</h3>
                    <p class="text-sm text-gray-500">Add a new event to your portfolio</p>
                </div>
            </a>

            <a href="{{ route('organizer.events.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Manage Events</h3>
                    <p class="text-sm text-gray-500">Edit and update your events</p>
                </div>
            </a>

            <a href="{{ route('organizer.bookings.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">View Bookings</h3>
                    <p class="text-sm text-gray-500">Manage event registrations</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
