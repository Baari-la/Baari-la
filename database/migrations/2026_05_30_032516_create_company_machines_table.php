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
       Schema::create('company_machines', function (Blueprint $table) {
    $table->id();

    $table->integer('company_id');

    $table->string('machine_category')->nullable();
    $table->string('machine_brand')->nullable();
    $table->string('machine_model')->nullable();

    $table->integer('quantity')->default(0);

    $table->year('year_installed')->nullable();

    $table->string('country_origin')->nullable();

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
        Schema::dropIfExists('company_machines');
    }
};