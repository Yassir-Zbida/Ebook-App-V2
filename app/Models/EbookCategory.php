<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbookCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ebook_id',
        'parent_id',
        'name',
        'description',
        'icon',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }

    public function parent()
    {
        return $this->belongsTo(EbookCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(EbookCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function resources()
    {
        return $this->hasMany(CategoryResource::class, 'category_id')->orderBy('sort_order');
    }

    public function getAllDescendants()
    {
        $descendants = collect();
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        return $descendants;
    }

    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    public function getLevel()
    {
        $level = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        
        return $level;
    }
}