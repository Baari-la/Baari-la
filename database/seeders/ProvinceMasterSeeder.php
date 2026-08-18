<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceMasterSeeder extends Seeder
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
            . 'province_master_review.csv';

        if (!is_file($file)) {
            throw new \RuntimeException(
                "Province master review tidak ditemukan:\n{$file}"
            );
        }

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
            'province_code',
            'name',
            'name_en',
            'island_group',
            'region_group',
            'sort_order',
            'status',
        ];

        foreach ($required as $column) {
            if (!array_key_exists($column, $columns)) {
                fclose($handle);

                throw new \RuntimeException(
                    "Column tidak ditemukan: {$column}"
                );
            }
        }

        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {

            $status = trim(
                (string) $row[$columns['status']]
            );

            if ($status !== 'MAPPED') {
                continue;
            }

            $code = trim(
                (string) $row[$columns['province_code']]
            );

            if ($code === '') {
                continue;
            }

            $rows[] = [
                'code' => $code,

                'name' => trim(
                    (string) $row[$columns['name']]
                ),

                'name_en' => trim(
                    (string) $row[$columns['name_en']]
                ),

                'island_group' => trim(
                    (string) $row[$columns['island_group']]
                ),

                'region_group' => trim(
                    (string) $row[$columns['region_group']]
                ),

                'is_active' => true,

                'sort_order' => (int) $row[
                    $columns['sort_order']
                ],

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($handle);

        if (count($rows) !== 37) {
            throw new \RuntimeException(
                'Jumlah province hasil mapping bukan 37. '
                . 'Ditemukan: ' . count($rows)
            );
        }

        DB::transaction(function () use ($rows): void {
            foreach ($rows as $row) {
                DB::table('provinces')->updateOrInsert(
                    [
                        'code' => $row['code'],
                    ],
                    $row
                );
            }
        });

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "PROVINCE MASTER SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Provinces processed : " . count($rows) . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}