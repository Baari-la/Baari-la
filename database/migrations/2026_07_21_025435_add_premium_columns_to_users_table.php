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
    Schema::table('users', function (Blueprint $table) {

        if (!Schema::hasColumn('users', 'is_premium')) {
            $table->boolean('is_premium')->default(false);
        }

        if (!Schema::hasColumn('users', 'member_number')) {
            $table->string('member_number')->nullable();
        }

        if (!Schema::hasColumn('users', 'member_type')) {
            $table->enum(
                'member_type',
                [
                    'free',
                    'api_member',
                    'premium',
                ]
            )->default('free');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};