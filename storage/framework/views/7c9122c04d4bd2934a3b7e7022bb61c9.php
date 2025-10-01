<?php $__env->startSection('title', 'Attendee Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Dashboard</h1>
        <p class="text-gray-600">Manage your event bookings and discover new events</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($bookingStats['total_bookings']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Confirmed</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($bookingStats['confirmed_bookings']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($bookingStats['pending_bookings']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cancelled</p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($bookingStats['cancelled_bookings']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Events -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Upcoming Events</h2>
                <a href="<?php echo e(route('attendee.bookings.index')); ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
            
            <?php if($upcomingEvents->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <?php if($booking->event->image): ?>
                                <img src="<?php echo e(asset('storage/' . $booking->event->image)); ?>" alt="<?php echo e($booking->event->title); ?>" class="h-12 w-12 object-cover rounded-lg mr-4">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900"><?php echo e($booking->event->title); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e($booking->event->start_date->format('M d, Y \a\t g:i A')); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($booking->event->venue); ?>, <?php echo e($booking->event->city); ?></p>
                            </div>
                            
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                                <div class="mt-1">
                                    <a href="<?php echo e(route('events.show', $booking->event->slug)); ?>" class="text-blue-600 hover:text-blue-700 text-sm">
                                        View Event
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Upcoming Events</h3>
                    <p class="text-gray-500 mb-4">You don't have any confirmed upcoming events</p>
                    <a href="<?php echo e(route('events.index')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Browse Events
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Bookings</h2>
                <a href="<?php echo e(route('attendee.bookings.index')); ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
            
            <?php if($recentBookings->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <?php if($booking->event->image): ?>
                                <img src="<?php echo e(asset('storage/' . $booking->event->image)); ?>" alt="<?php echo e($booking->event->title); ?>" class="h-12 w-12 object-cover rounded-lg mr-4">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900"><?php echo e($booking->event->title); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e($booking->created_at->format('M d, Y')); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($booking->ticket_quantity); ?> <?php echo e($booking->ticket_quantity == 1 ? 'ticket' : 'tickets'); ?></p>
                            </div>
                            
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">$<?php echo e(number_format($booking->total_amount, 2)); ?></div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($booking->status === 'confirmed'): ?> bg-green-100 text-green-800
                                    <?php elseif($booking->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($booking->status === 'cancelled'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e(ucfirst($booking->status)); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-ticket-alt text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Bookings Yet</h3>
                    <p class="text-gray-500 mb-4">Start exploring events and make your first booking</p>
                    <a href="<?php echo e(route('events.index')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Browse Events
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?php echo e(route('events.index')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-search text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Browse Events</h3>
                    <p class="text-sm text-gray-500">Discover new events to attend</p>
                </div>
            </a>

            <a href="<?php echo e(route('attendee.bookings.index')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">My Bookings</h3>
                    <p class="text-sm text-gray-500">View and manage your bookings</p>
                </div>
            </a>

            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">My Reviews</h3>
                    <p class="text-sm text-gray-500">View and manage your reviews</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/dashboard/attendee.blade.php ENDPATH**/ ?>