<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ebook_id',
        'ebook_title',
        'price',
        'quantity',
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
     * Get the order that owns the order item
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the ebook for this order item
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
        $this->subtotal = ($this->price * $this->quantity) - $this->discount_amount;
        $this->save();
    }
} 