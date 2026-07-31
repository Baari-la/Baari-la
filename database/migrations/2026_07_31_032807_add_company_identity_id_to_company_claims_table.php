<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add canonical company identity reference to company claims.
     *
     * Existing company_id is intentionally retained for compatibility
     * with the legacy claim flow.
     *
     * New claims may reference company_identity_id so that a claim
     * belongs to one canonical company identity rather than one
     * individual legacy company record.
     */
    public function up(): void
    {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->foreignId(
                    'company_identity_id'
                )
                    ->nullable()
                    ->after('company_id')
                    ->constrained(
                        'company_identities'
                    )
                    ->nullOnDelete();
            }
        );
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->dropForeign([
                    'company_identity_id',
                ]);

                $table->dropColumn(
                    'company_identity_id'
                );
            }
        );
    }
};