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
        Schema::create('purchase_order_disputes', function (Blueprint $table) {

    $table->id();

    $table->foreignId('purchase_order_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->string('dispute_number')->unique();

    $table->string('category');

    $table->text('description');

    $table->string('status')
        ->default('open');

    $table->timestamp('resolved_at')
        ->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_disputes');
    }
};