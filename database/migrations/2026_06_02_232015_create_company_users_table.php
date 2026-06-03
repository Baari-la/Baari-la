<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_users', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            $table->integer('company_id');

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('user_id');

            /*
            |--------------------------------------------------------------------------
            | Company Role
            |--------------------------------------------------------------------------
            */

            $table->enum('role', [
                'owner',
                'admin',
                'sales',
                'purchasing',
                'logistics',
                'finance',
                'staff',
            ])->default('staff');

            /*
            |--------------------------------------------------------------------------
            | Membership Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'verified',
                'rejected',
                'suspended',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Primary Contact
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_primary')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Active Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('verified_by')
                ->nullable();

            $table->timestamp('verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Join Date
            |--------------------------------------------------------------------------
            */

            $table->timestamp('joined_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('company_id');
            $table->index('user_id');
            $table->index('status');

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Membership
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'company_id',
                'user_id',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_users');
    }
};