<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {

            // Kolom & index sudah ada
            // Tinggal tambahkan Foreign Key

            $table->foreign('import_batch_id', 'fk_trade_statistics_batch')
                ->references('id')
                ->on('trade_import_batches')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {

            $table->dropForeign('fk_trade_statistics_batch');

        });
    }
};