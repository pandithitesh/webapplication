<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'slug',
        'venue',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'registration_deadline',
        'max_attendees',
        'price',
        'currency',
        'image',
        'images',
        'tags',
        'status',
        'is_featured',
        'requires_approval',
        'cancellation_policy',
        'refund_policy',
        'additional_info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'images' => 'array',
        'tags' => 'array',
        'additional_info' => 'array',
        'is_featured' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    /**
     * Get the organizer of the event
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get bookings for this event
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get confirmed bookings for this event
     */
    public function confirmedBookings()
    {
        return $this->hasMany(Booking::class)->where('status', 'confirmed');
    }

    /**
     * Get reviews for this event
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get categories for this event
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_categories');
    }

    /**
     * Get attendees for this event
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'bookings')
                    ->wherePivot('status', 'confirmed')
                    ->withPivot(['booking_reference', 'ticket_quantity', 'total_amount', 'created_at'])
                    ->withTimestamps();
    }

    /**
     * Check if event is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if event is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if event is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if registration is open
     */
    public function isRegistrationOpen(): bool
    {
        return $this->isPublished() && 
               $this->registration_deadline > now() && 
               $this->start_date > now();
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute(): int
    {
        $bookedSpots = $this->confirmedBookings()->sum('ticket_quantity');
        return max(0, $this->max_attendees - $bookedSpots);
    }

    /**
     * Check if event is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->available_spots <= 0;
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Scope for published events
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope for events by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    /**
     * Scope for events by price range
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }
}
