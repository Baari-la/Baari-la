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
        if (!Schema::hasTable('premium_requests')) {
        Schema::table('premium_requests', function (Blueprint $table) {
            $table->foreign(['user_id'], 'premium_requests_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_requests', function (Blueprint $table) {
            $table->dropForeign('premium_requests_ibfk_1');
        });
    }
};