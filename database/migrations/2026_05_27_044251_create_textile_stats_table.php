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
        Schema::create('textile_stats', function (Blueprint $table) {
            $table->id();
            $table->date('period');                          // Format: Y-m-d (Contoh: 2025-12-01)
            $table->enum('type', ['import', 'export']);      // Memisahkan data Impor / Ekspor
            $table->string('reporter_code', 3)->index();     // Kode PBB (360: Indo, 156: China, dll)
            $table->string('country_name');                  // Nama negara (Indonesia, Vietnam, dll)
            $table->string('hs_code', 6)->index();           // Kode HS (2 atau 4 digit, misal: 61)
            $table->string('hs_description');                // Uraian resmi komoditas tekstil
            $table->unsignedBigInteger('volume_kg')->default(0); // Total berat dalam Kilogram
            $table->unsignedBigInteger('value_usd')->default(0); // Total nilai dalam USD
            $table->timestamps();

            // INDEX GABUNGAN: Kunci kecepatan performa grafik Multi-Line di React
            $table->index(['period', 'type', 'reporter_code', 'hs_code'], 'idx_textile_analytics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textile_stats');
    }
};