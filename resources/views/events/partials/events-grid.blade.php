@if($events->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($events as $event)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-4xl"></i>
                </div>
            @endif
            
            <div class="p-6">
                <!-- Event Status -->
                <div class="flex items-center mb-2">
                    @if($event->is_featured)
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2">
                            Featured
                        </span>
                    @endif
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($event->description, 120) }}</p>
                
                <!-- Event Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                        <span>{{ $event->venue }}, {{ $event->city }}</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-calendar mr-2 w-4"></i>
                        <span>{{ $event->start_date->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-user mr-2 w-4"></i>
                        <span>{{ $event->organizer->name }}</span>
                    </div>
                </div>
                
                <!-- Categories -->
                @if($event->categories->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($event->categories->take(3) as $category)
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
                
                <!-- Price and Action -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-bold text-blue-600">
                            @if($event->price > 0)
                                ${{ number_format($event->price, 2) }}
                            @else
                                Free
                            @endif
                        </span>
                        @if($event->max_attendees > 0)
                            <div class="text-sm text-gray-500">
                                {{ $event->available_spots }} spots left
                            </div>
                        @endif
                    </div>
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
        {{ $events->links() }}
    </div>
@else
    <!-- No Events Found -->
    <div class="text-center py-12">
        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Found</h3>
        <p class="text-gray-600 mb-6">Try adjusting your search criteria or check back later for new events.</p>
        <a href="{{ route('events.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
            View All Events
        </a>
    </div>
@endif
