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
        if (!Schema::hasTable('companies')) {
        Schema::create('companies', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nomor_anggota', 50)->nullable();
            $table->string('sektor', 100)->nullable();
            $table->string('wilayah', 100)->nullable();
            $table->string('nama_perusahaan');
            $table->text('alamat_lengkap')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('telepon')->nullable();
            $table->string('email_web')->nullable();
            $table->string('catalog_url')->nullable();
            $table->string('pimpinan')->nullable();
            $table->string('pimpinan_2')->nullable();
            $table->string('photo_pimpinan')->nullable();
            $table->string('photo_pimpinan_2')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('tenaga_kerja', 100)->nullable();
            $table->text('pasar_ekspor')->nullable();
            $table->text('produk')->nullable();
            $table->text('product_images')->nullable();
            $table->string('category', 100)->nullable()->default('General');
            $table->string('membership_type', 20)->nullable()->default('public');
            $table->string('tahun_berdiri', 4)->nullable();
            $table->enum('status_verifikasi', ['unverified', 'pending', 'verified'])->nullable()->default('unverified');
            $table->timestamp('last_verified_at')->nullable();
            $table->integer('claimed_by_user_id')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->string('stock_ready_caption')->nullable();
            $table->integer('stock_qty')->nullable()->default(0);
            $table->string('stock_unit', 20)->nullable()->default('Kg');
            $table->decimal('price', 15)->nullable()->default(0);
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};