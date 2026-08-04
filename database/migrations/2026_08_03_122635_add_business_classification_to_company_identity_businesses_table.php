<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_identity_businesses', function (Blueprint $table) {

            $table->string('primary_business_category', 50)
                ->nullable()
                ->after('is_chemical_supplier');

            $table->json('secondary_business_categories')
                ->nullable()
                ->after('primary_business_category');

            $table->string('value_chain_position', 30)
                ->nullable()
                ->after('secondary_business_categories');
        });
    }

    public function down(): void
    {
        Schema::table('company_identity_businesses', function (Blueprint $table) {

            $table->dropColumn([
                'primary_business_category',
                'secondary_business_categories',
                'value_chain_position',
            ]);

        });
    }
};