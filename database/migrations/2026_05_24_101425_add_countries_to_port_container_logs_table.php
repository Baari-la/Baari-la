<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('port_container_logs', function (Blueprint $table) {
            // Menambahkan 3 kolom intelijen baru untuk melacak geografi logistik
            $table->string('logistics_date')->nullable()->after('container_no'); // Tanggal manifes riil
            $table->string('country_origin')->nullable()->after('port_name'); // Negara Asal (Impor)
            $table->string('country_destination')->nullable()->after('country_origin'); // Negara Tujuan (Ekspor)
        });
    }

    public function down(): void
    {
        Schema::table('port_container_logs', function (Blueprint $table) {
            $table->dropColumn(['logistics_date', 'country_origin', 'country_destination']);
        });
    }
};