<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industry_partner_solutions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('industry_partner_id')
                ->constrained('industry_partners')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SOLUTION IDENTITY
            |--------------------------------------------------------------------------
            */

            $table->string('title');

            $table->string('slug');

            /*
            |--------------------------------------------------------------------------
            | SOLUTION CONTENT
            |--------------------------------------------------------------------------
            */

            $table->text('short_description')->nullable();

            $table->text('problem_solved')->nullable();

            $table->longText('solution_description')->nullable();

            $table->text('industry_applications')->nullable();

            $table->text('technology')->nullable();

            $table->text('key_benefits')->nullable();

            /*
            |--------------------------------------------------------------------------
            | VISIBILITY
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_featured')
                ->default(false);

            $table->boolean('is_active')
                ->default(false);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index(
                'industry_partner_id'
            );

            $table->index(
                'slug'
            );

            $table->index(
                'is_active'
            );

            $table->index([
                'industry_partner_id',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'industry_partner_solutions'
        );
    }
};