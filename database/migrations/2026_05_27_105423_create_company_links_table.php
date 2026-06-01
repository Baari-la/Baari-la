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
        Schema::create('company_links', function (Blueprint $table) {
    $table->id();

    $table->foreignId('company_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('link_type', [
        'website',
        'linkedin',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'marketplace',
        'catalog',
        'other'
    ])->default('website');

    $table->string('url', 500);

    $table->timestamp('created_at')->nullable();

    $table->index('link_type');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_links');
    }
};