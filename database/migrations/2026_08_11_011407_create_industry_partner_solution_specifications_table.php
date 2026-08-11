<?<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'industry_partner_solution_specifications',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | SOLUTION
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'industry_partner_solution_id'
                );

                $table->foreign(
                    'industry_partner_solution_id',
                    'ipspec_solution_fk'
                )
                    ->references('id')
                    ->on('industry_partner_solutions')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | SPECIFICATION
                |--------------------------------------------------------------------------
                */

                $table->string('name');

                $table->text('value');

                $table->string('unit')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | DISPLAY
                |--------------------------------------------------------------------------
                */

                $table->unsignedInteger('sort_order')
                    ->default(0);

                $table->boolean('is_active')
                    ->default(true);


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | INDEXES
                |--------------------------------------------------------------------------
                */

                $table->index(
                    'industry_partner_solution_id',
                    'ipspec_solution_idx'
                );

                $table->index(
                    'is_active',
                    'ipspec_active_idx'
                );

                $table->index(
                    [
                        'industry_partner_solution_id',
                        'sort_order',
                    ],
                    'ipspec_sort_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'industry_partner_solution_specifications'
        );
    }
};