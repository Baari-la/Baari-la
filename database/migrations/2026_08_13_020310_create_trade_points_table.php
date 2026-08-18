<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_points', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Trade Point Identity
            |--------------------------------------------------------------------------
            */

            $table->string('code', 30)
                ->nullable()
                ->unique();

            $table->string('name', 200);

            $table->string('name_en', 200)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Classification
            |--------------------------------------------------------------------------
            */

            $table->foreignId('trade_point_type_id')
                ->constrained('trade_point_types')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Indonesian Location
            |--------------------------------------------------------------------------
            */

            $table->foreignId('province_id')
                ->nullable()
                ->constrained('provinces')
                ->nullOnDelete();

            $table->string('city', 150)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('name');
            $table->index('name_en');
            $table->index('trade_point_type_id');
            $table->index('province_id');
            $table->index('city');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_points');
    }
};