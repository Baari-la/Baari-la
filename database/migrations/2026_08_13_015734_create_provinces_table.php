<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Province Identity
            |--------------------------------------------------------------------------
            */

            $table->string('code', 20)->unique();

            $table->string('name', 150);

            $table->string('name_en', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Regional Grouping
            |--------------------------------------------------------------------------
            */

            $table->string('island_group', 100)->nullable();

            $table->string('region_group', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('name');
            $table->index('name_en');
            $table->index('island_group');
            $table->index('region_group');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};