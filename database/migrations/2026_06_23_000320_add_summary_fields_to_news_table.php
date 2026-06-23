<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {

            $table->text('summary_id')
                ->nullable()
                ->after('title_id');

            $table->text('summary_en')
                ->nullable()
                ->after('title_en');

        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {

            $table->dropColumn([
                'summary_id',
                'summary_en',
            ]);

        });
    }
};