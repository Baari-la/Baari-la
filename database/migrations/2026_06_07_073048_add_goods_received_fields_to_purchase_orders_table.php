<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'purchase_orders',
            function (Blueprint $table) {

                $table->timestamp('goods_received_at')
                    ->nullable()
                    ->after('completed_at');

                $table->foreignId('goods_received_by')
                    ->nullable()
                    ->after('goods_received_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'purchase_orders',
            function (Blueprint $table) {

                $table->dropForeign([
                    'goods_received_by',
                ]);

                $table->dropColumn([
                    'goods_received_at',
                    'goods_received_by',
                ]);
            }
        );
    }
};