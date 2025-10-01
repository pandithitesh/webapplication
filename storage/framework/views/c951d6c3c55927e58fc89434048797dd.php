<?php $__env->startSection('title', 'Organizer Dashboard'); ?>

<?php $__env->startSection('content'); ?>
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
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($eventStats['total_events']); ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($eventStats['published_events']); ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($eventStats['draft_events']); ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($eventStats['upcoming_events']); ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($bookingStats['total_bookings']); ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e($bookingStats['confirmed_bookings']); ?></p>
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
                    <p class="text-2xl font-semibold text-gray-900">$<?php echo e(number_format($bookingStats['total_revenue'], 2)); ?></p>
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
        
        <?php if(isset($eventsReport) && $eventsReport && count($eventsReport) > 0): ?>
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
                        <?php $__currentLoopData = $eventsReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo e($event->title); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(\Carbon\Carbon::parse($event->event_date)->format('M d, Y g:i A')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($event->total_capacity); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($event->current_bookings); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($event->remaining_spots > 0): ?> bg-green-100 text-green-800
                                    <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                    <?php echo e($event->remaining_spots); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-chart-bar text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Found</h3>
                <p class="text-gray-500">Create your first event to see the report</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Events -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Events</h2>
                <a href="<?php echo e(route('organizer.events.index')); ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
            
            <?php if($recentEvents->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <?php if($event->image): ?>
                                <img src="<?php echo e(asset('storage/' . $event->image)); ?>" alt="<?php echo e($event->title); ?>" class="h-12 w-12 object-cover rounded-lg mr-4">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900"><?php echo e($event->title); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e($event->start_date->format('M d, Y')); ?></p>
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($event->status === 'published'): ?> bg-green-100 text-green-800
                                        <?php elseif($event->status === 'draft'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($event->status === 'cancelled'): ?> bg-red-100 text-red-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($event->status)); ?>

                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <a href="<?php echo e(route('events.show', $event->slug)); ?>" class="text-blue-600 hover:text-blue-700 text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Yet</h3>
                    <p class="text-gray-500 mb-4">Create your first event to get started</p>
                    <a href="<?php echo e(route('organizer.events.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Create Event
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Bookings</h2>
                <a href="<?php echo e(route('organizer.bookings.index')); ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All
                </a>
            </div>
            
            <?php if($recentBookings->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white font-semibold text-sm"><?php echo e(substr($booking->user->name, 0, 1)); ?></span>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900"><?php echo e($booking->user->name); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e($booking->event->title); ?></p>
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
                    <p class="text-gray-500">Bookings will appear here once people start registering for your events</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?php echo e(route('organizer.events.create')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-plus text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Create New Event</h3>
                    <p class="text-sm text-gray-500">Add a new event to your portfolio</p>
                </div>
            </a>

            <a href="<?php echo e(route('organizer.events.index')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Manage Events</h3>
                    <p class="text-sm text-gray-500">Edit and update your events</p>
                </div>
            </a>

            <a href="<?php echo e(route('organizer.bookings.index')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/dashboard/organizer.blade.php ENDPATH**/ ?>