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
        Schema::create('company_contacts', function (Blueprint $table) {
    $table->id();

    $table->foreignId('company_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('contact_name')->nullable();

    $table->string('position')->nullable();

    $table->string('phone')->nullable();

    $table->string('whatsapp')->nullable();

    $table->string('email')->nullable();

    $table->string('photo_url')->nullable();

    $table->boolean('is_primary')->default(false);

    $table->timestamps();

    $table->index('contact_name');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_contacts');
    }
};