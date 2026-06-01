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
        Schema::create('company_images', function (Blueprint $table) {
    $table->id();

    $table->foreignId('company_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('image_url', 500);

    $table->enum('image_type', [
        'logo',
        'factory',
        'product',
        'certificate',
        'banner',
        'office',
        'other'
    ])->default('product');

    $table->string('title')->nullable();

    $table->integer('sort_order')->default(0);

    $table->timestamp('created_at')->nullable();

    $table->index('image_type');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_images');
    }
};