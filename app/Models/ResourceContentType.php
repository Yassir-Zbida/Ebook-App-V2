<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceContentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'form_fields',
        'is_active',
    ];

    protected $casts = [
        'form_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function categoryResources()
    {
        return $this->hasMany(CategoryResource::class, 'content_type_id');
    }

    public static function getDefaultTypes()
    {
        return [
            [
                'name' => 'pdf',
                'display_name' => 'PDF Document',
                'description' => 'Uploadable PDF files',
                'form_fields' => [
                    ['name' => 'file', 'type' => 'file', 'label' => 'PDF File', 'required' => true, 'accept' => '.pdf']
                ]
            ],
            [
                'name' => 'table',
                'display_name' => 'Data Table',
                'description' => 'Structured data tables',
                'form_fields' => [
                    ['name' => 'headers', 'type' => 'text', 'label' => 'Table Headers (comma separated)', 'required' => true],
                    ['name' => 'rows', 'type' => 'textarea', 'label' => 'Table Data (one row per line, comma separated)', 'required' => true]
                ]
            ],
            [
                'name' => 'supplier_info',
                'display_name' => 'Supplier Information',
                'description' => 'Supplier/Manufacturer details',
                'form_fields' => [
                    ['name' => 'company_name', 'type' => 'text', 'label' => 'Company Name', 'required' => true],
                    ['name' => 'contact_person', 'type' => 'text', 'label' => 'Contact Person', 'required' => false],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'required' => false],
                    ['name' => 'phone', 'type' => 'text', 'label' => 'Phone', 'required' => false],
                    ['name' => 'address', 'type' => 'textarea', 'label' => 'Address', 'required' => false],
                    ['name' => 'website', 'type' => 'url', 'label' => 'Website', 'required' => false],
                    ['name' => 'specialization', 'type' => 'text', 'label' => 'Specialization', 'required' => false],
                    ['name' => 'min_order', 'type' => 'text', 'label' => 'Minimum Order', 'required' => false],
                    ['name' => 'payment_terms', 'type' => 'text', 'label' => 'Payment Terms', 'required' => false],
                    ['name' => 'notes', 'type' => 'textarea', 'label' => 'Additional Notes', 'required' => false]
                ]
            ],
            [
                'name' => 'product_data',
                'display_name' => 'Product Data',
                'description' => 'Product specifications and details',
                'form_fields' => [
                    ['name' => 'product_name', 'type' => 'text', 'label' => 'Product Name', 'required' => true],
                    ['name' => 'brand', 'type' => 'text', 'label' => 'Brand', 'required' => false],
                    ['name' => 'model', 'type' => 'text', 'label' => 'Model', 'required' => false],
                    ['name' => 'price_range', 'type' => 'text', 'label' => 'Price Range', 'required' => false],
                    ['name' => 'specifications', 'type' => 'textarea', 'label' => 'Specifications', 'required' => false],
                    ['name' => 'features', 'type' => 'textarea', 'label' => 'Features', 'required' => false],
                    ['name' => 'image', 'type' => 'file', 'label' => 'Product Image', 'required' => false, 'accept' => 'image/*']
                ]
            ],
            [
                'name' => 'text_content',
                'display_name' => 'Text Content',
                'description' => 'Rich text content',
                'form_fields' => [
                    ['name' => 'content', 'type' => 'textarea', 'label' => 'Content', 'required' => true, 'rows' => 10]
                ]
            ]
        ];
    }
}