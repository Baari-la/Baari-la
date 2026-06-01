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
        if (!Schema::hasTable('market_histories')) {
        Schema::create('market_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->unique();
            $table->decimal('cotton_price');
            $table->decimal('usd_idr');
            $table->timestamps();
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_histories');
    }
};