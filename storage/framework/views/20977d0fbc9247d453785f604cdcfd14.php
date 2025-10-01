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
<?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/events/partials/events-grid.blade.php ENDPATH**/ ?>