<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'coupon_code',
        'coupon_discount',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total',
        'metadata',
        'last_activity',
        'expires_at',
    ];

    protected $casts = [
        'coupon_discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'metadata' => 'array',
        'last_activity' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the cart items for the cart
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate cart totals
     */
    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal - $this->discount_amount + $this->tax_amount;
        $this->last_activity = now();
        $this->save();
    }

    /**
     * Check if cart is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get cart items count
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Clear cart items
     */
    public function clear(): void
    {
        $this->items()->delete();
        $this->calculateTotals();
    }
} 