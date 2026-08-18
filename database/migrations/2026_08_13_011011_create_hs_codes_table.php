<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hs_codes', function (Blueprint $table) {
            $table->id();

            $table->string('hs_code', 20)->unique();

            $table->string('description_id', 500)->nullable();
            $table->string('description_en', 500)->nullable();

            // Hierarchy
            $table->unsignedTinyInteger('chapter')->nullable();
            $table->string('heading', 4)->nullable();
            $table->string('subheading', 6)->nullable();

            // DIGESTEX classification
            $table->foreignId('sector_id')
                ->nullable()
                ->constrained('textile_sectors')
                ->nullOnDelete();

            $table->boolean('is_textile')->default(false);
            $table->boolean('is_fiber')->default(false);
            $table->boolean('is_yarn')->default(false);
            $table->boolean('is_fabric')->default(false);
            $table->boolean('is_apparel')->default(false);
            $table->boolean('is_madeup')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('chapter');
            $table->index('heading');
            $table->index('subheading');
            $table->index('sector_id');
            $table->index('is_textile');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hs_codes');
    }
};