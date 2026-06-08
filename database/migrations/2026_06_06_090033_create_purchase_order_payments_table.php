<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'purchase_order_payments',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('purchase_order_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('paid_by')
                    ->constrained('users')
                    ->cascadeOnDelete();

                $table->string('payment_reference')
                    ->nullable();

                $table->decimal(
                    'amount',
                    15,
                    2
                );

                $table->string(
                    'currency',
                    10
                )->default('USD');

                $table->enum(
                    'payment_method',
                    [
                        'bank_transfer',
                        'letter_of_credit',
                        'cash',
                        'other',
                    ]
                )->default('bank_transfer');

                $table->date('payment_date')
                    ->nullable();

                $table->string('payment_proof')
                    ->nullable();

                $table->text('remarks')
                    ->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'purchase_order_payments'
        );
    }
};