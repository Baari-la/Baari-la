<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Rebuild MST HSCode from scratch
        |--------------------------------------------------------------------------
        |
        | Legacy mst_hscode is intentionally retired.
        | New master will be populated from Kemendag HS Universe.
        |
        */

        Schema::dropIfExists('mst_hscode');

        Schema::create('mst_hscode', function (Blueprint $table) {
            $table->bigIncrements('id_hs');

            /*
            |--------------------------------------------------------------------------
            | HS Identity
            |--------------------------------------------------------------------------
            */

            $table->string('hs_code', 8)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | HS Description
            |--------------------------------------------------------------------------
            */

            $table->text('uraian_hs_id')
                ->nullable();

            $table->text('uraian_hs_en')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | HS Hierarchy
            |--------------------------------------------------------------------------
            */

            $table->string('chapter', 2)
                ->nullable();

            $table->string('heading', 4)
                ->nullable();

            $table->string('subheading', 6)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | DIGESTEX Global Textile Taxonomy
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('sector_id')
                ->nullable();

            $table->string('product_family', 150)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Classification Flags
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_textile')
                ->default(true);

            $table->boolean('is_fiber')
                ->default(false);

            $table->boolean('is_yarn')
                ->default(false);

            $table->boolean('is_fabric')
                ->default(false);

            $table->boolean('is_technical_textile')
                ->default(false);

            $table->boolean('is_apparel')
                ->default(false);

            $table->boolean('is_madeup')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(
                'chapter',
                'idx_mst_hscode_chapter'
            );

            $table->index(
                'heading',
                'idx_mst_hscode_heading'
            );

            $table->index(
                'subheading',
                'idx_mst_hscode_subheading'
            );

            $table->index(
                'sector_id',
                'idx_mst_hscode_sector_id'
            );

            $table->index(
                'product_family',
                'idx_mst_hscode_product_family'
            );

            $table->index(
                'is_textile',
                'idx_mst_hscode_is_textile'
            );

            $table->index(
                'is_active',
                'idx_mst_hscode_is_active'
            );

            /*
            |--------------------------------------------------------------------------
            | Taxonomy Relation
            |--------------------------------------------------------------------------
            */

            $table->foreign('sector_id')
                ->references('id')
                ->on('textile_sectors')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_hscode');

        /*
        |--------------------------------------------------------------------------
        | Restore only the legacy structure if rollback is required.
        |--------------------------------------------------------------------------
        */

        Schema::create('mst_hscode', function (Blueprint $table) {
            $table->integer('id_hs', true);

            $table->string('produk', 100)
                ->nullable();

            $table->string('hs_code', 15)
                ->nullable()
                ->unique();

            $table->text('uraian_hs_id')
                ->nullable();

            $table->text('uraian_hs_en')
                ->nullable();

            $table->enum(
                'kategori',
                [
                    'Serat',
                    'Benang',
                    'Kain',
                    'Garmen',
                    'Mesin',
                    'Lainnya',
                ]
            )->nullable();

            $table->timestamp('created_at')
                ->useCurrent();
        });
    }
};