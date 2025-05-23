<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'content_type_id',
        'title',
        'description',
        'content_data',
        'file_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(EbookCategory::class, 'category_id');
    }

    public function contentType()
    {
        return $this->belongsTo(ResourceContentType::class, 'content_type_id');
    }

    public function getFormattedContentAttribute()
    {
        switch ($this->contentType->name) {
            case 'table':
                return $this->formatTableContent();
            case 'supplier_info':
                return $this->formatSupplierInfo();
            case 'product_data':
                return $this->formatProductData();
            default:
                return $this->content_data;
        }
    }

    private function formatTableContent()
    {
        if (!isset($this->content_data['headers']) || !isset($this->content_data['rows'])) {
            return $this->content_data;
        }

        $headers = explode(',', $this->content_data['headers']);
        $rows = explode("\n", $this->content_data['rows']);
        
        $formattedRows = [];
        foreach ($rows as $row) {
            if (trim($row)) {
                $formattedRows[] = explode(',', trim($row));
            }
        }

        return [
            'headers' => array_map('trim', $headers),
            'rows' => $formattedRows
        ];
    }

    private function formatSupplierInfo()
    {
        return $this->content_data;
    }

    private function formatProductData()
    {
        return $this->content_data;
    }
}