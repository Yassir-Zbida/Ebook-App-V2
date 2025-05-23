<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('ebook_categories')->onDelete('cascade');
            $table->enum('content_type', [
                'pdf', 
                'image', 
                'docx', 
                'pptx', 
                'xlsx',
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
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category_id', 'content_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_resources');
    }
};