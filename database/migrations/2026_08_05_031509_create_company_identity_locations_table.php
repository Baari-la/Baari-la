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
        Schema::create('company_identity_locations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_identity_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Location Identity™
            |--------------------------------------------------------------------------
            */

            $table->string('location_type', 50);

            /*
                head_office
                factory
                warehouse
                branch
            */

            $table->string('location_code', 50)->nullable();

            $table->string('location_name')->nullable();

            $table->string('location_label')->nullable();

            $table->boolean('is_primary')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            $table->text('address')->nullable();

            $table->string('country', 100)->nullable();

            $table->string('province', 100)->nullable();

            $table->string('city', 100)->nullable();

            $table->string('district', 100)->nullable();

            $table->string('subdistrict', 100)->nullable();

            $table->string('postal_code', 20)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            $table->string('contact_person')->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->string('website')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Map Intelligence™
            |--------------------------------------------------------------------------
            */

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            $table->text('google_maps_url')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Future Intelligence™
            |--------------------------------------------------------------------------
            */

            $table->json('metadata')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('display_order')->default(1);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('company_identity_id');

            $table->index('location_type');

            $table->index('country');

            $table->index('province');

            $table->index('city');

            $table->index('is_primary');

            $table->index('is_active');

            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_identity_locations');
    }
};