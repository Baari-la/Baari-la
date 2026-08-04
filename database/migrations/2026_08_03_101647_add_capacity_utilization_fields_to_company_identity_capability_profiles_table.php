<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_identity_capability_profiles', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Capacity Intelligence™
            |--------------------------------------------------------------------------
            */

            $table->string('current_utilized_capacity')
                ->nullable()
                ->after('production_capacity_unit');

            $table->string('current_utilized_capacity_unit')
                ->nullable()
                ->after('current_utilized_capacity');

        });
    }

    public function down(): void
    {
        Schema::table('company_identity_capability_profiles', function (Blueprint $table) {

            $table->dropColumn([
                'current_utilized_capacity',
                'current_utilized_capacity_unit',
            ]);

        });
    }
};