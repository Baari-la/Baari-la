<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_reviews', function (Blueprint $table) {
            $table->decimal(
                'overall_rating',
                3,
                2
            )->nullable()->after(
                'communication_rating'
            );
        });
    }

    public function down(): void
    {
        Schema::table('supplier_reviews', function (Blueprint $table) {
            $table->dropColumn(
                'overall_rating'
            );
        });
    }
};