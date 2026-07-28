<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_claims', function (Blueprint $table) {

            $table->string('nib')
                ->nullable()
                ->after('phone');

            $table->string('verification_document_type')
                ->nullable()
                ->after('nib');

            $table->string('verification_document')
                ->nullable()
                ->after('verification_document_type');

            $table->unsignedBigInteger('reviewed_by')
                ->nullable()
                ->after('reviewed_at');

            $table->text('rejection_reason')
                ->nullable()
                ->after('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('company_claims', function (Blueprint $table) {

            $table->dropColumn([
                'nib',
                'verification_document_type',
                'verification_document',
                'reviewed_by',
                'rejection_reason',
            ]);
        });
    }
};