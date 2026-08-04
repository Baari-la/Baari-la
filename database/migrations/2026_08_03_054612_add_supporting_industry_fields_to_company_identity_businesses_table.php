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
        Schema::table('company_identity_businesses', function (Blueprint $table) {

            $table->boolean('is_testing_laboratory')
                ->default(false)
                ->after('is_buying_office');

            $table->boolean('is_certification_body')
                ->default(false)
                ->after('is_testing_laboratory');

            $table->boolean('is_machinery_supplier')
                ->default(false)
                ->after('is_certification_body');

            $table->boolean('is_accessories_supplier')
                ->default(false)
                ->after('is_machinery_supplier');

            $table->boolean('is_chemical_supplier')
                ->default(false)
                ->after('is_accessories_supplier');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_identity_businesses', function (Blueprint $table) {

            $table->dropColumn([
                'is_testing_laboratory',
                'is_certification_body',
                'is_machinery_supplier',
                'is_accessories_supplier',
                'is_chemical_supplier',
            ]);

        });
    }
};