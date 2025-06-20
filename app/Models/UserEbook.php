<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEbook extends Model
{
    use HasFactory;

    protected $table = 'user_ebooks';

    protected $fillable = [
        'user_id',
        'ebook_id',
        'purchase_price',
        'purchased_at',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    /**
     * Get the user that purchased the ebook
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ebook that was purchased
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}
