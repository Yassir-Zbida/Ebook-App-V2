<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the ebooks purchased by this user
     */
    public function purchasedEbooks()
    {
        return $this->belongsToMany(Ebook::class, 'user_ebooks')
            ->withPivot('purchase_price', 'purchased_at')
            ->withTimestamps();
    }

    /**
     * Check if user has purchased a specific ebook
     */
    public function hasPurchased(int $ebookId): bool
    {
        return $this->purchasedEbooks()->where('ebook_id', $ebookId)->exists();
    }

    /**
     * Get user's purchase history
     */
    public function getPurchaseHistory()
    {
        return $this->purchasedEbooks()
            ->withPivot('purchase_price', 'purchased_at')
            ->orderBy('user_ebooks.purchased_at', 'desc')
            ->get();
    }

    /**
     * Get the orders for the user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's total orders count
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get user's total spent
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }

    /**
     * Get user's last order
     */
    public function getLastOrderAttribute()
    {
        return $this->orders()->latest()->first();
    }

    /**
     * Scope to get only admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope to get only customer users
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get user's role badge class for display
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return $this->isAdmin() ? 'bg-danger' : 'bg-primary';
    }

    /**
     * Get formatted role name
     */
    public function getFormattedRoleAttribute(): string
    {
        return ucfirst($this->role);
    }
    
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new ResetPasswordMail($token, $this->email, $this));
    }
}
