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
        Schema::create('rfq_files', function (Blueprint $table) {

    $table->id();

    $table->foreignId('rfq_id')
        ->constrained('rfqs')
        ->cascadeOnDelete();

    $table->string('file_path');

    $table->string('file_name');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfq_files');
    }
};