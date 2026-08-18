<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeCountryAliasSeeder extends Seeder
{
    public function run(): void
    {
        $curatedFile =
            base_path('config/trade_country_curated.php');

        if (!is_file($curatedFile)) {
            throw new \RuntimeException(
                'config/trade_country_curated.php tidak ditemukan.'
            );
        }

        $curated = require $curatedFile;

        if (!is_array($curated)) {
            throw new \RuntimeException(
                'trade_country_curated.php harus mengembalikan array.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Load country master
        |--------------------------------------------------------------------------
        */

        $countries = DB::table('mst_countries')
            ->pluck('id', 'country_code')
            ->toArray();

        if (empty($countries)) {
            throw new \RuntimeException(
                'mst_countries kosong.'
            );
        }

        $processed = 0;

        DB::transaction(function () use (
            $curated,
            $countries,
            &$processed
        ): void {

            foreach ($curated as $sourceName => $mapping) {

                $countryCode =
                    strtoupper(
                        trim(
                            (string) (
                                $mapping['country_code']
                                ?? ''
                            )
                        )
                    );

                if ($countryCode === '') {
                    throw new \RuntimeException(
                        "Country code kosong untuk alias: {$sourceName}"
                    );
                }

                if (!isset($countries[$countryCode])) {
                    throw new \RuntimeException(
                        "Country code tidak ditemukan di mst_countries: "
                        . $countryCode
                    );
                }

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

                DB::table('trade_country_aliases')
                    ->updateOrInsert(
                        [
                            'source_system' =>
                                'KEMENDAG',

                            'normalized_name' =>
                                $normalized,
                        ],
                        [
                            'country_id' =>
                                $countries[$countryCode],

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
        echo "TRADE COUNTRY ALIAS SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Aliases processed : "
            . $processed
            . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}