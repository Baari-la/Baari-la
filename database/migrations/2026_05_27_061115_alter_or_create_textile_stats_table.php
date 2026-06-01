<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop tabel lama jika ada agar struktur diperbarui bersih tanpa error
        Schema::dropIfExists('textile_stats');

        Schema::create('textile_stats', function (Blueprint $table) {
            $table->id();
            $table->date('period');                          
            $table->enum('type', ['import', 'export']);      
            $table->string('reporter_code', 3)->index();     
            $table->string('country_name');                  
            $table->string('hs_code', 10)->index();          // Bisa menampung 2, 4, sampai 6 digit
            $table->integer('hs_digits');                    // Penanda level: 2, 4, atau 6 digit
            $table->text('hs_description');                  // Uraian HS lengkap dari PBB
            $table->double('volume_kg')->default(0);         // Menggunakan double agar presisi desimal
            $table->double('value_usd')->default(0);         // Kolom Nilai Transaksi USD resmi
            $table->timestamps();

            $table->index(['period', 'type', 'reporter_code', 'hs_code'], 'idx_analytics_v2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('textile_stats');
    }
};