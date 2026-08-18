<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->char('trade_identity', 64)
                ->nullable()
                ->after('port_name');

            $table->unique(
                'trade_identity',
                'uq_trade_statistics_identity'
            );
        });
    }

    public function down(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->dropUnique('uq_trade_statistics_identity');
            $table->dropColumn('trade_identity');
        });
    }
};