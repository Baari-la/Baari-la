<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradePointMasterSeeder extends Seeder
{
    public function run(): void
    {
        $file =
            getenv('USERPROFILE')
            . DIRECTORY_SEPARATOR
            . 'Desktop'
            . DIRECTORY_SEPARATOR
            . 'DIGESTEX_DATA'
            . DIRECTORY_SEPARATOR
            . 'PROCESSED'
            . DIRECTORY_SEPARATOR
            . 'trade_point_canonical_master_v1.csv';

        if (!is_file($file)) {
            throw new \RuntimeException(
                "Canonical trade point master tidak ditemukan:\n{$file}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve active trade point types
        |--------------------------------------------------------------------------
        */

        $typeIds = DB::table('trade_point_types')
            ->where('is_active', true)
            ->pluck('id', 'code')
            ->toArray();

        if (empty($typeIds)) {
            throw new \RuntimeException(
                'Tidak ada active trade point types.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve active province codes
        |--------------------------------------------------------------------------
        */

        $provinceIds = DB::table('provinces')
            ->where('is_active', true)
            ->pluck('id', 'code')
            ->toArray();

        if (empty($provinceIds)) {
            throw new \RuntimeException(
                'Tidak ada active provinces.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Read CSV
        |--------------------------------------------------------------------------
        */

        $handle = fopen($file, 'rb');

        if ($handle === false) {
            throw new \RuntimeException(
                "Tidak dapat membuka:\n{$file}"
            );
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            throw new \RuntimeException(
                'Header CSV tidak ditemukan.'
            );
        }

        $columns = [];

        foreach ($header as $index => $name) {
            $columns[trim((string) $name)] = $index;
        }

        $required = [
            'code',
            'name',
            'name_en',
            'trade_point_type_code',
            'physical_province_code',
            'city',
            'status',
        ];

        foreach ($required as $column) {
            if (!array_key_exists($column, $columns)) {
                fclose($handle);

                throw new \RuntimeException(
                    "Column CSV tidak ditemukan: {$column}"
                );
            }
        }

        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {

            $status = trim(
                (string) $row[$columns['status']]
            );

            /*
            |--------------------------------------------------------------------------
            | Only approved canonical records
            |--------------------------------------------------------------------------
            */

            if ($status !== 'APPROVED') {
                continue;
            }

            $code = trim(
                (string) $row[$columns['code']]
            );

            $name = trim(
                (string) $row[$columns['name']]
            );

            $nameEn = trim(
                (string) $row[$columns['name_en']]
            );

            $typeCode = trim(
                (string) $row[
                    $columns['trade_point_type_code']
                ]
            );

            $provinceCode = trim(
                (string) $row[
                    $columns['physical_province_code']
                ]
            );

            $city = trim(
                (string) $row[$columns['city']]
            );

            if ($code === '') {
                throw new \RuntimeException(
                    'Trade point code kosong.'
                );
            }

            if ($name === '') {
                throw new \RuntimeException(
                    "Trade point name kosong untuk code: {$code}"
                );
            }

            if (!isset($typeIds[$typeCode])) {
                throw new \RuntimeException(
                    "Trade point type tidak ditemukan: {$typeCode}"
                );
            }

            if (!isset($provinceIds[$provinceCode])) {
                throw new \RuntimeException(
                    "Province code tidak ditemukan: {$provinceCode}"
                );
            }

            $rows[] = [
                'code' => $code,
                'name' => $name,
                'name_en' => $nameEn,

                'trade_point_type_id' =>
                    $typeIds[$typeCode],

                'province_id' =>
                    $provinceIds[$provinceCode],

                'city' =>
                    $city !== '' ? $city : null,

                'is_active' => true,

                'sort_order' => 0,

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($handle);

        /*
        |--------------------------------------------------------------------------
        | Master cardinality guard
        |--------------------------------------------------------------------------
        */

        if (count($rows) !== 49) {
            throw new \RuntimeException(
                'Jumlah canonical trade points bukan 49. '
                . 'Ditemukan: ' . count($rows)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Transactional upsert
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($rows): void {

            foreach ($rows as $index => $row) {

                $row['sort_order'] =
                    $index + 1;

                DB::table('trade_points')
                    ->updateOrInsert(
                        [
                            'code' =>
                                $row['code'],
                        ],
                        $row
                    );
            }
        });

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "TRADE POINT MASTER SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Canonical records processed : "
            . count($rows)
            . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}