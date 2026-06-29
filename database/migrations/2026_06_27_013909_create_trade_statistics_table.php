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
        Schema::create('trade_statistics', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | IMPORT INFORMATION
            |--------------------------------------------------------------------------
            */

            // Kemendag, BPS, UN Comtrade, ITC Trade Map, dll.
            $table->string('source', 50)->default('Kemendag');

            // Digunakan untuk audit proses import
            $table->unsignedBigInteger('import_batch_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TRADE INFORMATION
            |--------------------------------------------------------------------------
            */

            // export | import
            $table->string('trade_flow', 20);

            $table->smallInteger('year');
            $table->tinyInteger('month');

            // country | hs | product
            $table->string('dimension', 30);

            /*
            |--------------------------------------------------------------------------
            | PRODUCT INFORMATION
            |--------------------------------------------------------------------------
            */

            // Cotton Yarn, Polyester Yarn, Denim Fabric, dll.
            $table->string('product', 100)->nullable();

            // Fiber | Yarn | Fabric | Garment
            $table->string('product_category', 50)->nullable();

            // Upstream | Spinning | Weaving | Knitting | Dyeing | Garment
            $table->string('industry_segment', 50)->nullable();

            /*
            |--------------------------------------------------------------------------
            | HS INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('hs_code', 20);

            $table->text('hs_description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | COUNTRY INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('country_code', 5)->nullable();

            $table->string('country_name', 150)->nullable();

            $table->unsignedBigInteger('country_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TRADE DATA
            |--------------------------------------------------------------------------
            */

            // Nilai perdagangan
            $table->decimal('trade_value', 20, 2)->default(0);

            // USD
            $table->string('currency', 10)->default('USD');

            // Volume perdagangan
            $table->decimal('trade_volume', 20, 2)->default(0);

            // KG | PCS | M2 | TON
            $table->string('volume_unit', 20)->default('KG');

            /*
            |--------------------------------------------------------------------------
            | RELEASE INFORMATION
            |--------------------------------------------------------------------------
            */

            // Tanggal resmi rilis data
            $table->date('release_date')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            // Dashboard utama
            $table->index(
                ['trade_flow', 'year', 'month'],
                'idx_trade_period'
            );

            $table->index('product', 'idx_product');

            $table->index('product_category', 'idx_product_category');

            $table->index('industry_segment', 'idx_industry_segment');

            $table->index('hs_code', 'idx_hs_code');

            $table->index('country_code', 'idx_country_code');

            $table->index('country_id', 'idx_country_id');

            $table->index('source', 'idx_source');

            $table->index('release_date', 'idx_release_date');

            $table->index('import_batch_id', 'idx_import_batch');

            /*
            |--------------------------------------------------------------------------
            | UNIQUE KEY
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'source',
                'trade_flow',
                'year',
                'month',
                'hs_code',
                'country_code'
            ], 'uq_trade_statistics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_statistics');
    }
};