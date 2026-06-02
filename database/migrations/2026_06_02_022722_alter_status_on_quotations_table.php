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
          DB::statement("
        ALTER TABLE quotations
        MODIFY status ENUM(
            'submitted',
            'accepted',
            'rejected',
            'awarded'
        )
        DEFAULT 'submitted'
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};