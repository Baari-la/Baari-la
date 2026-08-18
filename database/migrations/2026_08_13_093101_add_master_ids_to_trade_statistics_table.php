<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('trade_statistics', 'province_id')) {
            Schema::table('trade_statistics', function (Blueprint $table) {
                $table->foreignId('province_id')
                    ->nullable()
                    ->after('province_name')
                    ->constrained('provinces')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('trade_statistics', 'trade_point_id')) {
            Schema::table('trade_statistics', function (Blueprint $table) {
                $table->foreignId('trade_point_id')
                    ->nullable()
                    ->after('port_name')
                    ->constrained('trade_points')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('trade_statistics', 'trade_point_type_id')) {
            Schema::table('trade_statistics', function (Blueprint $table) {
                $table->foreignId('trade_point_type_id')
                    ->nullable()
                    ->after('trade_point_id')
                    ->constrained('trade_point_types')
                    ->nullOnDelete();
            });
        }

        Schema::table('trade_statistics', function (Blueprint $table) {

            $table->index(
                'province_id',
                'idx_trade_statistics_province_id'
            );

            $table->index(
                'trade_point_id',
                'idx_trade_statistics_trade_point_id'
            );

            $table->index(
                'trade_point_type_id',
                'idx_trade_statistics_trade_point_type_id'
            );
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('trade_statistics', 'province_id')) {
            Schema::table('trade_statistics', function (Blueprint $table) {
                $table->dropForeign(['province_id']);
                $table->dropIndex('idx_trade_statistics_province_id');
                $table->dropColumn('province_id');
            });
        }

        if (Schema::hasColumn('trade_statistics', 'trade_point_id')) {
            Schema::table('trade_statistics', function (Blueprint $table) {
                $table->dropForeign(['trade_point_id']);
                $table->dropIndex('idx_trade_statistics_trade_point_id');
                $table->dropColumn('trade_point_id');
            });
        }

        if (Schema::hasColumn('trade_statistics', 'trade_point_type_id')) {
            Schema::table('trade_statistics', function (Blueprint $table) {
                $table->dropForeign(['trade_point_type_id']);
                $table->dropIndex('idx_trade_statistics_trade_point_type_id');
                $table->dropColumn('trade_point_type_id');
            });
        }
    }
};