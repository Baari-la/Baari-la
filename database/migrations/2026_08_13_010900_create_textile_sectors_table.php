<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('textile_sectors', function (Blueprint $table) {
            $table->id();

            $table->string('slug', 100)->unique();

            $table->string('name', 150);

            $table->string('name_en', 150)->nullable();

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Hierarchy
            |--------------------------------------------------------------------------
            |
            | parent_id memungkinkan kita membuat:
            |
            | Textile
            |   ├── Fiber
            |   ├── Yarn
            |   ├── Fabric
            |   ├── Technical Textile
            |   ├── Apparel
            |   └── Made-up Textile
            |
            */

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('textile_sectors')
                ->nullOnDelete();

            $table->unsignedTinyInteger('level')
                ->default(1);

            $table->string('icon', 50)->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index('parent_id');
            $table->index('level');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('textile_sectors');
    }
};