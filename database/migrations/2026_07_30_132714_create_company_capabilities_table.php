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
        Schema::create('company_capabilities', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | companies.id is legacy INT(11) SIGNED.
            | Therefore company_id MUST use integer(), not foreignId().
            |
            */

            $table->integer('company_id');

            /*
            |--------------------------------------------------------------------------
            | Capability
            |--------------------------------------------------------------------------
            |
            | Canonical business capability used by DIGESTEX intelligence.
            |
            | Examples:
            |
            | yarn_spinner
            | weaving_mill
            | knitting_mill
            | dyeing_finishing_mill
            | printing_mill
            | garment_manufacturer
            | fiber_manufacturer
            | textile_machinery_supplier
            |
            */

            $table->string('capability', 100);

            /*
            |--------------------------------------------------------------------------
            | Primary Capability
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_primary')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Data Provenance
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | legacy_directory
            | digital_directory
            | company_submission
            | admin_verified
            |
            */

            $table->string('source', 50)
                ->default('legacy_directory');

            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_verified')
                ->default(false);

            $table->timestamp('verified_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Constraints & Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique(
                ['company_id', 'capability'],
                'company_capabilities_company_capability_unique'
            );

            $table->index(
                'capability',
                'company_capabilities_capability_index'
            );

            $table->index(
                'is_verified',
                'company_capabilities_verified_index'
            );

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_capabilities');
    }
};