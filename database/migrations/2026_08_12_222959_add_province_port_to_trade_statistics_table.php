<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {

            // Indonesia origin/location
            $table->string('province_code', 20)
                ->nullable()
                ->after('country_id');

            $table->string('province_name', 150)
                ->nullable()
                ->after('province_code');

            // Port
            $table->string('port_code', 30)
                ->nullable()
                ->after('province_name');

            $table->string('port_name', 150)
                ->nullable()
                ->after('port_code');

            // New indexes for trade intelligence
            $table->index(
                ['trade_flow', 'year', 'month', 'hs_code'],
                'idx_trade_hs_period'
            );

            $table->index(
                ['trade_flow', 'year', 'month', 'country_code'],
                'idx_trade_country_period'
            );

            $table->index(
                ['trade_flow', 'year', 'month', 'province_code'],
                'idx_trade_province_period'
            );

            $table->index(
                ['trade_flow', 'year', 'month', 'port_code'],
                'idx_trade_port_period'
            );
        });

        // Replace legacy uniqueness rule after the new dimensions exist.
        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->dropUnique('uq_trade_statistics');

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
        });
    }

    public function down(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {

            $table->dropUnique(
                'uq_trade_statistics_detail'
            );

            $table->dropIndex(
                'idx_trade_hs_period'
            );

            $table->dropIndex(
                'idx_trade_country_period'
            );

            $table->dropIndex(
                'idx_trade_province_period'
            );

            $table->dropIndex(
                'idx_trade_port_period'
            );

            $table->dropColumn([
                'province_code',
                'province_name',
                'port_code',
                'port_name',
            ]);
        });
    }
};