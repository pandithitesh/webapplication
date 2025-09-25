<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'booking_id',
        'rating',
        'comment',
        'is_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Get the event for this review
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who wrote this review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking for this review
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if review is verified
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Verify the review
     */
    public function verify(): bool
    {
        $this->update(['is_verified' => true]);
        return true;
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for reviews by rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for reviews with comments
     */
    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment')->where('comment', '!=', '');
    }
}
