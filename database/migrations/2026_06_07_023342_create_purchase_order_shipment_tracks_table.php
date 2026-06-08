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
        Schema::create(
    'purchase_order_shipment_tracks',
    function (Blueprint $table) {

        $table->id();

        $table->foreignId('shipment_id')
            ->constrained('purchase_order_shipments')
            ->cascadeOnDelete();

        $table->string('status');

        $table->string('location')
            ->nullable();

        $table->text('remarks')
            ->nullable();

        $table->timestamp('tracked_at');

        $table->timestamps();
    }
);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_shipment_tracks');
    }
};