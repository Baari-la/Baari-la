<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'company_identity_sources',
            function (Blueprint $table) {
                $table->boolean('is_primary')
                    ->default(false)
                    ->after('source_type');

                $table->index(
                    ['company_identity_id', 'is_primary'],
                    'identity_source_primary_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'company_identity_sources',
            function (Blueprint $table) {
                $table->dropIndex(
                    'identity_source_primary_idx'
                );

                $table->dropColumn('is_primary');
            }
        );
    }
};