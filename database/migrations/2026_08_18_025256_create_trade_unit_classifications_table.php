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
        Schema::create('trade_unit_classifications', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | HS-8 Identity
            |--------------------------------------------------------------------------
            */

            $table->string('hs_code', 8)->unique();
            $table->text('hs_description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DIGESTEX Classification
            |--------------------------------------------------------------------------
            */

            $table->string('sector', 50)->nullable();
            $table->string('product_type', 100)->nullable();
            $table->string('product_group', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Trade Unit
            |--------------------------------------------------------------------------
            */

            $table->string('official_unit', 20)->nullable();
            $table->string('intelligence_unit', 20)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Conversion Control
            |--------------------------------------------------------------------------
            */

            $table->boolean('conversion_enabled')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Governance
            |--------------------------------------------------------------------------
            */

            $table->string('status', 20)
                ->default('active');

            $table->string('classification_source')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('sector');
            $table->index('product_type');
            $table->index('intelligence_unit');
            $table->index('conversion_enabled');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'trade_unit_classifications'
        );
    }
};