<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Event Management System')); ?> - <?php echo $__env->yieldContent('title', 'Home'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https:
    <link href="https:

    <!-- Tailwind CSS -->
    <script src="https:
    
    <!-- Alpine.js -->
    <script defer src="https:
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https:
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                            <span class="text-xl font-bold text-gray-900">EventHub</span>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="<?php echo e(route('home')); ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('home') ? 'text-blue-600' : ''); ?>">
                            Home
                        </a>
                        <a href="<?php echo e(route('events.index')); ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('events.*') ? 'text-blue-600' : ''); ?>">
                            Events
                        </a>
                        
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('dashboard') ? 'text-blue-600' : ''); ?>">
                                Dashboard
                            </a>
                            
                            <?php if(auth()->user()->isOrganizer()): ?>
                                <a href="<?php echo e(route('organizer.events.index')); ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('organizer.*') ? 'text-blue-600' : ''); ?>">
                                    Manage Events
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center space-x-4">
                        <?php if(auth()->guard()->check()): ?>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                    <img src="<?php echo e(auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)); ?>"
                                         alt="<?php echo e(auth()->user()->name); ?>" 
                                         class="h-8 w-8 rounded-full">
                                    <div class="flex flex-col items-start">
                                        <span class="text-sm font-medium"><?php echo e(auth()->user()->name); ?></span>
                                        <span class="text-xs text-gray-500">
                                            <?php echo e(auth()->user()->isOrganizer() ? 'Organiser' : 'Attendee'); ?>

                                        </span>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="<?php echo e(route('dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Settings
                                    </a>
                                    <hr class="my-1">
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            <a href="<?php echo e(route('register')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile menu -->
        <div class="md:hidden" x-data="{ open: false }">
            <div x-show="open" @click.away="open = false" x-cloak class="bg-white shadow-lg">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="<?php echo e(route('home')); ?>" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Home</a>
                    <a href="<?php echo e(route('events.index')); ?>" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Events</a>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                        <?php if(auth()->user()->isOrganizer()): ?>
                            <a href="<?php echo e(route('organizer.events.index')); ?>" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Manage Events</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main>
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-16">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <i class="fas fa-calendar-alt text-blue-400 text-2xl"></i>
                            <span class="text-xl font-bold">EventHub</span>
                        </div>
                        <p class="text-gray-300">Your one-stop platform for discovering and managing amazing events.</p>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="<?php echo e(route('home')); ?>" class="text-gray-300 hover:text-white">Home</a></li>
                            <li><a href="<?php echo e(route('events.index')); ?>" class="text-gray-300 hover:text-white">Events</a></li>
                            <?php if(auth()->guard()->check()): ?>
                                <li><a href="<?php echo e(route('dashboard')); ?>" class="text-gray-300 hover:text-white">Dashboard</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white">Help Center</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Contact Us</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Privacy Policy</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Connect</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <i class="fab fa-linkedin text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-300">&copy; <?php echo e(date('Y')); ?> EventHub. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH /Users/hiteshsharma/Downloads/Web application/event-management-system/resources/views/layouts/app.blade.php ENDPATH**/ ?>