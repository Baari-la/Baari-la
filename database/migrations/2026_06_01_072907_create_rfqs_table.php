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
        Schema::create('rfqs', function (Blueprint $table) {

    $table->id();

    $table->foreignId('user_id');

    $table->string('rfq_number')->unique();

    $table->string('product_name');

    $table->string('hs_code')->nullable();

    $table->text('description')->nullable();

    $table->decimal(
        'required_quantity',
        15,
        2
    )->default(0);

    $table->string('unit')
        ->default('PCS');

    $table->date('required_delivery_date')
        ->nullable();

    $table->string('destination_country')
        ->nullable();

    $table->enum(
        'status',
        [
            'open',
            'quoted',
            'closed',
            'cancelled'
        ]
    )->default('open');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfqs');
    }
};