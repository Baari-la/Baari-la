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
        Schema::create('company_identity_trade_profiles', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Canonical Company
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_identity_id')
                ->constrained('company_identities')
                ->cascadeOnDelete()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Trade Roles™
            |--------------------------------------------------------------------------
            |
            | Launch Ready
            |
            */

            $table->json('trade_roles')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Export Experience
            |--------------------------------------------------------------------------
            */

            $table->string('export_experience')
                ->nullable();

            $table->year('export_since')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Trade Geography
            |--------------------------------------------------------------------------
            */

            $table->json('export_countries')
                ->nullable();

            $table->json('import_countries')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Supply Chain Intelligence™
            |--------------------------------------------------------------------------
            */

            $table->json('main_industries')
                ->nullable();

            $table->json('domestic_markets')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Future Trade Intelligence™
            |--------------------------------------------------------------------------
            |
            | Phase 2
            | (disiapkan dari sekarang)
            |
            */

            $table->json('export_products')
                ->nullable();

            $table->json('import_products')
                ->nullable();

            $table->text('trade_notes')
                ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Trade Profile Score™
                |--------------------------------------------------------------------------
                |
                | 0 - 40
                |
                | Used by:
                |
                | Buyer Trust™
                | Visibility™
                | Sourcing Hub™
                |
                */

            $table->unsignedTinyInteger('trade_profile_score')
                ->default(0);

                  /*
            |--------------------------------------------------------------------------
            | Verification™
            |--------------------------------------------------------------------------
            */

            $table->string('verification_status')
                ->default('draft');

            $table->timestamp('verified_at')
                ->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
     
            $table->timestamp('last_reviewed_at')
            ->nullable();
            
                     
                /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique('company_identity_id');

            $table->index('export_experience');

            $table->index('trade_profile_score');
         
       });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'company_identity_trade_profiles'
        );
    }
};