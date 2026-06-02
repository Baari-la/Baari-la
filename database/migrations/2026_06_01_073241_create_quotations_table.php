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
       Schema::create('quotations', function (Blueprint $table) {

    $table->id();

    $table->foreignId('rfq_id');

    $table->integer('company_id');

    $table->decimal(
        'unit_price',
        15,
        2
    );

    $table->decimal(
        'minimum_order_quantity',
        15,
        2
    )->nullable();

    $table->integer('lead_time_days')
        ->nullable();

    $table->text('remarks')
        ->nullable();

    $table->enum(
        'status',
        [
            'submitted',
            'accepted',
            'rejected'
        ]
    )->default('submitted');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};