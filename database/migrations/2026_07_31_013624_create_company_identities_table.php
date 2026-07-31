<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Canonical company identity layer.
     *
     * One row represents one company identity regardless of how many
     * legacy directory records originally represented that company.
     *
     * Legacy records in `companies` remain untouched.
     */
    public function up(): void
    {
        Schema::create('company_identities', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Canonical Identity
            |--------------------------------------------------------------------------
            */

            // Display name, e.g. KAHATEX
            $table->string('canonical_name');

            // Normalized key used by the identity resolver.
            // Example: KAHATEX, PT. / KAHATEX, PT -> KAHATEX
            $table->string('normalized_name')->unique();

            /*
            |--------------------------------------------------------------------------
            | Country
            |--------------------------------------------------------------------------
            |
            | Keep country at identity level because the same normalized company
            | name could theoretically occur in different countries later.
            |
            */

            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Identity Status
            |--------------------------------------------------------------------------
            |
            | READY:
            | Identity is suitable for canonical lookup.
            |
            | REVIEW:
            | Requires manual review and should not be exposed as a canonical
            | company identity yet.
            |
            */

            $table->string('identity_status', 30)
                ->default('READY')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Verification / Lifecycle
            |--------------------------------------------------------------------------
            |
            | Identity resolution is separate from company verification.
            | A canonical identity can exist before it is claimed or verified.
            |
            */

            $table->string('verification_status', 30)
                ->default('unverified')
                ->index();

            $table->timestamp('verified_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Source / Provenance
            |--------------------------------------------------------------------------
            */

            $table->string('created_from', 50)
                ->default('legacy_directory');

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('canonical_name');
            $table->index('country_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_identities');
    }
};