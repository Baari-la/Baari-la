<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    
{
    Schema::table('company_claims', function (Blueprint $table) {
        $table->integer('company_id')
            ->nullable()
            ->change();
    });

    if (!Schema::hasColumn(
        'company_claims',
        'claimed_company_name'
    )) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->string('claimed_company_name')
                    ->nullable()
                    ->after('company_id');
            }
        );
    }

    if (!Schema::hasColumn('company_claims', 'nib')) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->string('nib')
                    ->nullable()
                    ->after('phone');
            }
        );
    }

    if (!Schema::hasColumn(
        'company_claims',
        'verification_document_type'
    )) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->string(
                    'verification_document_type'
                )
                    ->nullable()
                    ->after('nib');
            }
        );
    }

    if (!Schema::hasColumn(
        'company_claims',
        'verification_document'
    )) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->string(
                    'verification_document'
                )
                    ->nullable()
                    ->after(
                        'verification_document_type'
                    );
            }
        );
    }

    if (!Schema::hasColumn(
        'company_claims',
        'reviewed_by'
    )) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->unsignedBigInteger(
                    'reviewed_by'
                )
                    ->nullable()
                    ->after('reviewed_at');
            }
        );
    }

    if (!Schema::hasColumn(
        'company_claims',
        'rejection_reason'
    )) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->text('rejection_reason')
                    ->nullable()
                    ->after('reviewed_by');
            }
        );
    }
}
    public function down(): void
{
    if (Schema::hasColumn(
        'company_claims',
        'claimed_company_name'
    )) {
        Schema::table(
            'company_claims',
            function (Blueprint $table) {
                $table->dropColumn(
                    'claimed_company_name'
                );
            }
        );
    }

    Schema::table(
        'company_claims',
        function (Blueprint $table) {
            $table->integer('company_id')
                ->nullable(false)
                ->change();
        }
    );
}
};