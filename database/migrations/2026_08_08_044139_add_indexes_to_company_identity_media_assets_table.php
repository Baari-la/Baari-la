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
        Schema::table('company_identity_media_assets', function (Blueprint $table) {
            $table->index(
                ['company_identity_id', 'media_type'],
                'media_identity_type_idx'
            );

            $table->index(
                'verification_status',
                'company_identity_media_assets_verification_status_idx'
            );

            $table->index(
                'is_featured',
                'company_identity_media_assets_is_featured_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_identity_media_assets', function (Blueprint $table) {
            $table->dropIndex('media_identity_type_idx');

            $table->dropIndex(
                'company_identity_media_assets_verification_status_idx'
            );

            $table->dropIndex(
                'company_identity_media_assets_is_featured_idx'
            );
        });
    }
};