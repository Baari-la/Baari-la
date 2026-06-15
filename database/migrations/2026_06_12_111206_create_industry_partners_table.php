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
        Schema::create('industry_partners', function (
    Blueprint $table
) {

    $table->id();

    $table->integer('company_id')
        ->nullable()
        ->constrained('companies')
        ->nullOnDelete();

    $table->string('company_name');

    $table->string('slug')
        ->unique();

    $table->enum(
        'partner_category',
        [
            'technology',
            'machinery',
            'testing_certification',
            'raw_material',
            'logistics',
            'finance',
            'institution',
            'association',
        ]
    );

    $table->enum(
        'partner_level',
        [
            'bronze',
            'silver',
            'gold',
            'platinum',
        ]
    )->default('bronze');

    $table->string('logo_url')
        ->nullable();

    $table->string('website_url')
        ->nullable();

    $table->text('short_description')
        ->nullable();

    $table->boolean('is_featured')
        ->default(false);

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
        Schema::dropIfExists('industry_partners');
    }
};