<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">Discover Amazing Events</h1>
        <p class="hero-subtitle">Join thousands of attendees at exciting events happening around you</p>
    </div>

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo e($stats['total_events'] ?? 0); ?></div>
            <div class="stat-label">Total Events</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($stats['total_attendees'] ?? 0); ?></div>
            <div class="stat-label">Active Attendees</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($stats['total_organizers'] ?? 0); ?></div>
            <div class="stat-label">Event Organizers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($stats['total_cities'] ?? 0); ?></div>
            <div class="stat-label">Cities Covered</div>
        </div>
    </div>

    <!-- Personalized Recommendations Section -->
    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isAttendee() && $recommendations->count() > 0): ?>
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <i class="fas fa-magic text-indigo-600 text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Personalized Recommendations</h2>
                </div>
                <p class="text-gray-600 mb-6">Events we think you'll love based on your preferences</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
                            <?php if($event->image): ?>
                                <img src="<?php echo e(asset('storage/' . $event->image)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-32 object-cover">
                            <?php else: ?>
                                <div class="w-full h-32 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-heart text-red-500 text-xs mr-1"></i>
                                    <span class="text-xs text-indigo-600 font-semibold">For You</span>
                                </div>
                                
                                <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2"><?php echo e($event->title); ?></h3>
                                
                                <div class="space-y-1 mb-3">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1 w-3"></i>
                                        <span><?php echo e($event->city); ?></span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1 w-3"></i>
                                        <span><?php echo e($event->start_date->format('M d')); ?></span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-indigo-600">
                                        <?php if($event->price > 0): ?>
                                            $<?php echo e(number_format($event->price, 0)); ?>

                                        <?php else: ?>
                                            Free
                                        <?php endif; ?>
                                    </span>
                                    <a href="<?php echo e(route('events.show', $event->slug)); ?>" class="bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700 transition duration-300">
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

    <!-- Events Section -->
    <div class="page-header">
        <h2 class="page-title">Upcoming Events</h2>
        <p class="page-subtitle">Don't miss out on these amazing experiences</p>
    </div>
    
    <?php if($upcomingEvents->count() > 0): ?>
        <div class="event-grid">
            <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="event-card">
                    <h3 class="event-title">
                        <a href="<?php echo e(route('events.show', $event->slug)); ?>">
                            <?php echo e($event->title); ?>

                        </a>
                    </h3>
                    <p class="event-detail">
                        <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; color: #9ca3af;"></i>
                        <?php echo e($event->start_date->format('M d, Y')); ?> at <?php echo e($event->start_date->format('h:i A')); ?>

                    </p>
                    <p class="event-detail">
                        <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; color: #9ca3af;"></i>
                        <?php echo e($event->venue); ?>, <?php echo e($event->city); ?>

                    </p>
                    <p class="event-detail">
                        <i class="fas fa-users" style="margin-right: 0.5rem; color: #9ca3af;"></i>
                        <?php echo e($event->available_spots); ?> spots available
                    </p>
                    <p class="event-detail" style="font-weight: 600; color: #2563eb; margin-top: 1rem; font-size: 1.125rem;">
                        <i class="fas fa-ticket-alt" style="margin-right: 0.5rem;"></i>
                        <?php if($event->price > 0): ?>
                            $<?php echo e(number_format($event->price, 2)); ?>

                        <?php else: ?>
                            Free
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div style="margin-top: 3rem; text-align: center;">
            <?php echo e($upcomingEvents->links()); ?>

        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <i class="fas fa-calendar-times" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
            <p style="color: #6b7280; font-size: 1.125rem;">No upcoming events available at the moment.</p>
            <p style="color: #9ca3af; margin-top: 0.5rem;">Check back soon for new events!</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/home.blade.php ENDPATH**/ ?>