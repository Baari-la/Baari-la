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
        Schema::create('company_capacities', function (Blueprint $table) {

    $table->id();

    $table->unsignedInteger('company_id');

    $table->foreign('company_id')
        ->references('id')
        ->on('companies')
        ->onDelete('cascade');
            // Jenis kapasitas
            $table->string('capacity_type');
            // spinning
            // weaving
            // knitting
            // garment
            // dyeing
            // printing

            // Nama item / line
            $table->string('item_name')->nullable();

            // Nilai kapasitas
            $table->decimal('capacity_value', 15, 2)->nullable();

            // Unit
            $table->string('capacity_unit')->nullable();
            // kg/day
            // ton/month
            // pcs/month

            // Installed / actual / idle
            $table->enum('capacity_category', [
                'installed',
                'actual',
                'idle'
            ])->default('installed');

            // Shift info
            $table->string('shift_info')->nullable();

            // Jumlah mesin
            $table->integer('machine_count')->nullable();

            // Catatan
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_capacities');
    }
};