<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Canonical capabilities for a company identity.
     *
     * Capabilities are aggregated from legacy company records
     * connected through company_identity_sources.
     *
     * Example:
     *
     * KAHATEX
     *   - fiber_manufacturer
     *   - yarn_spinner
     *   - weaving_mill
     *   - knitting_mill
     *   - dyeing_finishing_mill
     *   - printing_mill
     *   - garment_manufacturer
     *
     * These records are initial legacy evidence.
     *
     * They do not mean that the capability is necessarily still
     * active today. Companies may later update their capabilities
     * through the Digital Directory & Visibility Program, followed
     * by Digestex verification.
     */
    public function up(): void
    {
        Schema::create(
            'company_identity_capabilities',
            function (Blueprint $table) {
                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Canonical Company Identity
                |--------------------------------------------------------------------------
                */

                $table->foreignId('company_identity_id')
                    ->constrained('company_identities')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Capability
                |--------------------------------------------------------------------------
                |
                | Structured capability belonging to the canonical identity.
                |
                | Examples:
                | - fiber_manufacturer
                | - yarn_spinner
                | - weaving_mill
                | - knitting_mill
                | - dyeing_finishing_mill
                | - printing_mill
                | - garment_manufacturer
                | - trading_company
                | - testing_laboratory
                | - certification_body
                |
                */

                $table->string(
                    'capability',
                    100
                );

                /*
                |--------------------------------------------------------------------------
                | Provenance
                |--------------------------------------------------------------------------
                |
                | legacy_directory
                |     Aggregated from legacy company records.
                |
                | company_updated
                |     Capability supplied or updated by the company.
                |
                | verified_by_admin
                |     Capability verified by Digestex.
                |
                */

                $table->string(
                    'source',
                    50
                )->default(
                    'legacy_directory'
                );

                /*
                |--------------------------------------------------------------------------
                | Verification
                |--------------------------------------------------------------------------
                |
                | Legacy capability evidence starts as unverified.
                |
                | Verification is intentionally independent from identity
                | resolution. A company can have a valid canonical identity
                | while its capabilities still require verification.
                |
                */

                $table->boolean(
                    'is_verified'
                )->default(false);

                $table->timestamp(
                    'verified_at'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Timestamps
                |--------------------------------------------------------------------------
                */

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | Constraints / Indexes
                |--------------------------------------------------------------------------
                |
                | A capability may occur only once for each canonical
                | company identity.
                |
                | Explicit short index names are used because MySQL limits
                | identifier names to 64 characters.
                |
                */

                $table->unique(
                    [
                        'company_identity_id',
                        'capability',
                    ],
                    'identity_capability_unique'
                );

                $table->index(
                    'capability',
                    'identity_capability_idx'
                );

                $table->index(
                    'source',
                    'identity_cap_source_idx'
                );

                $table->index(
                    'is_verified',
                    'identity_cap_verified_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'company_identity_capabilities'
        );
    }
};