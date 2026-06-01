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
        if (!Schema::hasTable('mst_hscode')) {
        Schema::create('mst_hscode', function (Blueprint $table) {
            $table->integer('id_hs', true);
            $table->string('produk', 100)->nullable();
            $table->string('hs_code', 15)->nullable()->unique('hs_code');
            $table->text('uraian_hs_id')->nullable();
            $table->text('uraian_hs_en')->nullable();
            $table->enum('kategori', ['Serat', 'Benang', 'Kain', 'Garmen', 'Mesin', 'Lainnya'])->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_hscode');
    }
};