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
        Schema::create('member_inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('fk_member_user');
            $table->string('nama_material')->nullable();
            $table->enum('kategori', ['Serat', 'Benang', 'Kain', 'Aksesoris'])->nullable()->default('Kain');
            $table->enum('satuan', ['Kg', 'Meter', 'Yard', 'Pcs'])->nullable()->default('Kg');
            $table->double('stok_awal')->nullable()->default(0);
            $table->double('stok_masuk')->nullable()->default(0);
            $table->double('stok_keluar')->nullable()->default(0);
            $table->double('stok_akhir')->nullable()->storedAs('`stok_awal` + `stok_masuk` - `stok_keluar`');
            $table->double('buffer_stock')->nullable()->default(100);
            $table->string('lokasi_gudang', 100)->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_inventory');
    }
};
