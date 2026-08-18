<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HsCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HsUniverseSeeder extends Seeder
{
    public function run(): void
    {
        $file = getenv('USERPROFILE')
            . DIRECTORY_SEPARATOR
            . 'Desktop'
            . DIRECTORY_SEPARATOR
            . 'DIGESTEX_DATA'
            . DIRECTORY_SEPARATOR
            . 'PROCESSED'
            . DIRECTORY_SEPARATOR
            . 'hs_universe_2019_2026.csv';

        if (!is_file($file)) {
            throw new \RuntimeException(
                "HS Universe file tidak ditemukan: {$file}"
            );
        }

        $handle = fopen($file, 'rb');

        if ($handle === false) {
            throw new \RuntimeException(
                "Tidak dapat membuka file: {$file}"
            );
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            throw new \RuntimeException(
                'Header CSV tidak ditemukan.'
            );
        }

        $buffer = [];
        $bufferSize = 500;

        $inserted = 0;

        DB::transaction(function () use (
            $handle,
            &$buffer,
            $bufferSize,
            &$inserted
        ) {
            while (($row = fgetcsv($handle)) !== false) {

                if (count($row) < 2) {
                    continue;
                }

                $hsCode = trim((string) $row[0]);
                $description = trim((string) $row[1]);

                if (!preg_match('/^\d{8}$/', $hsCode)) {
                    continue;
                }

                $buffer[] = [
                    'hs_code' => $hsCode,

                    'uraian_hs_id' => $description,

                    /*
                    |--------------------------------------------------------------------------
                    | English description
                    |--------------------------------------------------------------------------
                    |
                    | Source CSV currently contains one description field.
                    | We keep it in uraian_hs_id first.
                    | English mapping can be added later from validated source.
                    |
                    */

                    'uraian_hs_en' => null,

                    'chapter' => substr($hsCode, 0, 2),

                    'heading' => substr($hsCode, 0, 4),

                    'subheading' => substr($hsCode, 0, 6),

                    'sector_id' => null,

                    'product_family' => null,

                    'is_textile' => true,

                    'is_fiber' => false,

                    'is_yarn' => false,

                    'is_fabric' => false,

                    'is_technical_textile' => false,

                    'is_apparel' => false,

                    'is_madeup' => false,

                    'is_active' => true,

                    'created_at' => now(),

                    'updated_at' => now(),
                ];

                if (count($buffer) >= $bufferSize) {
                    HsCode::upsert(
                        $buffer,
                        ['hs_code'],
                        [
                            'uraian_hs_id',
                            'uraian_hs_en',
                            'chapter',
                            'heading',
                            'subheading',
                            'updated_at',
                        ]
                    );

                    $inserted += count($buffer);

                    $buffer = [];
                }
            }

            if (!empty($buffer)) {
                HsCode::upsert(
                    $buffer,
                    ['hs_code'],
                    [
                        'uraian_hs_id',
                        'uraian_hs_en',
                        'chapter',
                        'heading',
                        'subheading',
                        'updated_at',
                    ]
                );

                $inserted += count($buffer);
            }
        });

        fclose($handle);

        echo PHP_EOL;
        echo "HS Universe imported: {$inserted}" . PHP_EOL;
    }
}