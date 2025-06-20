<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'content_type',
        'title',
        'description',
        'content_data',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content_data' => 'array',
        'is_active' => 'boolean',
    ];

    // Available content types
    const CONTENT_TYPES = [
        'pdf' => 'PDF Document',
        'excel' => 'Excel File',
        'image' => 'Image',
        'docx' => 'Word Document',
        'pptx' => 'PowerPoint Presentation',
        'xlsx' => 'Excel Spreadsheet',
        'table' => 'Data Table',
        'supplier_info' => 'Supplier Information',
        'product_data' => 'Product Data',
        'text_content' => 'Text Content',
        'form' => 'Form Data'
    ];

    public function category()
    {
        return $this->belongsTo(EbookCategory::class, 'category_id');
    }

    public function getContentTypeDisplayAttribute()
    {
        return self::CONTENT_TYPES[$this->content_type] ?? $this->content_type;
    }

    public function isFileType()
    {
        return in_array($this->content_type, ['pdf', 'excel', 'image', 'docx', 'pptx', 'xlsx']);
    }

    public function isDataType()
    {
        return in_array($this->content_type, ['table', 'supplier_info', 'product_data', 'text_content', 'form']);
    }

    public function getFormattedContentAttribute()
    {
        switch ($this->content_type) {
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

        $headers = is_array($this->content_data['headers']) 
            ? $this->content_data['headers']
            : explode(',', $this->content_data['headers']);
            
        $rows = is_array($this->content_data['rows'])
            ? $this->content_data['rows']
            : array_map(function($row) {
                return explode(',', trim($row));
            }, explode("\n", $this->content_data['rows']));

        return [
            'headers' => array_map('trim', $headers),
            'rows' => array_filter($rows, function($row) {
                return !empty(array_filter($row, 'trim'));
            })
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

    public static function getFormFieldsForType($contentType)
    {
        $fields = [
            'table' => [
                ['name' => 'headers', 'type' => 'text', 'label' => 'Table Headers (comma separated)', 'required' => true],
                ['name' => 'rows', 'type' => 'textarea', 'label' => 'Table Data (one row per line, comma separated)', 'required' => true]
            ],
            'supplier_info' => [
                ['name' => 'company_name', 'type' => 'text', 'label' => 'Company Name', 'required' => true],
                ['name' => 'contact_person', 'type' => 'text', 'label' => 'Contact Person'],
                ['name' => 'email', 'type' => 'email', 'label' => 'Email'],
                ['name' => 'phone', 'type' => 'text', 'label' => 'Phone'],
                ['name' => 'address', 'type' => 'textarea', 'label' => 'Address'],
                ['name' => 'website', 'type' => 'url', 'label' => 'Website'],
                ['name' => 'specialization', 'type' => 'text', 'label' => 'Specialization'],
                ['name' => 'min_order', 'type' => 'text', 'label' => 'Minimum Order'],
                ['name' => 'payment_terms', 'type' => 'text', 'label' => 'Payment Terms'],
                ['name' => 'notes', 'type' => 'textarea', 'label' => 'Additional Notes']
            ],
            'product_data' => [
                ['name' => 'product_name', 'type' => 'text', 'label' => 'Product Name', 'required' => true],
                ['name' => 'brand', 'type' => 'text', 'label' => 'Brand'],
                ['name' => 'model', 'type' => 'text', 'label' => 'Model'],
                ['name' => 'price_range', 'type' => 'text', 'label' => 'Price Range'],
                ['name' => 'specifications', 'type' => 'textarea', 'label' => 'Specifications'],
                ['name' => 'features', 'type' => 'textarea', 'label' => 'Features']
            ],
            'text_content' => [
                ['name' => 'content', 'type' => 'textarea', 'label' => 'Content', 'required' => true, 'rows' => 10]
            ],
            'form' => [
                ['name' => 'form_data', 'type' => 'textarea', 'label' => 'Form Data (JSON)', 'required' => true]
            ]
        ];

        return $fields[$contentType] ?? [];
    }
}