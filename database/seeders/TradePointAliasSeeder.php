<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradePointAliasSeeder extends Seeder
{
    public function run(): void
    {
        $curatedFile =
            base_path('config/trade_point_curated.php');

        if (!is_file($curatedFile)) {
            throw new \RuntimeException(
                'config/trade_point_curated.php tidak ditemukan.'
            );
        }

        $curated = require $curatedFile;

        if (!is_array($curated)) {
            throw new \RuntimeException(
                'trade_point_curated.php harus mengembalikan array.'
            );
        }

        $tradePoints = DB::table('trade_points')
            ->pluck('id', 'code')
            ->toArray();

        $processed = 0;

        DB::transaction(function () use (
            $curated,
            $tradePoints,
            &$processed
        ): void {

            foreach ($curated as $sourceName => $mapping) {

                $canonicalName =
                    $mapping['canonical_name'] ?? null;

                $typeCode =
                    $mapping['type_code'] ?? null;

                $provinceCode =
                    $mapping['physical_province_code'] ?? null;

                if (
                    !$canonicalName
                    || !$typeCode
                    || !$provinceCode
                ) {
                    throw new \RuntimeException(
                        "Mapping tidak lengkap: {$sourceName}"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Canonical trade point code
                |--------------------------------------------------------------------------
                */

                $slug = preg_replace(
                    '/[^A-Z0-9]+/',
                    '-',
                    strtoupper($canonicalName)
                ) ?? '';

                $slug = trim($slug, '-');

                $tradePointCode = 'TP-' . $slug;

                if (!isset($tradePoints[$tradePointCode])) {
                    throw new \RuntimeException(
                        "Canonical trade point tidak ditemukan: "
                        . $tradePointCode
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Normalize alias
                |--------------------------------------------------------------------------
                */

                $normalized = mb_strtoupper(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        trim($sourceName)
                    ) ?? ''
                );

                if ($normalized === '') {
                    continue;
                }

                DB::table('trade_point_aliases')
                    ->updateOrInsert(
                        [
                            'source_system' => 'KEMENDAG',
                            'normalized_name' => $normalized,
                        ],
                        [
                            'trade_point_id' =>
                                $tradePoints[
                                    $tradePointCode
                                ],

                            'source_name' =>
                                $sourceName,

                            'is_active' => true,

                            'updated_at' => now(),

                            'created_at' => now(),
                        ]
                    );

                $processed++;
            }
        });

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "TRADE POINT ALIAS SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Aliases processed : {$processed}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}