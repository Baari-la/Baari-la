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
        // Drop FK lama
        Schema::table('company_products', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        // Ubah tipe kolom
        DB::statement("
            ALTER TABLE company_products
            MODIFY company_id INT(11) NOT NULL
        ");

        // Tambah FK baru
        Schema::table('company_products', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('company_products', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
    }
};