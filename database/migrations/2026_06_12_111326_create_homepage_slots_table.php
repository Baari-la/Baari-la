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
        Schema::create('homepage_slots', function (
    Blueprint $table
) {

    $table->id();

    $table->foreignId(
        'industry_partner_id'
    )->constrained()
        ->cascadeOnDelete();

    $table->enum(
        'slot_type',
        [
            'featured_partner',
            'industry_solution',
            'sponsored_insight',
            'company_spotlight',
        ]
    );

    $table->string('title');

    $table->text('description')
        ->nullable();

    $table->string('banner_image')
        ->nullable();

    $table->string('cta_text')
        ->nullable();

    $table->string('cta_url')
        ->nullable();

    $table->integer('display_order')
        ->default(0);

    $table->date('start_date')
        ->nullable();

    $table->date('end_date')
        ->nullable();

    $table->boolean('is_active')
        ->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_slots');
    }
};