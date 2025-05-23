<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebook_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebook_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('ebook_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); 
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['ebook_id', 'parent_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_categories');
    }
};