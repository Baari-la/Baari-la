<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link legacy companies to Canonical Company Identity.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Canonical Company Identity
            |--------------------------------------------------------------------------
            |
            | Links the legacy company record to the canonical company identity.
            | This becomes the bridge between the legacy company model and the
            | new Canonical Architecture.
            |
            */

            $table->foreignId('company_identity_id')
                ->nullable()
                ->after('id')
                ->constrained('company_identities')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('company_identity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->dropForeign(['company_identity_id']);

            $table->dropIndex(['company_identity_id']);

            $table->dropColumn('company_identity_id');

        });
    }
};