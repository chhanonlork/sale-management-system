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
        Schema::create('sale_details', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade'); // 
    $table->foreignId('product_id')->constrained('products'); // 
    $table->integer('qty'); // How many sold 
    $table->decimal('price', 10, 2); // Price at moment of sale 
    $table->decimal('subtotal', 10, 2); // qty * price 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
