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
        Schema::create('partnerships', function (Blueprint $table) {
        $table->id();
        $table->string('name');             // e.g., "Centric Software", "PT. Loom Parts Indonesia"
        $table->string('category');         // Kategori: 'Technology', 'Machinery', 'Raw Material'
        $table->string('region');           // Wilayah: 'West Java', 'Central Java', 'Global'
        $table->string('logo_path')->nullable(); // Logo perusahaan vendor
        $table->string('match_percentage')->default('95'); // Persentase kecocokan sistem AI
        $table->string('tagline');          // Deskripsi singkat keahlian
        $table->text('description');        // Detail spesifikasi solusi/produk
        $table->string('whatsapp_contact'); // Kontak negosiasi langsung
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};