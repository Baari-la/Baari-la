<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini

return new class extends Migration
{
    public function up(): void
    {
        // Perintah SQL murni untuk membuat tabel secara paksa tanpa lewat gerbang urutan Laravel
        DB::statement("
            CREATE TABLE IF NOT EXISTS regulations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                speaker VARCHAR(255) NOT NULL,
                category VARCHAR(255) NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                access_tier ENUM('Public', 'Member', 'Premium') DEFAULT 'Member',
                event_date DATE NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};