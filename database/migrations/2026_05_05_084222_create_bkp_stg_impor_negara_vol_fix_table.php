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
        if (!Schema::hasTable('bkp_stg_impor_negara_vol_fix')) {
        Schema::create('bkp_stg_impor_negara_vol_fix', function (Blueprint $table) {
            $table->string('produk')->nullable();
            $table->string('hs', 20)->nullable();
            $table->text('uraian_hs')->nullable();
            $table->string('nama_negara')->nullable();
            $table->integer('id_negara')->nullable();
            $table->double('val_2025_01')->nullable();
            $table->double('val_2025_02')->nullable();
            $table->double('val_2025_03')->nullable();
            $table->double('val_2025_04')->nullable();
            $table->double('val_2025_05')->nullable();
            $table->double('val_2025_06')->nullable();
            $table->double('val_2025_07')->nullable();
            $table->double('val_2025_08')->nullable();
            $table->double('val_2025_09')->nullable();
            $table->double('val_2025_10')->nullable();
            $table->double('val_2025_11')->nullable();
            $table->double('val_2025_12')->nullable();
            $table->double('vol_2025_01')->nullable();
            $table->double('vol_2025_02')->nullable();
            $table->double('vol_2025_03')->nullable();
            $table->double('vol_2025_04')->nullable();
            $table->double('vol_2025_05')->nullable();
            $table->double('vol_2025_06')->nullable();
            $table->double('vol_2025_07')->nullable();
            $table->double('vol_2025_08')->nullable();
            $table->double('vol_2025_09')->nullable();
            $table->double('vol_2025_10')->nullable();
            $table->double('vol_2025_11')->nullable();
            $table->double('vol_2025_12')->nullable();
            $table->double('val_2026_01')->nullable()->default(0);
            $table->double('val_2026_02')->nullable()->default(0);
            $table->double('vol_2026_01')->nullable()->default(0);
            $table->double('vol_2026_02')->nullable()->default(0);
            $table->integer('id_hs')->nullable();
            $table->string('tipe_arus', 50)->nullable();
            $table->string('dimensi', 50)->nullable();
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bkp_stg_impor_negara_vol_fix');
    }
};