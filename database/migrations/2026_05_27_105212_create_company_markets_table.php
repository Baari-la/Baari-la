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
        Schema::create('company_markets', function (Blueprint $table) {
    $table->id();

    $table->foreignId('company_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('country_name');

    $table->enum('market_type', [
        'export',
        'import',
        'domestic'
    ])->default('export');

    $table->timestamp('created_at')->nullable();

    $table->index('country_name');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_markets');
    }
};