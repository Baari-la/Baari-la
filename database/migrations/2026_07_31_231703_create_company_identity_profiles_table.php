<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_identity_profiles', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Canonical Identity
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_identity_id')
                ->unique()
                ->constrained('company_identities')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Company Information
            |--------------------------------------------------------------------------
            */

            $table->text('phone');

            $table->string('phone', 1000)->nullable();
            $table->string('website')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 50)->nullable();

            $table->text('address')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Profile Metadata
            |--------------------------------------------------------------------------
            |
            | owner_entered:
            | Information entered/reviewed by an authorized company user.
            |
            */

            $table->string('data_source')
                ->default('owner_entered');

            $table->timestamp('last_reviewed_at')
                ->nullable();

            $table->foreignId('last_updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'company_identity_profiles'
        );
    }
};