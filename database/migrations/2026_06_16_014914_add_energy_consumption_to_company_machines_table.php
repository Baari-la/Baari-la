<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_machines', function (Blueprint $table) {

            $table->decimal(
                'energy_consumption',
                12,
                2
            )->nullable()
             ->after('capacity_unit');

        });
    }

    public function down(): void
    {
        Schema::table('company_machines', function (Blueprint $table) {

            $table->dropColumn(
                'energy_consumption'
            );

        });
    }
};