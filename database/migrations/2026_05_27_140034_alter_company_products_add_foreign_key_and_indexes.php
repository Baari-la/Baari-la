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
        Schema::table('company_products', function (Blueprint $table) {

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
        Schema::table('company_products', function (Blueprint $table) {

            // drop foreign key
            $table->dropForeign(['company_id']);

            // drop indexes
            $table->dropIndex(['company_id']);
            $table->dropIndex(['product_name']);
            $table->dropIndex(['hs_code']);
            $table->dropIndex(['category']);

        });
    }
};