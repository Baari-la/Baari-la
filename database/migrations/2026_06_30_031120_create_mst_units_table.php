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
        Schema::create('mst_units', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Unit Identification
            |--------------------------------------------------------------------------
            */
            $table->string('unit_code', 20)->unique();
            $table->string('symbol', 20);

            /*
            |--------------------------------------------------------------------------
            | Bilingual Name
            |--------------------------------------------------------------------------
            */
            $table->string('unit_name_en', 100);
            $table->string('unit_name_id', 100);

            /*
            |--------------------------------------------------------------------------
            | Category
            | Weight, Length, Area, Quantity, Packaging, Textile
            |--------------------------------------------------------------------------
            */
            $table->string('category', 50)->index();

            /*
            |--------------------------------------------------------------------------
            | Base Unit
            | Example:
            | KG  -> KG
            | GR  -> KG
            | TON -> KG
            | YD  -> MTR
            | PCS -> PCS
            |--------------------------------------------------------------------------
            */
            $table->string('base_unit', 20)->index();

            /*
            |--------------------------------------------------------------------------
            | Conversion Factor
            |--------------------------------------------------------------------------
            */
            $table->decimal('conversion_factor', 18, 8)
                ->default(1);

            /*
            |--------------------------------------------------------------------------
            | Decimal Precision
            |--------------------------------------------------------------------------
            */
            $table->unsignedTinyInteger('decimal_precision')
                ->default(2);

            /*
            |--------------------------------------------------------------------------
            | Measurement System
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_metric')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Textile Default Unit
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_textile_default')
                ->default(false)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Active Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */
            $table->text('description')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Additional Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_units');
    }
};