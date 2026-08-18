<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('countries');
    }

    public function down(): void
    {
        Schema::create('countries', function ($table) {
            $table->id();

            $table->string('code', 20)
                ->nullable()
                ->unique();

            $table->string('name', 150);

            $table->string('name_en', 150)
                ->nullable();

            $table->string('region', 100)
                ->nullable();

            $table->string('subregion', 100)
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index('name');
            $table->index('name_en');
            $table->index('region');
            $table->index('subregion');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }
};