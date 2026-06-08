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
        Schema::create('purchase_order_shipments', function (Blueprint $table) {

    $table->id();

    $table->foreignId('purchase_order_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('carrier')->nullable();

    $table->string('tracking_number')->nullable();

    $table->string('container_number')->nullable();

    $table->string('bl_number')->nullable();

    $table->date('etd')->nullable();

    $table->date('eta')->nullable();

    $table->string('current_location')->nullable();

    $table->text('remarks')->nullable();

    $table->foreignId('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_shipments');
    }
};