<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'user_id',
        'type',
        'status',
        'gateway',
        'gateway_transaction_id',
        'amount',
        'currency',
        'gateway_fee',
        'gateway_response',
        'metadata',
        'failure_reason',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the order that owns the transaction
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique transaction ID
     */
    public static function generateTransactionId(): string
    {
        do {
            $transactionId = 'TXN-' . date('Ymd') . '-' . strtoupper(uniqid());
        } while (static::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }

    /**
     * Mark transaction as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Complete purchase by adding ebooks to user's library
     */
    public function completePurchase(): void
    {
        if ($this->status !== 'completed' || !$this->order) {
            return;
        }

        // Add ebooks to user's library
        foreach ($this->order->orderItems as $orderItem) {
            \DB::table('user_ebooks')->updateOrInsert(
                [
                    'user_id' => $this->user_id,
                    'ebook_id' => $orderItem->ebook_id,
                ],
                [
                    'purchase_price' => $orderItem->price,
                    'purchased_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Clear user's cart if it contains the purchased items
        $cart = \App\Models\Cart::where('session_id', $this->user_id)->first();
        if ($cart) {
            $purchasedEbookIds = $this->order->orderItems->pluck('ebook_id')->toArray();
            $cart->items()->whereIn('ebook_id', $purchasedEbookIds)->delete();
            
            // If cart is empty, delete it
            if ($cart->items()->count() === 0) {
                $cart->delete();
            }
        }
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed(string $reason = null): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'processed_at' => now(),
        ]);
    }

    /**
     * Check if transaction is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get transaction status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Get formatted type
     */
    public function getFormattedTypeAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->type));
    }
} 