<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TradeCountryAliasV2Seeder extends Seeder
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
            . 'trade_country_alias_curated_v2.csv';

        if (!is_file($curatedFile)) {
            throw new RuntimeException(
                "Curated V2 file tidak ditemukan:\n{$curatedFile}"
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
            throw new RuntimeException(
                'mst_countries kosong.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Open curated CSV
        |--------------------------------------------------------------------------
        */

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
                'Header curated V2 tidak ditemukan.'
            );
        }

        $columns = [];

        foreach ($header as $index => $name) {
            $columns[
                trim((string) $name)
            ] = $index;
        }

        foreach ([
            'source_name',
            'normalized_name',
            'country_code',
            'country_name_en',
            'country_name_id',
            'entity_class',
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
            $countries,
            &$processed,
            &$skipped
        ): void {
            while (($row = fgetcsv($handle)) !== false) {

                $sourceName = trim(
                    (string) $row[
                        $columns['source_name']
                    ]
                );

                $normalizedName = trim(
                    (string) $row[
                        $columns['normalized_name']
                    ]
                );

                $countryCode = strtoupper(
                    trim(
                        (string) $row[
                            $columns['country_code']
                        ]
                    )
                );

                if (
                    $sourceName === ''
                    ||
                    $normalizedName === ''
                    ||
                    $countryCode === ''
                ) {
                    $skipped++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Final target validation
                |--------------------------------------------------------------------------
                */

                if (!isset($countries[$countryCode])) {
                    throw new RuntimeException(
                        "Country code tidak ditemukan di mst_countries: "
                        . "{$countryCode}"
                        . " ({$sourceName})"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Upsert alias
                |--------------------------------------------------------------------------
                */

                DB::table('trade_country_aliases')
                    ->updateOrInsert(
                        [
                            'source_system' =>
                                'KEMENDAG',

                            'normalized_name' =>
                                $normalizedName,
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

        fclose($handle);

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "TRADE COUNTRY ALIAS V2 SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Aliases processed : {$processed}" . PHP_EOL;
        echo "Rows skipped      : {$skipped}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}