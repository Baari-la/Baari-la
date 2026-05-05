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
        Schema::create('mst_negara', function (Blueprint $table) {
            $table->integer('id_negara', true);
            $table->string('nama_negara', 100)->nullable()->unique('nama_negara');
            $table->string('kawasan', 100)->nullable();
            $table->string('kode_negara_iso', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_negara');
    }
};
