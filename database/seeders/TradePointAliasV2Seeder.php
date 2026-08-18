<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TradePointAliasV2Seeder extends Seeder
{
    public function run(): void
    {
        $base =
            getenv('USERPROFILE')
            . DIRECTORY_SEPARATOR
            . 'Desktop'
            . DIRECTORY_SEPARATOR
            . 'DIGESTEX_DATA';

        $curatedFile =
            $base
            . DIRECTORY_SEPARATOR
            . 'PROCESSED'
            . DIRECTORY_SEPARATOR
            . 'trade_point_residual_curated_2019.csv';

        if (!is_file($curatedFile)) {
            throw new RuntimeException(
                "Curated Trade Point file tidak ditemukan:\n{$curatedFile}"
            );
        }

        $handle = fopen($curatedFile, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                "Tidak dapat membuka:\n{$curatedFile}"
            );
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            throw new RuntimeException(
                'Header curated Trade Point tidak ditemukan.'
            );
        }

        $columns = [];

        foreach ($header as $index => $name) {
            $columns[trim((string) $name)] = $index;
        }

        foreach ([
            'trade_point_source',
            'normalized_name',
            'canonical_code',
            'review_status',
            'confidence',
        ] as $required) {
            if (!isset($columns[$required])) {
                fclose($handle);

                throw new RuntimeException(
                    "Column tidak ditemukan: {$required}"
                );
            }
        }

        $processed = 0;
        $skipped = 0;

        DB::transaction(function () use (
            $handle,
            $columns,
            &$processed,
            &$skipped
        ): void {
            while (($row = fgetcsv($handle)) !== false) {

                $reviewStatus = strtoupper(
                    trim(
                        (string) $row[
                            $columns['review_status']
                        ]
                    )
                );

                if ($reviewStatus !== 'APPROVED') {
                    $skipped++;
                    continue;
                }

                $sourceName = trim(
                    (string) $row[
                        $columns['trade_point_source']
                    ]
                );

                $normalizedName = trim(
                    (string) $row[
                        $columns['normalized_name']
                    ]
                );

                $canonicalCode = strtoupper(
                    trim(
                        (string) $row[
                            $columns['canonical_code']
                        ]
                    )
                );

                $confidence = strtoupper(
                    trim(
                        (string) $row[
                            $columns['confidence']
                        ]
                    )
                );

                if (
                    $sourceName === ''
                    ||
                    $normalizedName === ''
                    ||
                    $canonicalCode === ''
                ) {
                    throw new RuntimeException(
                        "Approved alias memiliki field kosong: "
                        . $sourceName
                    );
                }

                if ($confidence !== 'HIGH') {
                    throw new RuntimeException(
                        "Approved alias bukan HIGH confidence: "
                        . $sourceName
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Canonical target must exist
                |--------------------------------------------------------------------------
                */

                $tradePoint = DB::table('trade_points')
                    ->where('code', $canonicalCode)
                    ->where('is_active', true)
                    ->first();

                if ($tradePoint === null) {
                    throw new RuntimeException(
                        "Canonical Trade Point tidak ditemukan: "
                        . $canonicalCode
                        . " untuk alias "
                        . $sourceName
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Upsert alias
                |--------------------------------------------------------------------------
                */

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

        fclose($handle);

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "TRADE POINT ALIAS V2 SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Approved aliases processed : {$processed}" . PHP_EOL;
        echo "Rows skipped               : {$skipped}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}