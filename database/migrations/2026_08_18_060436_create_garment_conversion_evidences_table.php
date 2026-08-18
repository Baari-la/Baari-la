<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_conversion_evidence', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | HS-8 Identity
            |--------------------------------------------------------------------------
            */

            $table->string('hs_code', 8)->index();

            $table->string('product_group')->nullable()->index();
            $table->string('product_type')->nullable()->index();
            $table->string('conversion_sub_group')->nullable()->index();
            $table->string('methodology')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Evidence Classification
            |--------------------------------------------------------------------------
            */

            $table->string('evidence_type', 100)->index();

            /*
            |--------------------------------------------------------------------------
            | Sampling / Weight Evidence
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('sample_size')->nullable();

            $table->decimal('average_weight', 12, 6)->nullable();
            $table->decimal('minimum_weight', 12, 6)->nullable();
            $table->decimal('maximum_weight', 12, 6)->nullable();

            $table->string('weight_unit', 20)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Product Context
            |--------------------------------------------------------------------------
            */

            $table->string('material_composition')->nullable();

            $table->text('product_specification')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Evidence Source
            |--------------------------------------------------------------------------
            */

            $table->string('source_type', 100)->nullable();
            $table->text('source_reference')->nullable();
            $table->date('source_date')->nullable();

            $table->string('country', 100)->nullable();
            $table->string('market', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            $table->string('confidence_level', 30)->nullable();

            $table->string(
                'validation_status',
                30
            )->default('PENDING')->index();

            /*
            |--------------------------------------------------------------------------
            | Review
            |--------------------------------------------------------------------------
            */

            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'hs_code',
                'evidence_type',
            ]);

            $table->index([
                'hs_code',
                'validation_status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'garment_conversion_evidence'
        );
    }
};