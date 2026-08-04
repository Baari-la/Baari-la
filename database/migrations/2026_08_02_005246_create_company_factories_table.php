<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create company_factories table.
     *
     * CompanyFactory menjadi Aggregate Root
     * untuk seluruh Manufacturing Domain.
     */
    public function up(): void
    {
        Schema::create('company_factories', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Global Identity
            |--------------------------------------------------------------------------
            |
            | Used for API, QR Code, Digital Passport and public integration.
            |
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Canonical Company
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_identity_id')
            ->constrained('company_identities')
            ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Public Identity
            |--------------------------------------------------------------------------
            |
            | Used for public URL and SEO.
            |
            */

            $table->string('factory_slug')
            ->nullable()
            ->index();
                    
                /*
            |--------------------------------------------------------------------------
            | Factory Identity
            |--------------------------------------------------------------------------
            */

            $table->string('factory_code')
                ->nullable()
                ->index();

            $table->string('factory_name');
            

            $table->string('factory_type')
                ->nullable()
                ->index();

            $table->string('factory_status')
                ->default('ACTIVE')
                ->index();

            $table->boolean('is_headquarters')
                ->default(false);

            $table->boolean('is_main_factory')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Factory Location
            |--------------------------------------------------------------------------
            */

            $table->string('country', 100)->nullable();
            $table->string('province', 150)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            /*
            |--------------------------------------------------------------------------
            | General Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedSmallInteger('factory_established_year')->nullable();
            $table->decimal('land_area_sqm', 12, 2)->nullable();
            $table->decimal('building_area_sqm', 12, 2)->nullable();
            $table->unsignedInteger('number_of_buildings')->default(1);
            $table->unsignedTinyInteger('display_order')->default(0);
                
            /*
            |--------------------------------------------------------------------------
            | Digital Factory Passport
            |--------------------------------------------------------------------------
            */

            $table->string('visibility_status')
                ->default('VISIBILITY_PRIVATE');

            $table->string('verification_status')
                ->default('VERIFICATION_PENDING');

            $table->timestamp('verified_at')
                ->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
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

          /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'country',
                'province',
                'city',
            ]);

            $table->index([
                'company_identity_id',
                'factory_status',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Timestamps & Soft Deletes
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_factories');
    }
};