<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mst_countries', function (Blueprint $table) {

            // Rename existing columns
            $table->renameColumn('country_name', 'country_name_en');
            $table->renameColumn('region', 'region_en');
            $table->renameColumn('sub_region', 'sub_region_en');

        });

        Schema::table('mst_countries', function (Blueprint $table) {

            $table->char('iso3', 3)
                ->nullable()
                ->after('country_code');

            $table->string('country_name_id')
                ->nullable()
                ->after('country_name_en');

            $table->string('official_name')
                ->nullable()
                ->after('country_name_id');

            $table->string('region_code', 20)
                ->nullable()
                ->after('official_name');

            $table->string('region_id')
                ->nullable()
                ->after('region_en');

            $table->string('sub_region_id')
                ->nullable()
                ->after('sub_region_en');
        });
    }

    public function down(): void
    {
        Schema::table('mst_countries', function (Blueprint $table) {

            $table->dropColumn([
                'iso3',
                'country_name_id',
                'official_name',
                'region_code',
                'region_id',
                'sub_region_id',
            ]);

        });

        Schema::table('mst_countries', function (Blueprint $table) {

            $table->renameColumn('country_name_en', 'country_name');
            $table->renameColumn('region_en', 'region');
            $table->renameColumn('sub_region_en', 'sub_region');

        });
    }
};