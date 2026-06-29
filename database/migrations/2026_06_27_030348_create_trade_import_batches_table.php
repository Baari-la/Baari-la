<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_import_batches', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SOURCE INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('source', 50);

            $table->string('filename');

            /*
            |--------------------------------------------------------------------------
            | IMPORT INFORMATION
            |--------------------------------------------------------------------------
            */

            // export | import
            $table->string('trade_flow', 20);

            $table->smallInteger('year');

            $table->date('release_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | PROCESS SUMMARY
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('total_rows')->default(0);

            $table->unsignedInteger('inserted_rows')->default(0);

            $table->unsignedInteger('updated_rows')->default(0);

            $table->unsignedInteger('skipped_rows')->default(0);

            $table->unsignedInteger('failed_rows')->default(0);

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            // Processing | Completed | Failed
            $table->string('status', 30)->default('Processing');

            $table->text('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('source');

            $table->index('trade_flow');

            $table->index('year');

            $table->index('status');

            $table->index('release_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_import_batches');
    }
};