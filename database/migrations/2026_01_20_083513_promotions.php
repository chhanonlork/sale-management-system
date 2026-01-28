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
        //
        Schema::create('promotions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // 10% ឬ $10
    $table->decimal('discount', 10, 2);
    $table->date('start_date');
    $table->date('end_date');
    $table->string('status')->default('active');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
