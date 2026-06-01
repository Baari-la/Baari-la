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
        Schema::table('company_machines', function (Blueprint $table) {

    $table->string('machine_type')->nullable();

    $table->decimal(
        'production_capacity',
        15,
        2
    )->nullable();

    $table->string(
        'capacity_unit'
    )->nullable();

    $table->string(
        'working_width'
    )->nullable();

    $table->string(
        'gauge_specification'
    )->nullable();

    $table->string(
        'machine_condition'
    )->nullable();

    $table->string(
        'automation_level'
    )->nullable();

    $table->boolean(
        'is_active'
    )->default(true);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};