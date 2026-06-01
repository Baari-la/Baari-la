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
        Schema::create('company_moqs', function (Blueprint $table) {
    $table->id();

    $table->integer('company_id');

    $table->string('product_name')->nullable();

    $table->decimal('minimum_quantity', 15, 2)->default(0);

    $table->string('unit')->default('PCS');

    $table->text('notes')->nullable();

    $table->timestamps();

    $table->index('company_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_moqs');
    }
};