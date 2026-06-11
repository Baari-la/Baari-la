<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (
            Blueprint $table
        ) {

            $table->string('data_source')
                ->default('legacy_directory')
                ->after('claimed_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (
            Blueprint $table
        ) {

            $table->dropColumn(
                'data_source'
            );
        });
    }
};