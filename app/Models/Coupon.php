<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'is_active',
        'valid_from',
        'valid_until',
        'applicable_ebooks',
        'applicable_categories',
        'metadata',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applicable_ebooks' => 'array',
        'applicable_categories' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the coupon usages for this coupon
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from->isFuture()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can use this coupon
     */
    public function canBeUsedBy(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        }

        return $subtotal * ($this->value / 100);
    }

    /**
     * Check if coupon applies to specific ebook
     */
    public function appliesToEbook(int $ebookId): bool
    {
        if (empty($this->applicable_ebooks)) {
            return true;
        }

        return in_array($ebookId, $this->applicable_ebooks);
    }

    /**
     * Check if coupon applies to specific category
     */
    public function appliesToCategory(int $categoryId): bool
    {
        if (empty($this->applicable_categories)) {
            return true;
        }

        return in_array($categoryId, $this->applicable_categories);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
} 