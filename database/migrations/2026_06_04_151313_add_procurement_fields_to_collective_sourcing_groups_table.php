<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'collective_sourcing_groups',
            function (Blueprint $table) {

                $table->string('destination_country')
                    ->nullable()
                    ->after('currency');

                $table->date('required_delivery_date')
                    ->nullable()
                    ->after('destination_country');

                $table->date('quotation_deadline')
                    ->nullable()
                    ->after('required_delivery_date');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'collective_sourcing_groups',
            function (Blueprint $table) {

                $table->dropColumn([
                    'destination_country',
                    'required_delivery_date',
                    'quotation_deadline',
                ]);
            }
        );
    }
};