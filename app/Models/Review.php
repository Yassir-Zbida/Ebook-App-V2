<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ebook_id',
        'rating',
        'review',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'status' => 'string',
    ];

    /**
     * Get the user that wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ebook that was reviewed
     */
    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    /**
     * Scope to get only approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get reviews for a specific ebook
     */
    public function scopeForEbook($query, $ebookId)
    {
        return $query->where('ebook_id', $ebookId);
    }

    /**
     * Scope to get reviews by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get only pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only approved reviews (by status)
     */
    public function scopeStatusApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only rejected reviews
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
