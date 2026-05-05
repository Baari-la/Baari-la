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
        Schema::create('perusahaan_api', function (Blueprint $table) {
            $table->integer('id_perusahaan', true);
            $table->string('nama_perusahaan');
            $table->string('nama_brand')->nullable();
            $table->string('logo_brand')->nullable();
            $table->string('katalog_file')->nullable();
            $table->string('nib', 30)->nullable();
            $table->string('email', 100)->nullable()->unique('email');
            $table->string('password')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->enum('kategori_produk', ['Serat', 'Benang', 'Kain', 'Garmen', 'Lainnya']);
            $table->text('alamat')->nullable();
            $table->text('google_maps_link')->nullable();
            $table->string('kontak_wa', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable()->default('default_company.png');
            $table->boolean('is_verified')->nullable()->default(true);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->text('deskripsi_pabrik')->nullable();
            $table->string('foto_pabrik')->nullable();
            $table->string('katalog_pdf')->nullable();
            $table->integer('total_download_katalog')->nullable()->default(0);
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('nama_direktur', 100)->nullable();
            $table->string('wa_direktur', 20)->nullable();
            $table->integer('pasar_ekspor')->nullable()->default(0);
            $table->integer('pasar_lokal')->nullable()->default(0);
            $table->string('no_anggota_api', 50)->nullable();
            $table->text('alamat_kantor')->nullable();
            $table->text('alamat_pabrik')->nullable();
            $table->double('iuran_tahunan')->nullable()->default(0);
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->date('tgl_invoice')->nullable();
            $table->date('tgl_bayar')->nullable();
            $table->enum('status_iuran', ['Lunas', 'Tertunggak'])->nullable()->default('Tertunggak');
            $table->boolean('is_premium')->nullable()->default(false);
            $table->boolean('hide_identity')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan_api');
    }
};
