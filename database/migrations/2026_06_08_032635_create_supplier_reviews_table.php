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
        Schema::create('supplier_reviews', function (Blueprint $table) {

    $table->id();

    $table->foreignId('purchase_order_id')
        ->constrained()
        ->cascadeOnDelete();

    // companies.id = int(11)
    $table->unsignedInteger('supplier_company_id');

    $table->foreignId('buyer_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->tinyInteger('quality_rating');

    $table->tinyInteger('delivery_rating');

    $table->tinyInteger('communication_rating');

    $table->text('comment')->nullable();

    $table->timestamps();

    $table->unique('purchase_order_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_reviews');
    }
};