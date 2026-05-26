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
        // 🚢 MEMBUAT TABEL PERMANEN DI DATABASE MYSQL UNTUK HASIL TANGKAPAN PYTHON
        Schema::create('port_container_logs', function (Blueprint $table) {
            $table->id(); // Nomor urut otomatis (Primary Key)
            $table->string('container_no'); // Nomor lambung kontainer (Contoh: MSCU-884912-4)
            $table->string('port_name'); // Nama pelabuhan asal/tujuan (Contoh: JICT Tanjung Priok)
            $table->string('hs_code'); // Kode manifes komoditas bea cukai (Contoh: 6204)
            $table->string('commodity_type'); // Deskripsi jenis barang kain/baju (Contoh: Apparel Shirts)
            $table->string('volume_unit'); // Satuan industri buatan Bapak (Contoh: PCS / PAIRS)
            $table->integer('quantity'); // Jumlah kuantitas riil di dalam kontainer
            $table->string('gate_status'); // Status pergerakan pintu gerbang (Contoh: GATE-IN FULL EKSPOR)
            $table->timestamps(); // Otomatis mencatat tanggal & jam data masuk ke database
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('port_container_logs');
    }
};