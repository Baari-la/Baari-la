<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add manufacturing operation fields to company_factories.
     */
    public function up(): void
    {
        Schema::table('company_factories', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Manufacturing Operations
            |--------------------------------------------------------------------------
            |
            | Factory operational information used by Digital Factory Passport™,
            | Visibility Score™, Executive Dashboard™, Smart Business Matching™,
            | and Build My Supply Chain™.
            |
            */

            $table->unsignedInteger('production_lines')
                ->nullable()
                ->after('number_of_buildings');

            $table->unsignedTinyInteger('number_of_shifts')
                ->nullable()
                ->after('production_lines');

            $table->text('quality_control_system')
                ->nullable()
                ->after('number_of_shifts');

            $table->json('compliance_standards')
                ->nullable()
                ->after('quality_control_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_factories', function (Blueprint $table) {

            $table->dropColumn([
                'production_lines',
                'number_of_shifts',
                'quality_control_system',
                'compliance_standards',
            ]);

        });
    }
};