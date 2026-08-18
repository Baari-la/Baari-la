<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TradeCountryMasterExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $base =
            getenv('USERPROFILE')
            . DIRECTORY_SEPARATOR
            . 'Desktop'
            . DIRECTORY_SEPARATOR
            . 'DIGESTEX_DATA';

        $validationFile =
            $base
            . DIRECTORY_SEPARATOR
            . 'PROCESSED'
            . DIRECTORY_SEPARATOR
            . 'trade_country_master_expansion_validation_2019.csv';

        if (!is_file($validationFile)) {
            throw new RuntimeException(
                "Validation file tidak ditemukan:\n{$validationFile}"
            );
        }

        $handle = fopen($validationFile, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                "Tidak dapat membuka:\n{$validationFile}"
            );
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            throw new RuntimeException(
                'Header validation CSV tidak ditemukan.'
            );
        }

        $columns = [];

        foreach ($header as $index => $name) {
            $columns[trim((string) $name)] = $index;
        }

        foreach ([
            'country_source',
            'candidate_iso2',
            'candidate_iso3',
            'candidate_name_en',
            'candidate_name_id',
            'entity_class',
            'overall_status',
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
                $overallStatus = strtoupper(
                    trim(
                        (string) $row[
                            $columns['overall_status']
                        ]
                    )
                );

                if ($overallStatus !== 'PASS') {
                    $skipped++;
                    continue;
                }

                $entityClass = strtoupper(
                    trim(
                        (string) $row[
                            $columns['entity_class']
                        ]
                    )
                );

                if (!in_array(
                    $entityClass,
                    ['COUNTRY', 'TERRITORY'],
                    true
                )) {
                    $skipped++;
                    continue;
                }

                $countryCode = strtoupper(
                    trim(
                        (string) $row[
                            $columns['candidate_iso2']
                        ]
                    )
                );

                $iso3 = strtoupper(
                    trim(
                        (string) $row[
                            $columns['candidate_iso3']
                        ]
                    )
                );

                $nameEn = trim(
                    (string) $row[
                        $columns['candidate_name_en']
                    ]
                );

                $nameId = trim(
                    (string) $row[
                        $columns['candidate_name_id']
                    ]
                );

                if (
                    $countryCode === ''
                    ||
                    $nameEn === ''
                    ||
                    $nameId === ''
                ) {
                    throw new RuntimeException(
                        'Candidate PASS memiliki field wajib kosong: '
                        . (string) $row[
                            $columns['country_source']
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Final duplicate protection
                |--------------------------------------------------------------------------
                */

                $existingCode = DB::table('mst_countries')
                    ->where(
                        'country_code',
                        $countryCode
                    )
                    ->first();

                if ($existingCode !== null) {
                    $skipped++;
                    continue;
                }

                if ($iso3 !== '') {
                    $existingIso3 = DB::table('mst_countries')
                        ->where('iso3', $iso3)
                        ->first();

                    if ($existingIso3 !== null) {
                        $skipped++;
                        continue;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Insert
                |--------------------------------------------------------------------------
                |
                | Keep existing mst_countries structure untouched.
                | The new records use the fields already present in the master.
                |--------------------------------------------------------------------------
                */

                DB::table('mst_countries')->insert([
                    'country_code'   => $countryCode,
                    'iso3'           => $iso3 !== '' ? $iso3 : null,
                    'country_name_en'=> $nameEn,
                    'country_name_id'=> $nameId,
                    'is_active'      => true,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $processed++;
            }
        });

        fclose($handle);

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "DIGESTEX COUNTRY MASTER EXPANSION SEED" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Records inserted : {$processed}" . PHP_EOL;
        echo "Records skipped  : {$skipped}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}