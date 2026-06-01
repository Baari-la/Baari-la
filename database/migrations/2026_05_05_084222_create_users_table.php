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
        if (!Schema::hasTable('users')) {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('google_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role', 20)->nullable()->default('user');
            $table->enum('access_level', ['free', 'api', 'premium'])->nullable()->default('free');
            $table->enum('member_type', ['free', 'api_member', 'premium'])->nullable()->default('free');
            $table->boolean('is_premium')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('member_number')->nullable()->unique();
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};