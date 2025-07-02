<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
     * Get the orders that used this coupon
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, CouponUsage::class);
    }

    /**
     * Get the users who used this coupon
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_usages');
    }

    /**
     * Scope to get only active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only valid coupons (within date range)
     */
    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('valid_until')
              ->orWhere('valid_until', '>=', $now);
        });
    }

    /**
     * Scope to get coupons by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to search coupons
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Check if coupon is currently valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        // Check date validity
        if ($this->valid_from && $now < $this->valid_from) {
            return false;
        }

        if ($this->valid_until && $now > $this->valid_until) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon can be used by a specific user
     */
    public function canBeUsedBy(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check per-user usage limit
        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount for a given order total
     */
    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'percentage') {
            return ($orderTotal * $this->value) / 100;
        }

        return $this->value;
    }

    /**
     * Check if coupon applies to a specific ebook
     */
    public function appliesToEbook(int $ebookId): bool
    {
        if (empty($this->applicable_ebooks)) {
            return true; // Applies to all ebooks
        }

        return in_array($ebookId, $this->applicable_ebooks);
    }

    /**
     * Check if coupon applies to a specific category
     */
    public function appliesToCategory(int $categoryId): bool
    {
        if (empty($this->applicable_categories)) {
            return true; // Applies to all categories
        }

        return in_array($categoryId, $this->applicable_categories);
    }

    /**
     * Get formatted value for display
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }

        return number_format($this->value, 2) . ' €';
    }

    /**
     * Get formatted minimum amount for display
     */
    public function getFormattedMinimumAmountAttribute(): string
    {
        if (!$this->minimum_amount) {
            return 'Aucun minimum';
        }

        return number_format($this->minimum_amount, 2) . ' €';
    }

    /**
     * Get status badge class for display
     */
    public function getStatusBadgeClassAttribute(): string
    {
        if (!$this->is_active) {
            return 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
        }

        if (!$this->isValid()) {
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
        }

        return 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
    }

    /**
     * Get formatted status for display
     */
    public function getFormattedStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactif';
        }

        if (!$this->isValid()) {
            return 'Expiré';
        }

        return 'Actif';
    }

    /**
     * Get type badge class for display
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return $this->type === 'percentage' 
            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400'
            : 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400';
    }

    /**
     * Get formatted type for display
     */
    public function getFormattedTypeAttribute(): string
    {
        return $this->type === 'percentage' ? 'Pourcentage' : 'Montant fixe';
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentageAttribute(): float
    {
        if (!$this->usage_limit) {
            return 0;
        }

        return ($this->used_count / $this->usage_limit) * 100;
    }

    /**
     * Get remaining uses
     */
    public function getRemainingUsesAttribute(): ?int
    {
        if (!$this->usage_limit) {
            return null; // Unlimited
        }

        return max(0, $this->usage_limit - $this->used_count);
    }

    /**
     * Get validity period for display
     */
    public function getValidityPeriodAttribute(): string
    {
        if (!$this->valid_from && !$this->valid_until) {
            return 'Toujours valide';
        }

        $from = $this->valid_from ? $this->valid_from->format('d/m/Y') : 'Toujours';
        $until = $this->valid_until ? $this->valid_until->format('d/m/Y') : 'Toujours';

        return "Du {$from} au {$until}";
    }

    /**
     * Get applicable scope for display
     */
    public function getApplicableScopeAttribute(): string
    {
        if (!empty($this->applicable_ebooks) && !empty($this->applicable_categories)) {
            return 'Ebooks et catégories spécifiques';
        }

        if (!empty($this->applicable_ebooks)) {
            return 'Ebooks spécifiques';
        }

        if (!empty($this->applicable_categories)) {
            return 'Catégories spécifiques';
        }

        return 'Tous les produits';
    }
} 