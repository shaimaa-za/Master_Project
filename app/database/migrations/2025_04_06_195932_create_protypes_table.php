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
        Schema::create('protypes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique(); // كل منتج له سجل واحد في جدول protypes
            $table->string('name'); // نوع المنتج: Bracelet, Earring, Necklace, Ring
            $table->timestamps();
        
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protypes');
    }
};
