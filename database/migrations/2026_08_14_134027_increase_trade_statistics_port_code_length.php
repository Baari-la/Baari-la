<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->string('port_code', 100)
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('trade_statistics', function (Blueprint $table) {
            $table->string('port_code', 30)
                ->nullable()
                ->change();
        });
    }
};