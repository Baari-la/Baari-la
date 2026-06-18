<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_machines', function (Blueprint $table) {

            $table->string('energy_unit')
                ->nullable()
                ->default('kwh/hour')
                ->after('energy_consumption');

        });
    }

    public function down(): void
    {
        Schema::table('company_machines', function (Blueprint $table) {

            $table->dropColumn('energy_unit');

        });
    }
};