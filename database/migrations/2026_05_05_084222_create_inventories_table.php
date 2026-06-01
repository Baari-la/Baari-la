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
        if (!Schema::hasTable('inventories')) {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('category', 100)->nullable()->default('Fabric');
            $table->decimal('stock', 10)->nullable()->default(0);
            $table->string('unit', 50)->nullable()->default('Yard');
            $table->string('warehouse_location')->nullable();
            $table->string('whatsapp_contact', 20)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15)->nullable()->default(0);
            $table->timestamps();
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};