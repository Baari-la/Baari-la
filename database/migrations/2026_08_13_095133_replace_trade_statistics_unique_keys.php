<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Remove legacy unique keys
        |--------------------------------------------------------------------------
        */

        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->dropUnique('uq_trade_statistics_detail');
            $table->dropUnique('uq_trade_statistics_identity');
        });

        /*
        |--------------------------------------------------------------------------
        | New monthly fact uniqueness
        |--------------------------------------------------------------------------
        */

        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->unique(
                [
                    'trade_identity',
                    'year',
                    'month',
                ],
                'uq_trade_statistics_monthly'
            );
        });
    }

    public function down(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->dropUnique(
                'uq_trade_statistics_monthly'
            );

            $table->unique(
                [
                    'source',
                    'trade_flow',
                    'year',
                    'month',
                    'hs_code',
                    'country_code',
                    'province_code',
                    'port_code',
                ],
                'uq_trade_statistics_detail'
            );

            $table->unique(
                'trade_identity',
                'uq_trade_statistics_identity'
            );
        });
    }
};