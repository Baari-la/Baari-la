<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TradePointMasterExpansionSeeder extends Seeder
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
            . 'trade_point_master_expansion_review_2019.csv';

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
            $columns[
                trim((string) $name)
            ] = $index;
        }

        foreach ([
            'candidate_code',
            'candidate_name',
            'candidate_name_en',
            'trade_point_type_code',
            'trade_point_type_id',
            'physical_province_code',
            'city',
            'overall_status',
        ] as $required) {
            if (!isset($columns[$required])) {
                fclose($handle);

                throw new RuntimeException(
                    "Column tidak ditemukan: {$required}"
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Master lookup
        |--------------------------------------------------------------------------
        */

        $typeIds = DB::table('trade_point_types')
    ->where('is_active', true)
    ->pluck('id', 'code')
    ->toArray();

$provinceIds = DB::table('provinces')
    ->where('is_active', true)
    ->pluck('id', 'code')
    ->toArray();

$nextSortOrder =
    ((int) DB::table('trade_points')->max('sort_order')) + 1;

        $processed = 0;
        $skipped = 0;

        DB::transaction(function () use (
            $handle,
            $columns,
            $typeIds,
            $provinceIds,
            &$processed,
            &$skipped,
            &$nextSortOrder
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

                $code = strtoupper(
                    trim(
                        (string) $row[
                            $columns['candidate_code']
                        ]
                    )
                );

                $name = trim(
                    (string) $row[
                        $columns['candidate_name']
                    ]
                );

                $nameEn = trim(
                    (string) $row[
                        $columns['candidate_name_en']
                    ]
                );

                $typeCode = strtoupper(
                    trim(
                        (string) $row[
                            $columns['trade_point_type_code']
                        ]
                    )
                );

                $provinceCode = strtoupper(
                    trim(
                        (string) $row[
                            $columns['physical_province_code']
                        ]
                    )
                );

                $city = trim(
                    (string) $row[
                        $columns['city']
                    ]
                );

                if (
                    $code === ''
                    ||
                    $name === ''
                    ||
                    $nameEn === ''
                    ||
                    $typeCode === ''
                    ||
                    $provinceCode === ''
                    ||
                    $city === ''
                ) {
                    throw new RuntimeException(
                        "Canonical candidate memiliki field wajib kosong: "
                        . $code
                    );
                }

                if (!isset($typeIds[$typeCode])) {
                    throw new RuntimeException(
                        "Trade point type tidak ditemukan: "
                        . $typeCode
                    );
                }

                if (!isset($provinceIds[$provinceCode])) {
                    throw new RuntimeException(
                        "Province code tidak ditemukan: "
                        . $provinceCode
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Final collision protection
                |--------------------------------------------------------------------------
                */

                $existingCode = DB::table('trade_points')
                    ->where('code', $code)
                    ->first();

                if ($existingCode !== null) {
                    $skipped++;
                    continue;
                }

                $existingName = DB::table('trade_points')
                    ->where('name', $name)
                    ->first();

                if ($existingName !== null) {
                    $skipped++;
                    continue;
                }

                DB::table('trade_points')->insert([
                'code' =>
                    $code,

                'name' =>
                    $name,

                'name_en' =>
                    $nameEn,

                'trade_point_type_id' =>
                    $typeIds[$typeCode],

                'province_id' =>
                    $provinceIds[$provinceCode],

                'city' =>
                    $city,

                'is_active' =>
                    true,

                'sort_order' =>
                    $nextSortOrder++,

                'created_at' =>
                    now(),

                'updated_at' =>
                    now(),
            ]);

                $processed++;
            }
        });

        fclose($handle);

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "DIGESTEX TRADE POINT MASTER EXPANSION SEED" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Canonical records inserted : {$processed}" . PHP_EOL;
        echo "Rows skipped               : {$skipped}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}