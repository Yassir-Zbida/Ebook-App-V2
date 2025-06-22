<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'ebook_id',
        'price',
        'discount_amount',
        'subtotal',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the cart that owns the cart item
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the ebook for this cart item
     */
    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    /**
     * Calculate subtotal for this item
     */
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->price - $this->discount_amount;
        $this->save();
    }
} 