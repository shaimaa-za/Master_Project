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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // مفتاح خارجي إلى جدول المستخدمين
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');  // مفتاح خارجي إلى جدول المنتجات
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);  // قيد لضمان عدم إضافة نفس المنتج للمستخدم أكثر من مرة
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
