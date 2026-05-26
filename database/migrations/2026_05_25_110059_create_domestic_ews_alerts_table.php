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
        Schema::create('domestic_ews_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('commodity_name'); // Contoh: Polyester Yarn / Cotton Fabric
            $table->string('hs_prefix'); // Contoh: 5205 / 5402
            $table->integer('monthly_container_count'); // Jumlah kontainer impor bulan berjalan
            $table->integer('danger_threshold'); // Batas aman kontainer (Misal: 100 kontainer)
            $table->string('risk_level'); // CRITICAL, WARNING, SAFE
            $table->integer('days_to_market'); // Estimasi hari barang membanjiri pasar (Misal: 45 hari)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domestic_ews_alerts');
    }
};