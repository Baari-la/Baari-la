<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'company_identity_businesses',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Canonical Company
                |--------------------------------------------------------------------------
                */

                $table->foreignId('company_identity_id')
                    ->unique()
                    ->constrained('company_identities')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Company Overview
                |--------------------------------------------------------------------------
                */

                $table->text('business_description')
                    ->nullable();

                $table->year('year_established')
                    ->nullable();

                $table->string('legal_entity')
                    ->nullable();

                $table->string('employee_range')
                    ->nullable();

                $table->unsignedInteger('factory_count')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Business Model
                |--------------------------------------------------------------------------
                */

                $table->boolean('is_fiber_producer')
                    ->default(false);

                $table->boolean('is_spinner')
                    ->default(false);

                $table->boolean('is_weaving')
                    ->default(false);

                $table->boolean('is_knitting')
                    ->default(false);

                $table->boolean('is_dyeing_finishing')
                    ->default(false);

                $table->boolean('is_printing')
                    ->default(false);

                $table->boolean('is_garment')
                    ->default(false);

                $table->boolean('is_trader')
                    ->default(false);

                $table->boolean('is_brand')
                    ->default(false);

                $table->boolean('is_buying_office')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Business Strategy
                |--------------------------------------------------------------------------
                */

                $table->boolean('oem')
                    ->default(false);

                $table->boolean('odm')
                    ->default(false);

                $table->boolean('obm')
                    ->default(false);

                $table->boolean('private_label')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Market
                |--------------------------------------------------------------------------
                */

                $table->boolean('domestic_market')
                    ->default(true);

                $table->boolean('export_market')
                    ->default(false);

                $table->unsignedTinyInteger('export_experience_years')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Sustainability
                |--------------------------------------------------------------------------
                */

                $table->boolean('esg_program')
                    ->default(false);

                $table->boolean('renewable_energy')
                    ->default(false);

                $table->boolean('recycled_material')
                    ->default(false);

                $table->boolean('wastewater_treatment')
                    ->default(false);

                $table->text('sustainability_notes')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $table->foreignId('last_updated_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp('last_reviewed_at')
                    ->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'company_identity_businesses'
        );
    }
};