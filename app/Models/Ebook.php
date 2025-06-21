<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'cover_image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function categories()
    {
        return $this->hasMany(EbookCategory::class);
    }

    public function rootCategories()
    {
        return $this->categories()->whereNull('parent_id')->orderBy('sort_order');
    }

    public function purchasers()
    {
        return $this->belongsToMany(User::class, 'user_ebooks')
                    ->withPivot('purchase_price', 'purchased_at')
                    ->withTimestamps();
    }

    /**
     * Get the order items for this ebook
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the cart items for this ebook
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get users who have this ebook in their wishlist
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'user_wishlist')
                    ->withTimestamps();
    }

    /**
     * Get orders that contain this ebook
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('ebook_title', 'price', 'quantity', 'discount_amount', 'subtotal')
                    ->withTimestamps();
    }

    public function getAllResourcesCount()
    {
        return CategoryResource::whereIn('category_id', $this->categories->pluck('id'))->count();
    }

    /**
     * Get total sales for this ebook
     */
    public function getTotalSales()
    {
        return $this->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->sum('order_items.subtotal');
    }

    /**
     * Get total quantity sold
     */
    public function getTotalQuantitySold()
    {
        return $this->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');
    }

    /**
     * Get wishlist count
     */
    public function getWishlistCount()
    {
        return $this->wishlistedBy()->count();
    }
}