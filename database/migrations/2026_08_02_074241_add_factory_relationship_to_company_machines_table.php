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
        Schema::table('company_machines', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Canonical Factory Relationship
            |--------------------------------------------------------------------------
            */

            $table->foreignId('factory_id')
                ->nullable()
                ->after('company_id')
                ->constrained('company_factories')
                ->nullOnDelete();

            $table->foreignId('company_identity_id')
                ->nullable()
                ->after('factory_id')
                ->constrained('company_identities')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Factory Machine
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_primary')
                ->default(false)
                ->after('country_origin');

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('factory_id');

            $table->index('company_identity_id');

            $table->index([
                'factory_id',
                'is_primary',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_machines', function (Blueprint $table) {

            $table->dropForeign([
                'factory_id',
            ]);

            $table->dropForeign([
                'company_identity_id',
            ]);

            $table->dropIndex([
                'factory_id',
            ]);

            $table->dropIndex([
                'company_identity_id',
            ]);

            $table->dropIndex([
                'factory_id',
                'is_primary',
            ]);

            $table->dropColumn([
                'factory_id',
                'company_identity_id',
                'is_primary',
            ]);
        });
    }
};