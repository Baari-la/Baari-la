<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Canonical Company Identity
            |--------------------------------------------------------------------------
            |
            | company_id remains available for legacy compatibility.
            |
            | company_identity_id represents the canonical DIGESTEX company
            | identity that the user is authorized to manage.
            |
            */

            $table->foreignId('company_identity_id')
                ->nullable()
                ->after('company_id')
                ->constrained('company_identities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign([
                'company_identity_id',
            ]);

            $table->dropColumn(
                'company_identity_id'
            );
        });
    }
};