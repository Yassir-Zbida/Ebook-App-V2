<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Support\Facades\Hash;

class EbookSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create test customers
        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = User::firstOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'name' => "Customer {$i}",
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'is_active' => true,
                ]
            );
        }

        // Create test ebook
        $ebook = Ebook::create([
            'title' => 'Complete Guide to E-commerce',
            'description' => 'A comprehensive guide covering all aspects of e-commerce business, from setting up your store to scaling your operations.',
            'price' => 49.99,
            'is_active' => true,
        ]);

        // Create root categories
        $gettingStarted = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'name' => 'Getting Started',
            'description' => 'Basic concepts and setup',
            'icon' => 'ri-play-circle-line',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $marketing = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'name' => 'Marketing & Sales',
            'description' => 'Strategies to grow your business',
            'icon' => 'ri-megaphone-line',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $operations = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'name' => 'Operations',
            'description' => 'Managing day-to-day operations',
            'icon' => 'ri-settings-line',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Create subcategories for Getting Started
        $setupBasics = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'parent_id' => $gettingStarted->id,
            'name' => 'Setup Basics',
            'description' => 'How to set up your first store',
            'icon' => 'ri-store-line',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $platformChoice = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'parent_id' => $gettingStarted->id,
            'name' => 'Platform Choice',
            'description' => 'Choosing the right e-commerce platform',
            'icon' => 'ri-computer-line',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Create subcategories for Marketing
        $seo = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'parent_id' => $marketing->id,
            'name' => 'SEO Strategies',
            'description' => 'Search engine optimization techniques',
            'icon' => 'ri-search-line',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $socialMedia = EbookCategory::create([
            'ebook_id' => $ebook->id,
            'parent_id' => $marketing->id,
            'name' => 'Social Media Marketing',
            'description' => 'Leveraging social platforms',
            'icon' => 'ri-share-line',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Create resources for leaf categories
        CategoryResource::create([
            'category_id' => $setupBasics->id,
            'title' => 'Store Setup Checklist',
            'content_type' => 'pdf',
            'description' => 'Complete checklist for setting up your online store',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CategoryResource::create([
            'category_id' => $platformChoice->id,
            'title' => 'Platform Comparison Guide',
            'content_type' => 'excel',
            'description' => 'Detailed comparison of popular e-commerce platforms',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CategoryResource::create([
            'category_id' => $seo->id,
            'title' => 'SEO Best Practices',
            'content_type' => 'pdf',
            'description' => 'Essential SEO techniques for e-commerce sites',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CategoryResource::create([
            'category_id' => $socialMedia->id,
            'title' => 'Social Media Templates',
            'content_type' => 'excel',
            'description' => 'Ready-to-use templates for social media campaigns',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Create some purchase records
        foreach (array_slice($customers, 0, 3) as $customer) {
            $customer->purchasedEbooks()->attach($ebook->id, [
                'purchase_price' => $ebook->price,
                'purchased_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('✅ Ebook system seeded successfully!');
        $this->command->info("📚 Created ebook: '{$ebook->title}'");
        $this->command->info('📁 Created ' . EbookCategory::count() . ' categories');
        $this->command->info('📄 Created ' . CategoryResource::count() . ' resources');
        $this->command->info('🛒 Created purchase records for 3 customers');
        $this->command->info('👤 Admin: admin@example.com / password123');
        $this->command->info('👥 Customers: customer1@example.com to customer5@example.com / password123');
    }
}
