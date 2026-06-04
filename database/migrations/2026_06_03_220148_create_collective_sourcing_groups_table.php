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
       Schema::create('collective_sourcing_groups', function (Blueprint $table) {

    $table->id();

    $table->string('group_code')->unique();

    $table->string('product_category');

    $table->string('product_name');

    $table->text('specification')->nullable();

    $table->string('unit')->default('KG');

    $table->decimal('moq_quantity', 15, 2);

    $table->decimal('current_quantity', 15, 2)
        ->default(0);

    $table->string('hs_code')
        ->nullable();

    $table->string('currency')
        ->default('USD');

    $table->string('incoterm')
        ->nullable();

    $table->enum('status', [
        'open',
        'moq_reached',
        'rfq_created',
        'closed',
    ])->default('open');

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collective_sourcing_groups');
    }
};