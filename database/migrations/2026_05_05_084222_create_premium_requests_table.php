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
        Schema::create('premium_requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('company_name')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->nullable()->default('pending');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_requests');
    }
};
