<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resource_content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // pdf, table, form, image, text, video
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('form_fields')->nullable(); // Dynamic form configuration
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resource_content_types');
    }
};