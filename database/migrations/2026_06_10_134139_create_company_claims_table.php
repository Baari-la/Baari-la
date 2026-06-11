<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_claims', function (
            Blueprint $table
        ) {

            $table->id();

            $table->integer(
                'company_id'
            );

            $table->unsignedBigInteger(
                'user_id'
            );

            $table->string(
                'full_name'
            );

            $table->string(
                'position'
            )->nullable();

            $table->string(
                'email'
            );

            $table->string(
                'phone'
            )->nullable();

            $table->text(
                'notes'
            )->nullable();

            $table->enum(
                'status',
                [
                    'pending',
                    'approved',
                    'rejected',
                ]
            )->default(
                'pending'
            );

            $table->timestamp(
                'submitted_at'
            )->nullable();

            $table->timestamp(
                'reviewed_at'
            )->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'company_claims'
        );
    }
};