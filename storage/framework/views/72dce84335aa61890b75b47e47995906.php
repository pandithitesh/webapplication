<?php $__env->startSection('title', 'Events'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Discover Events</h1>
        <p class="text-gray-600">Find amazing events happening around you</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <form method="GET" action="<?php echo e(route('events.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Search events..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->slug); ?>" <?php echo e(request('category') == $category->slug ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" 
                           name="city" 
                           id="city" 
                           value="<?php echo e(request('city')); ?>"
                           placeholder="Enter city..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="start_date" <?php echo e(request('sort') == 'start_date' ? 'selected' : ''); ?>>Date</option>
                        <option value="title" <?php echo e(request('sort') == 'title' ? 'selected' : ''); ?>>Title</option>
                        <option value="price" <?php echo e(request('sort') == 'price' ? 'selected' : ''); ?>>Price</option>
                        <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Newest</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="<?php echo e(route('events.index')); ?>" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition duration-300">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>

                <div class="text-sm text-gray-500">
                    <?php echo e($events->total()); ?> events found
                </div>
            </div>
        </form>
    </div>

    <!-- Recommendations Section (for logged-in attendees) -->
    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isAttendee() && $recommendations->count() > 0): ?>
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <i class="fas fa-lightbulb text-purple-600 text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Recommended for You</h2>
                </div>
                <p class="text-gray-600 mb-6">Based on your booking history and preferences</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 border-l-4 border-purple-500">
                            <?php if($event->image): ?>
                                <img src="<?php echo e(asset('storage/' . $event->image)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-40 object-cover">
                            <?php else: ?>
                                <div class="w-full h-40 bg-gradient-to-r from-purple-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white text-3xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-star text-yellow-500 text-sm mr-1"></i>
                                    <span class="text-xs text-purple-600 font-semibold">Recommended</span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2"><?php echo e($event->title); ?></h3>
                                
                                <div class="space-y-1 mb-3">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-2 w-3"></i>
                                        <span><?php echo e($event->city); ?></span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-2 w-3"></i>
                                        <span><?php echo e($event->start_date->format('M d, Y')); ?></span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-purple-600">
                                        <?php if($event->price > 0): ?>
                                            $<?php echo e(number_format($event->price, 2)); ?>

                                        <?php else: ?>
                                            Free
                                        <?php endif; ?>
                                    </span>
                                    <a href="<?php echo e(route('events.show', $event->slug)); ?>" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition duration-300">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Events Grid -->
    <div id="events-container">
        <?php echo $__env->make('events.partials.events-grid', ['events' => $events], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const eventsContainer = document.getElementById('events-container');
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i><p class="mt-2 text-gray-600">Loading events...</p></div>';
    loadingIndicator.style.display = 'none';
    eventsContainer.parentNode.insertBefore(loadingIndicator, eventsContainer);

    // Handle form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading indicator
        loadingIndicator.style.display = 'block';
        eventsContainer.style.opacity = '0.5';
        
        // Get form data
        const formData = new FormData(form);
        
        // Make AJAX request
        fetch('<?php echo e(route("events.ajax-filter")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update events container
            eventsContainer.innerHTML = data.html;
            
            // Update event count
            const eventCountElement = document.querySelector('.text-sm.text-gray-500');
            if (eventCountElement) {
                eventCountElement.textContent = data.total + ' events found';
            }
            
            // Hide loading indicator
            loadingIndicator.style.display = 'none';
            eventsContainer.style.opacity = '1';
            
            // Scroll to events
            eventsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.style.display = 'none';
            eventsContainer.style.opacity = '1';
            alert('An error occurred while filtering events. Please try again.');
        });
    });

    // Auto-submit on category change
    const categorySelect = document.getElementById('category');
    const sortSelect = document.getElementById('sort');
    
    categorySelect.addEventListener('change', function() {
        form.dispatchEvent(new Event('submit'));
    });
    
    sortSelect.addEventListener('change', function() {
        form.dispatchEvent(new Event('submit'));
    });

    // Auto-submit on search with debounce
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            form.dispatchEvent(new Event('submit'));
        }, 500); // Wait 500ms after user stops typing
    });

    // Auto-submit on city change with debounce
    const cityInput = document.getElementById('city');
    let cityTimeout;
    
    cityInput.addEventListener('input', function() {
        clearTimeout(cityTimeout);
        cityTimeout = setTimeout(function() {
            form.dispatchEvent(new Event('submit'));
        }, 500);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/events/index.blade.php ENDPATH**/ ?>