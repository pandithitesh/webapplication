<?php $__env->startSection('title', 'Manage Events'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Manage Events</h1>
                <p class="text-gray-600">Create, edit, and manage your events</p>
            </div>
            <a href="<?php echo e(route('organizer.events.create')); ?>" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Create New Event
            </a>
        </div>
    </div>

    <!-- Events List -->
    <?php if($events->count() > 0): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Capacity
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bookings
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if($event->image): ?>
                                        <img class="h-12 w-12 rounded-lg object-cover mr-4" 
                                             src="<?php echo e(asset('storage/' . $event->image)); ?>" 
                                             alt="<?php echo e($event->title); ?>">
                                    <?php else: ?>
                                        <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="<?php echo e(route('events.show', $event->slug)); ?>" 
                                               class="hover:text-blue-600">
                                                <?php echo e($event->title); ?>

                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo e(Str::limit($event->description, 50)); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo e($event->start_date->format('M d, Y')); ?>

                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($event->start_date->format('g:i A')); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($event->venue); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($event->city); ?>, <?php echo e($event->state); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($event->max_attendees); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo e($event->bookings()->count()); ?> bookings
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($event->available_spots); ?> spots left
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    <?php if($event->status === 'published'): ?> bg-green-100 text-green-800
                                    <?php elseif($event->status === 'draft'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($event->status === 'cancelled'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($event->status)); ?>

                                </span>
                                <?php if($event->is_featured): ?>
                                    <span class="ml-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Featured
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('events.show', $event->slug)); ?>" 
                                       class="text-blue-600 hover:text-blue-900" 
                                       title="View Event">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('organizer.events.edit', $event->id)); ?>" 
                                       class="text-indigo-600 hover:text-indigo-900" 
                                       title="Edit Event">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" 
                                          action="<?php echo e(route('organizer.events.destroy', $event->id)); ?>" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Delete Event">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($events->links()); ?>

        </div>
    <?php else: ?>
        <!-- No Events -->
        <div class="text-center py-12">
            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-plus text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Yet</h3>
            <p class="text-gray-600 mb-6">Get started by creating your first event</p>
            <a href="<?php echo e(route('organizer.events.create')); ?>" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                Create Your First Event
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/events/manage.blade.php ENDPATH**/ ?>