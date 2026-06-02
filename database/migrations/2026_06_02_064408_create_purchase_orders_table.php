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
        Schema::create('purchase_orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('rfq_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('quotation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | COMPANY TABLE USES INT(11)
            |--------------------------------------------------------------------------
            */

            $table->integer('supplier_company_id');

$table->foreign('supplier_company_id')
    ->references('id')
    ->on('companies')
    ->cascadeOnDelete();

            $table->string('po_number')->unique();

            $table->decimal('unit_price', 15, 2);

            $table->decimal('quantity', 15, 2);

            $table->decimal('total_amount', 15, 2);

            $table->string('currency')->default('USD');

            $table->date('delivery_date')->nullable();

            $table->enum('status', [
                'pending',
                'confirmed',
                'production',
                'shipped',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};