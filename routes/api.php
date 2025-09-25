<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('featured', [EventController::class, 'featured']);
    Route::get('{slug}', [EventController::class, 'show']);
    Route::get('organizer/{organizerId}', [EventController::class, 'byOrganizer']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('password', [AuthController::class, 'changePassword']);
    });

    // Event management (organizer only)
    Route::middleware('role:organizer')->prefix('events')->group(function () {
        Route::post('/', [EventController::class, 'store']);
        Route::put('{id}', [EventController::class, 'update']);
        Route::delete('{id}', [EventController::class, 'destroy']);
    });

    // Booking routes
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('{id}', [BookingController::class, 'show']);
        Route::put('{id}/cancel', [BookingController::class, 'cancel']);
        Route::get('statistics', [BookingController::class, 'statistics']);
    });

    // Organizer booking management
    Route::middleware('role:organizer')->prefix('bookings')->group(function () {
        Route::get('organizer/all', [BookingController::class, 'organizerBookings']);
        Route::put('{id}/status', [BookingController::class, 'updateStatus']);
    });

    // Review routes
    Route::prefix('reviews')->group(function () {
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('{id}', [ReviewController::class, 'update']);
        Route::delete('{id}', [ReviewController::class, 'destroy']);
    });

    // Dashboard routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('organizer', [DashboardController::class, 'organizer']);
        Route::get('attendee', [DashboardController::class, 'attendee']);
    });
});
