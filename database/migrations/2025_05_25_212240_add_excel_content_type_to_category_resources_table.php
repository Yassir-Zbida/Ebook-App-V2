<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'excel' to the content_type enum
        DB::statement("ALTER TABLE category_resources MODIFY COLUMN content_type ENUM('pdf', 'image', 'docx', 'pptx', 'xlsx', 'excel', 'table', 'supplier_info', 'product_data', 'text_content', 'form') DEFAULT 'text_content'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'excel' from the content_type enum
        DB::statement("ALTER TABLE category_resources MODIFY COLUMN content_type ENUM('pdf', 'image', 'docx', 'pptx', 'xlsx', 'table', 'supplier_info', 'product_data', 'text_content', 'form') DEFAULT 'text_content'");
    }
};
