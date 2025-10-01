<?php $__env->startSection('title', $event->title); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <div class="event-card" style="margin-bottom: 2rem;">
        <h1 class="text-3xl font-bold mb-4"><?php echo e($event->title); ?></h1>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <?php if($event->image): ?>
                    <img src="<?php echo e(asset('storage/' . $event->image)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-64 object-cover rounded">
                <?php endif; ?>
            </div>
            
            <div>
                <div class="space-y-3 mb-6">
                    <p><strong>Date:</strong> <?php echo e($event->start_date->format('M d, Y')); ?></p>
                    <p><strong>Time:</strong> <?php echo e($event->start_date->format('h:i A')); ?></p>
                    <p><strong>Location:</strong> <?php echo e($event->venue); ?>, <?php echo e($event->city); ?></p>
                    <p><strong>Capacity:</strong> <?php echo e($event->max_attendees); ?> attendees</p>
                    <p><strong>Available:</strong> <?php echo e($event->available_spots); ?> spots left</p>
                    <p><strong>Price:</strong> 
                        <?php if($event->price > 0): ?>
                            $<?php echo e(number_format($event->price, 2)); ?>

                        <?php else: ?>
                            Free
                        <?php endif; ?>
                    </p>
                    <p><strong>Organizer:</strong> <?php echo e($event->organizer->name); ?></p>
                </div>

                <?php if($event->description): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p><?php echo e($event->description); ?></p>
                    </div>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isAttendee() && $event->isRegistrationOpen() && !$event->isSoldOut()): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Book This Event</h3>
                            <form action="<?php echo e(route('attendee.bookings.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="event_id" value="<?php echo e($event->id); ?>">
                                <div class="flex items-center space-x-4">
                                    <label class="font-semibold">Tickets:</label>
                                    <select name="ticket_quantity" class="border rounded px-3 py-2">
                                        <?php for($i = 1; $i <= min(5, $event->available_spots); $i++): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                        Book Now
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <?php if(auth()->user()->isOrganizer() && auth()->user()->id === $event->organizer_id): ?>
                        <div class="flex space-x-4">
                            <a href="<?php echo e(route('organizer.events.edit', $event->id)); ?>" class="bg-green-600 text-white px-4 py-2 rounded">
                                Edit Event
                            </a>
                            <form action="<?php echo e(route('organizer.events.destroy', $event->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded" onclick="return confirm('Delete this event?')">
                                    Delete Event
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(auth()->guard()->guest()): ?>
                    <div class="mb-6">
                        <p class="text-gray-600 mb-4">Please login to book this event.</p>
                        <a href="<?php echo e(route('login')); ?>" class="bg-blue-600 text-white px-6 py-2 rounded">
                            Login to Book
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($event->reviews->count() > 0): ?>
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Reviews</h2>
                <div class="space-y-4">
                    <?php $__currentLoopData = $event->reviews->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-b pb-4">
                            <div class="flex items-center mb-2">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($review->user->name)); ?>" alt="<?php echo e($review->user->name); ?>" class="h-10 w-10 rounded-full mr-3">
                                <div>
                                    <div class="font-medium"><?php echo e($review->user->name); ?></div>
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($review->comment): ?>
                                <p><?php echo e($review->comment); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/events/show.blade.php ENDPATH**/ ?>