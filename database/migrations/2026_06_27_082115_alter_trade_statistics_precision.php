<?php

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
        Schema::table('trade_statistics', function (Blueprint $table) {
    $table->decimal('trade_value', 24, 6)->default(0)->change();
    $table->decimal('trade_volume', 24, 6)->default(0)->change();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};