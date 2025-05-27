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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // Group settings (general, payment, email, etc.)
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json, array
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('options')->nullable(); // For select fields, validation rules, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('is_public')->default(false); // Can be accessed without auth
            $table->timestamps();
            
            $table->index(['group', 'key']);
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};