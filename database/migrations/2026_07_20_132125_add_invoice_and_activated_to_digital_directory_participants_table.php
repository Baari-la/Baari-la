<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'digital_directory_participants',
            function (
                Blueprint $table
            ) {

                $table->string(
                    'invoice_number'
                )
                ->nullable()
                ->after(
                    'currency'
                );

                $table->timestamp(
                    'activated_at'
                )
                ->nullable()
                ->after(
                    'payment_verified_at'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'digital_directory_participants',
            function (
                Blueprint $table
            ) {

                $table->dropColumn([
                    'invoice_number',
                    'activated_at',
                ]);
            }
        );
    }
};