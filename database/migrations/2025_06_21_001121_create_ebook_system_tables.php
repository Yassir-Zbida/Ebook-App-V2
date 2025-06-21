<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create ebooks table
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'created_at']);
        });

        // 2. Create ebook_categories table (hierarchical structure)
        Schema::create('ebook_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebook_id')->constrained('ebooks')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('ebook_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['ebook_id', 'parent_id']);
            $table->index(['parent_id', 'sort_order']);
            $table->index(['is_active']);
        });

        // 3. Create category_resources table
        Schema::create('category_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('ebook_categories')->onDelete('cascade');
            $table->enum('content_type', [
                'pdf', 
                'excel', 
                'xlsx',
                'image', 
                'docx', 
                'pptx', 
                'table', 
                'supplier_info', 
                'product_data', 
                'text_content',
                'form'
            ])->default('text_content');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('content_data')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category_id', 'content_type']);
            $table->index(['content_type']);
            $table->index(['is_active']);
        });

        // 4. Create user_ebooks table (purchase records)
        Schema::create('user_ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ebook_id')->constrained('ebooks')->onDelete('cascade');
            $table->decimal('purchase_price', 10, 2);
            $table->timestamp('purchased_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'ebook_id']);
            $table->index(['user_id', 'purchased_at']);
            $table->index(['ebook_id', 'purchased_at']);
        });

        // 5. Create password_reset_tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user_ebooks');
        Schema::dropIfExists('category_resources');
        Schema::dropIfExists('ebook_categories');
        Schema::dropIfExists('ebooks');
    }
};
