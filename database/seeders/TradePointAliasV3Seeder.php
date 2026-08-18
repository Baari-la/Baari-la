<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TradePointAliasV3Seeder extends Seeder
{
    public function run(): void
    {
        $aliases = [
            'TANJUNG BALAI ASAHAN' => [
                'canonical_code' =>
                    'TP-TANJUNG-BALAI-ASAHAN-PORT',
            ],

            'TANJUNG PINANG' => [
                'canonical_code' =>
                    'TP-TANJUNG-PINANG-PORT',
            ],

            'TEMBILAHAN' => [
                'canonical_code' =>
                    'TP-TEMBILAHAN-PORT',
            ],

            'TENAU' => [
                'canonical_code' =>
                    'TP-TENAU-PORT',
            ],

            'KUALA ENOK' => [
                'canonical_code' =>
                    'TP-KUALA-ENOK-PORT',
            ],

            'BANDARA INTERNASIONAL LOMBOK' => [
                'canonical_code' =>
                    'TP-BANDARA-INTERNASIONAL-LOMBOK',
            ],
        ];

        $processed = 0;
        $skipped = 0;

        DB::transaction(function () use (
            $aliases,
            &$processed,
            &$skipped
        ): void {
            foreach ($aliases as $sourceName => $mapping) {

                $normalizedName = mb_strtoupper(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim($sourceName)
                    ) ?? ''
                );

                $canonicalCode = strtoupper(
                    trim(
                        (string) (
                            $mapping['canonical_code']
                            ?? ''
                        )
                    )
                );

                if (
                    $normalizedName === ''
                    ||
                    $canonicalCode === ''
                ) {
                    throw new RuntimeException(
                        "Alias tidak lengkap: {$sourceName}"
                    );
                }

                $tradePoint = DB::table('trade_points')
                    ->where(
                        'code',
                        $canonicalCode
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->first();

                if ($tradePoint === null) {
                    throw new RuntimeException(
                        "Canonical Trade Point tidak ditemukan: "
                        . $canonicalCode
                    );
                }

                DB::table('trade_point_aliases')
                    ->updateOrInsert(
                        [
                            'source_system' =>
                                'KEMENDAG',

                            'normalized_name' =>
                                $normalizedName,
                        ],
                        [
                            'trade_point_id' =>
                                $tradePoint->id,

                            'source_name' =>
                                $sourceName,

                            'is_active' =>
                                true,

                            'updated_at' =>
                                now(),

                            'created_at' =>
                                now(),
                        ]
                    );

                $processed++;
            }
        });

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "TRADE POINT ALIAS V3 SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Aliases processed : {$processed}" . PHP_EOL;
        echo "Rows skipped      : {$skipped}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}