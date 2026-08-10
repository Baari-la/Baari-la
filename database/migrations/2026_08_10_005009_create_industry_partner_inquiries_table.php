<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industry_partner_inquiries', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | COMPANY
            |--------------------------------------------------------------------------
            */

            $table->foreignId('industry_partner_id')
                ->nullable()
                ->constrained('industry_partners')
                ->nullOnDelete();

            $table->string('company_name');

            $table->string('website_url')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CONTACT PERSON
            |--------------------------------------------------------------------------
            */

            $table->string('contact_name');

            $table->string('job_title')->nullable();

            $table->string('email');

            $table->string('phone')->nullable();

            /*
            |--------------------------------------------------------------------------
            | PARTNERSHIP
            |--------------------------------------------------------------------------
            */

            $table->string('partner_category');

            $table->text('solution_description');

            $table->text('partnership_interest')->nullable();

            $table->text('target_market')->nullable();

            $table->text('proposed_value')->nullable();

            /*
            |--------------------------------------------------------------------------
            | WORKFLOW
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'reviewing',
                'contacted',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('admin_notes')->nullable();

            $table->timestamp('reviewed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SOURCE
            |--------------------------------------------------------------------------
            */

            $table->string('source')->nullable();

            $table->string('locale', 5)->default('id');

            $table->timestamps();

            $table->index('status');
            $table->index('partner_category');
            $table->index('company_name');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industry_partner_inquiries');
    }
};