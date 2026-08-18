<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_unit_classifications', function (Blueprint $table) {
            $table->decimal('conversion_factor', 12, 6)
                ->nullable()
                ->after('conversion_enabled');

            $table->string('conversion_method', 50)
                ->nullable()
                ->after('conversion_factor');

            $table->string('conversion_source', 255)
                ->nullable()
                ->after('conversion_method');

            $table->string('conversion_confidence', 20)
                ->nullable()
                ->after('conversion_source');
        });
    }

    public function down(): void
    {
        Schema::table('trade_unit_classifications', function (Blueprint $table) {
            $table->dropColumn([
                'conversion_factor',
                'conversion_method',
                'conversion_source',
                'conversion_confidence',
            ]);
        });
    }
};