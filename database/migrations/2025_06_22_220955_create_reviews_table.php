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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ebook_id')->constrained('ebooks')->onDelete('cascade');
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            // One review per user per ebook
            $table->unique(['user_id', 'ebook_id']);
            
            // Indexes for better performance
            $table->index(['ebook_id', 'is_approved']);
            $table->index(['user_id']);
            $table->index(['rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
