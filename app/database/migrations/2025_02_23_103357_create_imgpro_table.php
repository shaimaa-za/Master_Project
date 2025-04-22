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
        Schema::create('imgpro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // ارتباط بالمنتج
            $table->string('image_url'); // رابط الصورة
            $table->string('faiss_id'); // إضافة حقل faiss_id لتخزين المعرف
            $table->json('descriptors')->nullable(); // ميزات الصورة (اختياري)
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imgpro');
    }
};
