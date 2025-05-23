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
            $table->foreignId('content_type_id')->constrained('resource_content_types')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('content_data'); // Flexible JSON field for different content types
            $table->string('file_path')->nullable(); // For PDF, images, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['category_id', 'content_type_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_resources');
    }
};