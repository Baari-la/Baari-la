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
        Schema::create('trade_master_annual_country', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('tipe_arus', ['ekspor', 'impor'])->nullable();
            $table->integer('id_negara')->nullable();
            $table->string('nama_negara', 100)->nullable();
            $table->string('produk')->nullable();
            $table->double('val_2019')->nullable();
            $table->double('val_2020')->nullable();
            $table->double('val_2021')->nullable();
            $table->double('val_2022')->nullable();
            $table->double('val_2023')->nullable();
            $table->double('val_2024')->nullable();
            $table->double('val_2025')->nullable();
            $table->double('vol_2019')->nullable();
            $table->double('vol_2020')->nullable();
            $table->double('vol_2021')->nullable();
            $table->double('vol_2022')->nullable();
            $table->double('vol_2023')->nullable();
            $table->double('vol_2024')->nullable();
            $table->double('vol_2025')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_master_annual_country');
    }
};
