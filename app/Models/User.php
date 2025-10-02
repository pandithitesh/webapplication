<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'bio',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Check if user is an organizer
     * 
     * @return bool
     */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /**
     * Check if user is an attendee
     * 
     * @return bool
     */
    public function isAttendee(): bool
    {
        return $this->role === 'attendee';
    }

    /**
     * Get all events created by this user (organizers only)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function events()
    {
        return $this->organizedEvents();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookedEvents()
    {
        return $this->belongsToMany(Event::class, 'bookings')
                    ->withPivot(['booking_reference', 'ticket_quantity', 'total_amount', 'status', 'created_at'])
                    ->withTimestamps();
    }
}
