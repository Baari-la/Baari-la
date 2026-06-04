<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collective_sourcing_requests', function (Blueprint $table) {

    $table->id();

    $table->foreignId('group_id')
        ->constrained('collective_sourcing_groups')
        ->cascadeOnDelete();

    $table->integer('company_id');

    $table->foreign('company_id')
        ->references('id')
        ->on('companies')
        ->cascadeOnDelete();

    $table->decimal('quantity', 15, 2);

    $table->string('required_month');

    $table->string('destination_country')
        ->nullable();

    $table->string('destination_city')
        ->nullable();

    $table->text('notes')
        ->nullable();

    $table->enum('status', [
        'pending',
        'joined',
        'cancelled',
    ])->default('joined');

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('collective_sourcing_requests');
    }
};