<?php $__env->startSection('title', $event->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="<?php echo e(route('events.index')); ?>" class="text-gray-700 hover:text-blue-600">Events</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500"><?php echo e($event->title); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Event Image -->
            <div class="mb-8">
                <?php if($event->image): ?>
                    <img src="<?php echo e(asset('storage/' . $event->image)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-64 md:h-96 object-cover rounded-lg">
                <?php else: ?>
                    <div class="w-full h-64 md:h-96 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-6xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Event Details -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($event->title); ?></h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                By <?php echo e($event->organizer->name); ?>

                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                <?php echo e($event->reviews_count); ?> reviews
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-star mr-2 text-yellow-400"></i>
                                <?php echo e(number_format($event->average_rating, 1)); ?>/5
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            <?php if($event->price > 0): ?>
                                $<?php echo e(number_format($event->price, 2)); ?>

                            <?php else: ?>
                                Free
                            <?php endif; ?>
                        </div>
                        <?php if($event->max_attendees > 0): ?>
                            <div class="text-sm text-gray-500">
                                <?php echo e($event->available_spots); ?> of <?php echo e($event->max_attendees); ?> spots left
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Organizer Actions -->
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isOrganizer() && auth()->user()->id === $event->organizer_id): ?>
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">Event Management</h3>
                            <div class="flex space-x-3">
                                <a href="<?php echo e(route('organizer.events.edit', $event->id)); ?>" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Event
                                </a>
                                <form method="POST" action="<?php echo e(route('organizer.events.destroy', $event->id)); ?>" 
                                      class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 flex items-center">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete Event
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Event Status Badges -->
                <div class="flex items-center space-x-2 mb-6">
                    <?php if($event->is_featured): ?>
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">
                            Featured
                        </span>
                    <?php endif; ?>
                    <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                        <?php echo e(ucfirst($event->status)); ?>

                    </span>
                    <?php if($event->requires_approval): ?>
                        <span class="bg-orange-100 text-orange-800 text-sm font-semibold px-3 py-1 rounded-full">
                            Requires Approval
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Event Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Event</h2>
                    <div class="prose max-w-none">
                        <?php echo nl2br(e($event->description)); ?>

                    </div>
                </div>

                <!-- Event Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-calendar-alt text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Start Date</div>
                                    <div class="text-gray-600"><?php echo e($event->start_date->format('l, F j, Y \a\t g:i A')); ?></div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="fas fa-calendar-check text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">End Date</div>
                                    <div class="text-gray-600"><?php echo e($event->end_date->format('l, F j, Y \a\t g:i A')); ?></div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="fas fa-clock text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Registration Deadline</div>
                                    <div class="text-gray-600"><?php echo e($event->registration_deadline->format('l, F j, Y \a\t g:i A')); ?></div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="fas fa-users text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Capacity</div>
                                    <div class="text-gray-600">
                                        <?php echo e($event->max_attendees); ?> total spots
                                        <?php if($event->max_attendees > 0): ?>
                                            (<?php echo e($event->available_spots); ?> available)
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo e($event->venue); ?></div>
                                    <div class="text-gray-600"><?php echo e($event->address); ?></div>
                                    <div class="text-gray-600"><?php echo e($event->city); ?>, <?php echo e($event->state); ?> <?php echo e($event->postal_code); ?></div>
                                    <div class="text-gray-600"><?php echo e($event->country); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <?php if($event->categories->count() > 0): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $event->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                    <?php echo e($category->name); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tags -->
                <?php if($event->tags && count($event->tags) > 0): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $event->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                    #<?php echo e($tag); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Reviews Section -->
            <?php if($event->reviews->count() > 0): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Reviews</h2>
                    
                    <div class="space-y-6">
                        <?php $__currentLoopData = $event->reviews->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center">
                                        <img src="<?php echo e($review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name)); ?>" 
                                             alt="<?php echo e($review->user->name); ?>" 
                                             class="h-10 w-10 rounded-full mr-3">
                                        <div>
                                            <div class="font-medium text-gray-900"><?php echo e($review->user->name); ?></div>
                                            <div class="flex items-center">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?> text-sm"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo e($review->created_at->format('M d, Y')); ?>

                                    </div>
                                </div>
                                
                                <?php if($review->comment): ?>
                                    <p class="text-gray-700 mt-2"><?php echo e($review->comment); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($event->reviews->count() > 5): ?>
                        <div class="text-center mt-6">
                            <button class="text-blue-600 hover:text-blue-700 font-medium">
                                View All Reviews (<?php echo e($event->reviews->count()); ?>)
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Booking Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8 sticky top-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Book Your Spot</h3>
                
                <?php if($event->isRegistrationOpen()): ?>
                    <?php if($event->available_spots > 0): ?>
                        <form method="POST" action="<?php echo e(route('attendee.bookings.store')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="event_id" value="<?php echo e($event->id); ?>">
                            
                            <div class="mb-4">
                                <label for="ticket_quantity" class="block text-sm font-medium text-gray-700 mb-2">Number of Tickets</label>
                                <select name="ticket_quantity" id="ticket_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php for($i = 1; $i <= min(10, $event->available_spots); $i++): ?>
                                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?> <?php echo e($i == 1 ? 'ticket' : 'tickets'); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-2">Special Requirements (Optional)</label>
                                <textarea name="special_requirements" id="special_requirements" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any special dietary requirements, accessibility needs, etc."></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <div class="flex justify-between items-center py-2 border-t border-gray-200">
                                    <span class="font-medium text-gray-900">Total Amount:</span>
                                    <span class="text-xl font-bold text-blue-600" id="total-amount">
                                        <?php if($event->price > 0): ?>
                                            $<?php echo e(number_format($event->price, 2)); ?>

                                        <?php else: ?>
                                            Free
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if(auth()->guard()->check()): ?>
                                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                                    <?php if($event->requires_approval): ?>
                                        Request Booking
                                    <?php else: ?>
                                        Book Now
                                    <?php endif; ?>
                                </button>
                            <?php else: ?>
                                <div class="text-center">
                                    <p class="text-gray-600 mb-4">Please login to book this event</p>
                                    <a href="<?php echo e(route('login')); ?>" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 inline-block">
                                        Login to Book
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-times-circle text-red-500 text-4xl mb-4"></i>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Sold Out</h4>
                            <p class="text-gray-600">This event is fully booked</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Registration Closed</h4>
                        <p class="text-gray-600">Registration for this event has ended</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Organizer Info -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Organizer</h3>
                <div class="flex items-center mb-4">
                    <img src="<?php echo e($event->organizer->avatar ? asset('storage/' . $event->organizer->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($event->organizer->name)); ?>" 
                         alt="<?php echo e($event->organizer->name); ?>" 
                         class="h-12 w-12 rounded-full mr-4">
                    <div>
                        <div class="font-medium text-gray-900"><?php echo e($event->organizer->name); ?></div>
                        <div class="text-sm text-gray-500">Event Organizer</div>
                    </div>
                </div>
                
                <?php if($event->organizer->bio): ?>
                    <p class="text-gray-600 text-sm"><?php echo e($event->organizer->bio); ?></p>
                <?php endif; ?>
            </div>

            <!-- Related Events -->
            <?php if($relatedEvents->count() > 0): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Related Events</h3>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $relatedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedEvent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('events.show', $relatedEvent->slug)); ?>" class="block hover:bg-gray-50 p-3 rounded-lg transition duration-300">
                                <div class="flex items-center">
                                    <?php if($relatedEvent->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $relatedEvent->image)); ?>" alt="<?php echo e($relatedEvent->title); ?>" class="h-16 w-16 object-cover rounded-lg mr-3">
                                    <?php else: ?>
                                        <div class="h-16 w-16 bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar-alt text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 text-sm"><?php echo e($relatedEvent->title); ?></h4>
                                        <p class="text-xs text-gray-500"><?php echo e($relatedEvent->start_date->format('M d, Y')); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($relatedEvent->city); ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Update total amount when ticket quantity changes
document.getElementById('ticket_quantity').addEventListener('change', function() {
    const quantity = parseInt(this.value);
    const price = <?php echo e($event->price); ?>;
    const total = quantity * price;
    
    const totalElement = document.getElementById('total-amount');
    if (price > 0) {
        totalElement.textContent = '$' + total.toFixed(2);
    } else {
        totalElement.textContent = 'Free';
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/events/show.blade.php ENDPATH**/ ?>