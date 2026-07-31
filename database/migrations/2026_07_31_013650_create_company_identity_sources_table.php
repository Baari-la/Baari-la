<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Map canonical company identities to their source company records.
     *
     * Example:
     *
     * KAHATEX
     *   -> companies.id 14
     *   -> companies.id 116
     *   -> companies.id 344
     *   -> companies.id 677
     *   -> companies.id 1040
     *   -> companies.id 1476
     *
     * No legacy company record is modified or deleted.
     */
    public function up(): void
    {
        Schema::create(
            'company_identity_sources',
            function (Blueprint $table) {
                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Canonical Identity
                |--------------------------------------------------------------------------
                */

                $table->foreignId('company_identity_id')
                    ->constrained('company_identities')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Legacy Company Record
                |--------------------------------------------------------------------------
                |
                | This points to the original companies.id.
                |
                | One legacy company record should belong to at most one
                | canonical identity.
                |
                */

                $table->integer('company_id');

                /*
                |--------------------------------------------------------------------------
                | Source Metadata
                |--------------------------------------------------------------------------
                */

                $table->string('source_type', 50)
                    ->default('legacy_directory');

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
                */

                $table->unique('company_id');

                $table->unique([
                    'company_identity_id',
                    'company_id',
                ]);

                $table->index('source_type');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'company_identity_sources'
        );
    }
};