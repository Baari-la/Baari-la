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
        Schema::create('company_lead_times', function (Blueprint $table) {
    $table->id();

    $table->integer('company_id');

    $table->string('lead_time_type')->nullable();

    $table->integer('days')->nullable();

    $table->text('notes')->nullable();

    $table->timestamps();

    $table->index('company_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_lead_times');
    }
};