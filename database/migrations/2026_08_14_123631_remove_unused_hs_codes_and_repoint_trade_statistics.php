<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Remove legacy FK
        |--------------------------------------------------------------------------
        */

        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->dropForeign(
                'trade_statistics_hs_id_foreign'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Repoint trade_statistics.hs_id
        |    hs_codes.id → mst_hscode.id_hs
        |--------------------------------------------------------------------------
        |
        | Both columns are BIGINT UNSIGNED.
        |
        */

        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->foreign('hs_id')
                ->references('id_hs')
                ->on('mst_hscode')
                ->nullOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Remove unused hs_codes table
        |--------------------------------------------------------------------------
        |
        | hs_codes is an unused migration result.
        | Canonical HS master is mst_hscode.
        |
        */

        Schema::dropIfExists('hs_codes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Recreate hs_codes
        |--------------------------------------------------------------------------
        |
        | This rollback restores the table structure, but does not restore
        | the old hs_codes data because the table was intentionally unused
        | and currently contains no production records.
        |
        */

        if (!Schema::hasTable('hs_codes')) {
            Schema::create('hs_codes', function (Blueprint $table) {
                $table->id();

                $table->string('hs_code', 20)->unique();

                $table->string('description_id', 500)->nullable();
                $table->string('description_en', 500)->nullable();

                $table->unsignedTinyInteger('chapter')->nullable();
                $table->string('heading', 4)->nullable();
                $table->string('subheading', 6)->nullable();

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

        /*
        |--------------------------------------------------------------------------
        | Remove canonical FK
        |--------------------------------------------------------------------------
        */

        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->dropForeign([
                'hs_id',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Restore old FK
        |--------------------------------------------------------------------------
        */

        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->foreign('hs_id')
                ->references('id')
                ->on('hs_codes')
                ->nullOnDelete();
        });
    }
};