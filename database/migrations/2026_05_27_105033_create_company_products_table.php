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
        
        Schema::create('company_products', function (Blueprint $table) {
    $table->id();

    $table->foreignId('company_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('product_name');
    $table->string('product_name_en')->nullable();

    $table->string('hs_code', 20)->nullable();

    $table->string('category')->nullable();

    $table->text('description')->nullable();

    $table->boolean('is_primary')->default(false);

    $table->timestamps();

    $table->index('product_name');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_products');
    }
};