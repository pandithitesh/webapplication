<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\EventController as WebEventController;
use App\Http\Controllers\Web\AuthController as WebAuthController;
use App\Http\Controllers\Web\DashboardController as WebDashboardController;
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [WebEventController::class, 'index'])->name('events.index');
Route::post('/events/ajax-filter', [WebEventController::class, 'ajaxFilter'])->name('events.ajax-filter');
Route::get('/events/{id}/recommendation-reasons', [WebEventController::class, 'getRecommendationReasons'])->name('events.recommendation-reasons');
Route::get('/events/{slug}', [WebEventController::class, 'show'])->name('events.show');
Route::get('/privacy-policy', function () { return view('privacy-policy'); })->name('privacy-policy');
Route::get('/terms-of-service', function () { return view('terms-of-service'); })->name('terms-of-service');

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WebDashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware('role:organizer')->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/events', [WebEventController::class, 'manage'])->name('events.index');
        Route::get('/events/create', [WebEventController::class, 'create'])->name('events.create');
        Route::post('/events', [WebEventController::class, 'store'])->name('events.store');
        Route::get('/events/{id}/edit', [WebEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}', [WebEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [WebEventController::class, 'destroy'])->name('events.destroy');
        Route::get('/bookings', [WebDashboardController::class, 'bookings'])->name('bookings.index');
    });
    
    Route::middleware('role:attendee')->prefix('attendee')->name('attendee.')->group(function () {
        Route::get('/bookings', [WebDashboardController::class, 'myBookings'])->name('bookings.index');
        Route::post('/bookings', [WebDashboardController::class, 'createBooking'])->name('bookings.store');
        Route::delete('/bookings/{id}', [WebDashboardController::class, 'cancelBooking'])->name('bookings.cancel');
    });
});
