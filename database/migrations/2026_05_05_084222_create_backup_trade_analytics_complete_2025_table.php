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
    {if (!Schema::hasTable('backup_trade_analytics_complete_2025')) {
        Schema::create('backup_trade_analytics_complete_2025', function (Blueprint $table) {
            $table->integer('id_trade')->default(0);
            $table->enum('tipe_arus', ['ekspor', 'impor']);
            $table->enum('dimensi', ['hscode', 'country']);
            $table->string('produk', 100)->nullable();
            $table->integer('id_hs')->nullable();
            $table->integer('id_negara')->nullable();
            $table->double('val_2019')->nullable()->default(0);
            $table->double('val_2020')->nullable()->default(0);
            $table->double('val_2021')->nullable()->default(0);
            $table->double('val_2022')->nullable()->default(0);
            $table->double('val_2023')->nullable()->default(0);
            $table->double('val_2024')->nullable()->default(0);
            $table->double('val_2025')->nullable()->default(0);
            $table->double('val_jandes_2024')->nullable()->default(0);
            $table->double('val_jandes_2025')->nullable()->default(0);
            $table->double('vol_2019')->nullable()->default(0);
            $table->double('vol_2020')->nullable()->default(0);
            $table->double('vol_2021')->nullable()->default(0);
            $table->double('vol_2022')->nullable()->default(0);
            $table->double('vol_2023')->nullable()->default(0);
            $table->double('vol_2024')->nullable()->default(0);
            $table->double('vol_2025')->nullable()->default(0);
            $table->double('vol_jandes_2024')->nullable()->default(0);
            $table->double('vol_jandes_2025')->nullable()->default(0);
            $table->double('prediksi_val_2026')->nullable();
            $table->double('cagr_score')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->double('val_2025_01')->nullable()->default(0);
            $table->double('val_2025_02')->nullable()->default(0);
            $table->double('val_2025_03')->nullable()->default(0);
            $table->double('val_2025_04')->nullable()->default(0);
            $table->double('val_2025_05')->nullable()->default(0);
            $table->double('val_2025_06')->nullable()->default(0);
            $table->double('val_2025_07')->nullable()->default(0);
            $table->double('val_2025_08')->nullable()->default(0);
            $table->double('val_2025_09')->nullable()->default(0);
            $table->double('val_2025_10')->nullable()->default(0);
            $table->double('val_2025_11')->nullable()->default(0);
            $table->double('val_2025_12')->nullable()->default(0);
            $table->double('vol_2025_01')->nullable()->default(0);
            $table->double('vol_2025_02')->nullable()->default(0);
            $table->double('vol_2025_03')->nullable()->default(0);
            $table->double('vol_2025_04')->nullable()->default(0);
            $table->double('vol_2025_05')->nullable()->default(0);
            $table->double('vol_2025_06')->nullable()->default(0);
            $table->double('vol_2025_07')->nullable()->default(0);
            $table->double('vol_2025_08')->nullable()->default(0);
            $table->double('vol_2025_09')->nullable()->default(0);
            $table->double('vol_2025_10')->nullable()->default(0);
            $table->double('vol_2025_11')->nullable()->default(0);
            $table->double('vol_2025_12')->nullable()->default(0);
            $table->double('val_2026_01')->nullable()->default(0);
            $table->double('val_2026_02')->nullable()->default(0);
            $table->double('vol_2026_01')->nullable()->default(0);
            $table->double('vol_2026_02')->nullable()->default(0);
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_trade_analytics_complete_2025');
    }
};