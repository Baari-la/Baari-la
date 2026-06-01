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
        if (!Schema::hasTable('temp_impor_negara_final')) {
        Schema::create('temp_impor_negara_final', function (Blueprint $table) {
            $table->string('produk')->nullable();
            $table->string('nama_negara')->nullable();
            $table->integer('id_negara')->nullable();
            $table->integer('id_hs')->nullable();
            $table->string('tipe_arus', 50)->nullable();
            $table->string('dimensi', 50)->nullable();
            $table->double('val_2025_01')->nullable();
            $table->double('val_2025_02')->nullable();
            $table->double('val_2026_01')->nullable();
            $table->double('val_2026_02')->nullable();
            $table->double('vol_2025_01');
            $table->double('vol_2025_02');
            $table->double('vol_2026_01');
            $table->double('vol_2026_02');
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_impor_negara_final');
    }
};