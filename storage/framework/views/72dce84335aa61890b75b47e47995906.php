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

    <!-- Events Grid -->
    <?php if($events->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <?php if($event->image): ?>
                    <img src="<?php echo e(asset('storage/' . $event->image)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-4xl"></i>
                    </div>
                <?php endif; ?>
                
                <div class="p-6">
                    <!-- Event Status -->
                    <div class="flex items-center mb-2">
                        <?php if($event->is_featured): ?>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2">
                                Featured
                            </span>
                        <?php endif; ?>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            <?php echo e(ucfirst($event->status)); ?>

                        </span>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($event->title); ?></h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo e(Str::limit($event->description, 120)); ?></p>
                    
                    <!-- Event Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                            <span><?php echo e($event->venue); ?>, <?php echo e($event->city); ?></span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2 w-4"></i>
                            <span><?php echo e($event->start_date->format('M d, Y \a\t g:i A')); ?></span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-user mr-2 w-4"></i>
                            <span><?php echo e($event->organizer->name); ?></span>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <?php if($event->categories->count() > 0): ?>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php $__currentLoopData = $event->categories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                    <?php echo e($category->name); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Price and Action -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-bold text-blue-600">
                                <?php if($event->price > 0): ?>
                                    $<?php echo e(number_format($event->price, 2)); ?>

                                <?php else: ?>
                                    Free
                                <?php endif; ?>
                            </span>
                            <?php if($event->max_attendees > 0): ?>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($event->available_spots); ?> spots left
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('events.show', $event->slug)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            <?php echo e($events->links()); ?>

        </div>
    <?php else: ?>
        <!-- No Events Found -->
        <div class="text-center py-12">
            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your search criteria or check back later for new events.</p>
            <a href="<?php echo e(route('events.index')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                View All Events
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/events/index.blade.php ENDPATH**/ ?>