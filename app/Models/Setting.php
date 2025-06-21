<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'display_name',
        'description',
        'options',
        'sort_order',
        'is_public',
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->value;
    }

    /**
     * Set setting value by key
     */
    public static function setValue(string $key, $value, string $type = 'string'): void
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            static::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
            ]);
        }
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group)
    {
        return static::where('group', $group)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get public settings
     */
    public static function getPublicSettings()
    {
        return static::where('is_public', true)
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get setting value with proper type casting
     */
    public function getTypedValueAttribute()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'array' => json_decode($this->value, true),
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }
} 