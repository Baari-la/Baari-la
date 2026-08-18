<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_country_aliases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                ->constrained('mst_countries')
                ->cascadeOnDelete();

            $table->string('source_name', 255);

            $table->string('normalized_name', 255);

            $table->string('source_system', 50)
                ->default('KEMENDAG');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->unique(
                ['source_system', 'normalized_name'],
                'uq_trade_country_alias_source_name'
            );

            $table->index(
                ['country_id', 'is_active'],
                'idx_trade_country_alias_country'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_country_aliases');
    }
};