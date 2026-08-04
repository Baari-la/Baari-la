<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'company_identity_capability_profiles',
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
                | Production Capacity
                |--------------------------------------------------------------------------
                */

                $table->string('production_capacity')
                    ->nullable();

                $table->string('production_capacity_unit')
                    ->nullable();

                $table->string('monthly_capacity')
                    ->nullable();

                $table->string('annual_capacity')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Commercial Capability
                |--------------------------------------------------------------------------
                */

                $table->string('minimum_order_quantity')
                    ->nullable();

                $table->string('minimum_order_unit')
                    ->nullable();

                $table->integer('lead_time_days')
                    ->nullable();

                $table->boolean('sampling_service')
                    ->default(false);

                $table->boolean('export_ready')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Manufacturing Services
                |--------------------------------------------------------------------------
                */

                $table->boolean('supports_oem')
                    ->default(false);

                $table->boolean('supports_odm')
                    ->default(false);

                $table->boolean('supports_private_label')
                    ->default(false);

                $table->boolean('supports_full_package')
                    ->default(false);

                $table->boolean('supports_cmt')
                    ->default(false);

                $table->boolean('supports_design_support')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Production Flexibility
                |--------------------------------------------------------------------------
                */

                $table->boolean('supports_small_batch')
                    ->default(false);

                $table->boolean('supports_fast_sampling')
                    ->default(false);

                $table->boolean('supports_quick_response')
                    ->default(false);

                $table->boolean('supports_custom_development')
                    ->default(false);

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
            'company_identity_capability_profiles'
        );
    }
};