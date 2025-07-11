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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('value', 10, 2); // Amount or percentage
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum order amount
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_user')->nullable(); // Per user limit
            $table->integer('used_count')->default(0); // Track usage
            $table->boolean('is_active')->default(true);
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_until')->nullable();
            $table->json('applicable_ebooks')->nullable(); // Specific ebook IDs if restricted
            $table->json('applicable_categories')->nullable(); // Specific category IDs if restricted
            $table->json('metadata')->nullable(); // Additional rules/data
            $table->timestamps();
            
            $table->index(['code', 'is_active']);
            $table->index(['valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
