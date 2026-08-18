<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('trade_intelligence_snapshots')) {
            Schema::create('trade_intelligence_snapshots', function (Blueprint $table) {
                $table->id();

                $table->string('snapshot_key', 150);

                $table->string('snapshot_type', 50)
                    ->default('trade');

                $table->string('sector', 50)
                    ->nullable();

                $table->string('period_key', 100);

                $table->unsignedInteger('version')
                    ->default(1);

                $table->string('status', 30)
                    ->default('validated');

                $table->json('payload');

                $table->string('checksum', 64)
                    ->nullable();

                $table->timestamp('generated_at')
                    ->nullable();

                $table->timestamp('validated_at')
                    ->nullable();

                $table->timestamps();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Indexes
        |--------------------------------------------------------------------------
        */

        $indexes = collect(
            \DB::select(
                "SHOW INDEX FROM trade_intelligence_snapshots"
            )
        )->pluck('Key_name')->unique()->all();

        if (!in_array(
            'tis_key_status_version_idx',
            $indexes,
            true
        )) {
            Schema::table(
                'trade_intelligence_snapshots',
                function (Blueprint $table) {
                    $table->index(
                        [
                            'snapshot_key',
                            'status',
                            'version',
                        ],
                        'tis_key_status_version_idx'
                    );
                }
            );
        }

        if (!in_array(
            'tis_type_sector_period_idx',
            $indexes,
            true
        )) {
            Schema::table(
                'trade_intelligence_snapshots',
                function (Blueprint $table) {
                    $table->index(
                        [
                            'snapshot_type',
                            'sector',
                            'period_key',
                        ],
                        'tis_type_sector_period_idx'
                    );
                }
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'trade_intelligence_snapshots'
        );
    }
};