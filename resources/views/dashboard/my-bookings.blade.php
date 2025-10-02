@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <p class="text-gray-600">View and manage your event bookings</p>
    </div>

    @if($bookings->count() > 0)
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-ticket-alt text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $bookings->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Confirmed</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $bookings->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Spent</p>
                        <p class="text-lg font-semibold text-gray-900">${{ number_format($bookings->where('status', 'confirmed')->sum('total_amount'), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm font-medium text-gray-700">Filter by status:</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('attendee.bookings.index') }}" 
                       class="px-3 py-1 text-sm rounded-full border {{ request('status') == '' ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }}">
                        All ({{ $bookings->total() }})
                    </a>
                    <a href="{{ route('attendee.bookings.index', ['status' => 'confirmed']) }}" 
                       class="px-3 py-1 text-sm rounded-full border {{ request('status') == 'confirmed' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }}">
                        Confirmed ({{ $bookings->where('status', 'confirmed')->count() }})
                    </a>
                    <a href="{{ route('attendee.bookings.index', ['status' => 'pending']) }}" 
                       class="px-3 py-1 text-sm rounded-full border {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }}">
                        Pending ({{ $bookings->where('status', 'pending')->count() }})
                    </a>
                    <a href="{{ route('attendee.bookings.index', ['status' => 'cancelled']) }}" 
                       class="px-3 py-1 text-sm rounded-full border {{ request('status') == 'cancelled' ? 'bg-red-100 text-red-800 border-red-200' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }}">
                        Cancelled ({{ $bookings->where('status', 'cancelled')->count() }})
                    </a>
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event Details
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking Info
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    @if($booking->event->image)
                                        <img class="h-16 w-16 rounded-xl object-cover mr-4 shadow-sm" 
                                             src="{{ asset('storage/' . $booking->event->image) }}" 
                                             alt="{{ $booking->event->title }}">
                                    @else
                                        <div class="h-16 w-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                                            <a href="{{ route('events.show', $booking->event->slug) }}" 
                                               class="hover:text-blue-600 transition-colors duration-200">
                                                {{ $booking->event->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            By {{ $booking->event->organizer->name }}
                                        </p>
                                        @if($booking->event->categories->count() > 0)
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @foreach($booking->event->categories->take(2) as $category)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                                @if($booking->event->categories->count() > 2)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500">
                                                        +{{ $booking->event->categories->count() - 2 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->event->start_date->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $booking->event->start_date->format('g:i A') }} - {{ $booking->event->end_date->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->event->venue }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->event->city }}, {{ $booking->event->state }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->ticket_quantity }} ticket{{ $booking->ticket_quantity > 1 ? 's' : '' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($booking->total_amount > 0)
                                        ${{ number_format($booking->total_amount, 2) }}
                                    @else
                                        Free
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    Booked {{ $booking->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <div class="w-2 h-2 rounded-full mr-2
                                        @if($booking->status === 'confirmed') bg-green-400
                                        @elseif($booking->status === 'pending') bg-yellow-400
                                        @elseif($booking->status === 'cancelled') bg-red-400
                                        @else bg-gray-400 @endif"></div>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('events.show', $booking->event->slug) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200" 
                                       title="View Event">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                    @if($booking->canBeCancelled())
                                        <form method="POST" 
                                              action="{{ route('attendee.bookings.cancel', $booking->id) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white hover:bg-red-50 transition-colors duration-200" 
                                                    title="Cancel Booking">
                                                <i class="fas fa-times mr-1"></i>
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-4">
                        <!-- Event Header -->
                        <div class="flex items-start space-x-4">
                            @if($booking->event->image)
                                <img class="h-20 w-20 rounded-lg object-cover shadow-sm flex-shrink-0" 
                                     src="{{ asset('storage/' . $booking->event->image) }}" 
                                     alt="{{ $booking->event->title }}">
                            @else
                                <div class="h-20 w-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 leading-tight">
                                    <a href="{{ route('events.show', $booking->event->slug) }}" 
                                       class="hover:text-blue-600 transition-colors duration-200">
                                        {{ $booking->event->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    By {{ $booking->event->organizer->name }}
                                </p>
                                
                                <!-- Categories -->
                                @if($booking->event->categories->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($booking->event->categories->take(2) as $category)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium flex-shrink-0
                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <!-- Event Details -->
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->event->start_date->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $booking->event->start_date->format('g:i A') }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->event->venue }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->event->city }}</div>
                            </div>
                        </div>

                        <!-- Booking Info -->
                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->ticket_quantity }} ticket{{ $booking->ticket_quantity > 1 ? 's' : '' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($booking->total_amount > 0)
                                        ${{ number_format($booking->total_amount, 2) }}
                                    @else
                                        Free
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('events.show', $booking->event->slug) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>
                                    View
                                </a>
                                @if($booking->canBeCancelled())
                                    <form method="POST" 
                                          action="{{ route('attendee.bookings.cancel', $booking->id) }}" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                                            <i class="fas fa-times mr-1"></i>
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @else
        <!-- No Bookings -->
        <div class="text-center py-16">
            <div class="bg-gradient-to-br from-blue-100 to-purple-100 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-ticket-alt text-blue-600 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-3">No Bookings Yet</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Start exploring amazing events and make your first booking to see them here</p>
            <a href="{{ route('events.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                <i class="fas fa-search mr-2"></i>
                Browse Events
            </a>
        </div>
    @endif
</div>
@endsection
