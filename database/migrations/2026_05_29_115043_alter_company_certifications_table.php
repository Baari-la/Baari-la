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
        Schema::table('company_certifications', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $table->string('category')
                ->nullable()
                ->after('certification_name');

            /*
            |--------------------------------------------------------------------------
            | CERTIFICATION CODE
            |--------------------------------------------------------------------------
            */

            $table->string('certification_code')
                ->nullable()
                ->after('category');

            /*
            |--------------------------------------------------------------------------
            | DESCRIPTION
            |--------------------------------------------------------------------------
            */

            $table->text('description')
                ->nullable()
                ->after('certificate_number');

            /*
            |--------------------------------------------------------------------------
            | CERTIFICATE FILE (PDF)
            |--------------------------------------------------------------------------
            */

            $table->string('certificate_file')
                ->nullable()
                ->after('description');

            /*
            |--------------------------------------------------------------------------
            | LOGO URL
            |--------------------------------------------------------------------------
            */

            $table->string('logo_url')
                ->nullable()
                ->after('certificate_file');

            /*
            |--------------------------------------------------------------------------
            | VERIFIED STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_verified')
                ->default(false)
                ->after('logo_url');

            /*
            |--------------------------------------------------------------------------
            | FEATURED CERTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_featured')
                ->default(false)
                ->after('is_verified');

            /*
            |--------------------------------------------------------------------------
            | SORT ORDER
            |--------------------------------------------------------------------------
            */

            $table->integer('sort_order')
                ->default(0)
                ->after('is_featured');

            /*
            |--------------------------------------------------------------------------
            | ISSUED DATE
            |--------------------------------------------------------------------------
            */

            $table->date('issued_at')
                ->nullable()
                ->after('valid_until');

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                    'active',
                    'expired',
                    'pending',
                ])
                ->default('active')
                ->after('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_certifications', function (Blueprint $table) {

            $table->dropColumn([
                'category',
                'certification_code',
                'description',
                'certificate_file',
                'logo_url',
                'is_verified',
                'is_featured',
                'sort_order',
                'issued_at',
                'status',
            ]);
        });
    }
};