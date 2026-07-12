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
        Schema::create('company_social_compliances', function (Blueprint $table) {

    $table->id();

    // Legacy Database Compatible
    $table->integer('company_id')->index();

    $table->string('standard_name');

    $table->string('certificate_number')->nullable();

    $table->string('issued_by')->nullable();

    $table->date('issue_date')->nullable();

    $table->date('expiry_date')->nullable();

    $table->string('status')->default('active');

    $table->text('remarks')->nullable();

    $table->timestamps();

    $table->foreign('company_id')
        ->references('id')
        ->on('companies')
        ->cascadeOnDelete();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_social_compliances');
    }
};