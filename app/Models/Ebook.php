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

    public function getAllResourcesCount()
    {
        return CategoryResource::whereIn('category_id', $this->categories->pluck('id'))->count();
    }
}