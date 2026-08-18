<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_point_aliases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trade_point_id');

            $table->string('source_name', 255);

            $table->string('normalized_name', 255);

            $table->string('source_system', 50)
                ->default('KEMENDAG');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->foreign('trade_point_id')
                ->references('id')
                ->on('trade_points')
                ->cascadeOnDelete();

            $table->unique(
                ['source_system', 'normalized_name'],
                'uq_trade_point_alias_source_name'
            );

            $table->index(
                ['trade_point_id', 'is_active'],
                'idx_trade_point_alias_trade_point'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_point_aliases');
    }
};