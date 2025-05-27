<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            EbookSystemSeeder::class,
        ]);
    }

    private function createSubcategoriesAndResources($mainCategories)
    {
        // Getting Started category resources
        $gettingStarted = $mainCategories[0];
        
        CategoryResource::create([
            'category_id' => $gettingStarted->id,
            'content_type' => 'text_content',
            'title' => 'Introduction to Chinese Manufacturing',
            'description' => 'Overview of Chinese manufacturing landscape',
            'content_data' => [
                'content' => 'China has become the world\'s manufacturing hub, producing everything from electronics to textiles. This section will guide you through the basics of working with Chinese manufacturers...'
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CategoryResource::create([
            'category_id' => $gettingStarted->id,
            'content_type' => 'pdf',
            'title' => 'China Business Basics Guide',
            'description' => 'Downloadable PDF guide',
            'content_data' => null,
            'file_path' => 'resources/china-business-basics.pdf',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Finding Suppliers category
        $findingSuppliers = $mainCategories[1];
        
        CategoryResource::create([
            'category_id' => $findingSuppliers->id,
            'content_type' => 'table',
            'title' => 'Popular B2B Platforms Comparison',
            'description' => 'Comparison of major Chinese B2B platforms',
            'content_data' => [
                'headers' => ['Platform', 'Suppliers', 'Verification', 'Commission'],
                'rows' => [
                    ['Alibaba.com', '200,000+', 'Gold Supplier', '3-5%'],
                    ['Made-in-China.com', '150,000+', 'Verified Supplier', '2-4%'],
                    ['GlobalSources.com', '50,000+', 'Premium Supplier', '4-6%'],
                    ['DHgate.com', '100,000+', 'Top Merchant', '5-8%']
                ]
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CategoryResource::create([
            'category_id' => $findingSuppliers->id,
            'content_type' => 'supplier_info',
            'title' => 'Recommended Electronics Supplier',
            'description' => 'Trusted electronics manufacturer',
            'content_data' => [
                'company_name' => 'Shenzhen TechMax Electronics Co., Ltd.',
                'contact_person' => 'Lisa Chen',
                'email' => 'lisa@techmax.com.cn',
                'phone' => '+86-755-1234-5678',
                'address' => 'Building A, Industrial Park, Shenzhen, Guangdong, China',
                'website' => 'https://www.techmax.com.cn',
                'specialization' => 'Consumer Electronics, IoT Devices',
                'min_order' => '1000 pieces',
                'payment_terms' => '30% deposit, 70% before shipment',
                'notes' => 'ISO 9001 certified, experienced with Western markets'
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Product Development category
        $productDev = $mainCategories[2];
        
        CategoryResource::create([
            'category_id' => $productDev->id,
            'content_type' => 'product_data',
            'title' => 'Sample Product Specification Template',
            'description' => 'Example product specification format',
            'content_data' => [
                'product_name' => 'Bluetooth Wireless Speaker',
                'brand' => 'Your Brand',
                'model' => 'BS-2024',
                'price_range' => '$15-25 FOB',
                'specifications' => 'Bluetooth 5.0, 10W output, 2000mAh battery, IPX5 waterproof',
                'features' => 'Voice assistant compatible, RGB lighting, portable design'
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Add subcategory to Quality Control
        $qualityControl = $mainCategories[3];
        $qcSubcategory = EbookCategory::create([
            'ebook_id' => $qualityControl->ebook_id,
            'parent_id' => $qualityControl->id,
            'name' => 'Inspection Checklists',
            'description' => 'Ready-to-use quality inspection checklists',
            'icon' => 'fas fa-clipboard-check',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CategoryResource::create([
            'category_id' => $qcSubcategory->id,
            'content_type' => 'xlsx',
            'title' => 'Electronics QC Checklist',
            'description' => 'Excel template for electronics quality control',
            'content_data' => null,
            'file_path' => 'resources/electronics-qc-checklist.xlsx',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}