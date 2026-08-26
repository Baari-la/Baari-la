<?php

declare(strict_types=1);

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
        Schema::create('garment_conversion_factors', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Factor Identity
            |--------------------------------------------------------------------------
            */

            // HS-8 specific. Never a global garment factor.
            $table->string('hs_code', 8);

            // Canonical methodology:
            // KG_PER_PCS = kilogram per garment piece.
            $table->string('methodology', 50);

            $table->decimal('factor', 12, 6);

            /*
            |--------------------------------------------------------------------------
            | Evidence Definition
            |--------------------------------------------------------------------------
            */

            $table->string('evidence_type', 100)->nullable();

            $table->string('weight_unit', 20)->nullable();

            $table->unsignedInteger('evidence_count');

            $table->unsignedInteger('total_sample_size');

            /*
            |--------------------------------------------------------------------------
            | Calculation Provenance
            |--------------------------------------------------------------------------
            */

            $table->string('calculation_method', 100)->nullable();

            $table->decimal('observed_minimum', 12, 6)->nullable();

            $table->decimal('observed_maximum', 12, 6)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Evidence Snapshot
            |--------------------------------------------------------------------------
            |
            | Historical snapshot of the evidence references used
            | when this factor was approved and activated.
            |
            */

            $table->json('evidence_references');

            /*
            |--------------------------------------------------------------------------
            | Approval Governance
            |--------------------------------------------------------------------------
            */

            $table->string('reviewer', 150)->nullable();

            $table->string('reviewer_role', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Activation Governance
            |--------------------------------------------------------------------------
            */

            $table->string('activator', 150)->nullable();

            $table->string('activator_role', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Lifecycle Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30)->default('ACTIVE');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['hs_code', 'methodology'],
                'gcf_hs_methodology_index'
            );

            $table->index(
                ['hs_code', 'status'],
                'gcf_hs_status_index'
            );

            /*
            |--------------------------------------------------------------------------
            | Active Factor Uniqueness
            |--------------------------------------------------------------------------
            |
            | Prevent more than one ACTIVE factor for the same
            | HS-8 + methodology combination.
            |
            */

            $table->unique(
                ['hs_code', 'methodology', 'status'],
                'gcf_hs_methodology_status_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garment_conversion_factors');
    }
};