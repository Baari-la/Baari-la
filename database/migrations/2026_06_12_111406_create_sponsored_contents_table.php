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
        Schema::create('sponsored_contents', function (
    Blueprint $table
) {

    $table->id();

    $table->foreignId(
        'industry_partner_id'
    )->constrained()
        ->cascadeOnDelete();

    $table->string('title');

    $table->string('slug')
        ->unique();

    $table->longText('content');

    $table->string(
        'featured_image'
    )->nullable();

    $table->enum(
        'content_type',
        [
            'article',
            'whitepaper',
            'case_study',
            'market_report',
        ]
    )->default('article');

    $table->timestamp(
        'published_at'
    )->nullable();

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
        Schema::dropIfExists('sponsored_contents');
    }
};