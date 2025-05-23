<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ebook_id')->constrained()->onDelete('cascade');
            $table->decimal('purchase_price', 8, 2);
            $table->timestamp('purchased_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'ebook_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_ebooks');
    }
};
