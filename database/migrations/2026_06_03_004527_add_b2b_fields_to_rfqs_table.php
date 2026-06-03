<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Buyer Company
            |--------------------------------------------------------------------------
            */

            $table->integer('company_id')
                ->nullable()
                ->after('user_id');

            /*
            |--------------------------------------------------------------------------
            | Trade Terms
            |--------------------------------------------------------------------------
            */

            $table->string('incoterm', 20)
                ->nullable()
                ->after('destination_country');

            $table->string('currency', 10)
                ->default('USD')
                ->after('incoterm');

            /*
            |--------------------------------------------------------------------------
            | RFQ Deadline
            |--------------------------------------------------------------------------
            */

            $table->date('quotation_deadline')
                ->nullable()
                ->after('currency');

            /*
            |--------------------------------------------------------------------------
            | Award Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamp('awarded_at')
                ->nullable()
                ->after('awarded_quotation_id');

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('company_id');
            $table->index('quotation_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {

            $table->dropIndex(['company_id']);
            $table->dropIndex(['quotation_deadline']);

            $table->dropColumn([
                'company_id',
                'incoterm',
                'currency',
                'quotation_deadline',
                'awarded_at',
            ]);
        });
    }
};