<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HsCode;
use App\Models\TextileSector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HsClassificationSeeder extends Seeder
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
            . 'hs_classification_review_v4.csv';

        if (!is_file($file)) {
            throw new \RuntimeException(
                "Classification V4 file tidak ditemukan:\n{$file}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve primary sectors
        |--------------------------------------------------------------------------
        */

        $sectorIds = [];

        foreach ([
            'fiber',
            'yarn',
            'fabric',
            'technical-textile',
            'apparel',
            'made-up-textile',
        ] as $slug) {
            $sector = TextileSector::query()
                ->where('slug', $slug)
                ->first();

            if (!$sector) {
                throw new \RuntimeException(
                    "Textile sector tidak ditemukan: {$slug}"
                );
            }

            $sectorIds[$slug] = $sector->id;
        }

        /*
        |--------------------------------------------------------------------------
        | Read CSV
        |--------------------------------------------------------------------------
        */

        $handle = fopen($file, 'rb');

        if ($handle === false) {
            throw new \RuntimeException(
                "Tidak dapat membuka file:\n{$file}"
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
            'hs_code',
            'description',
            'suggested_sector',
            'suggested_product_family',
            'is_fiber',
            'is_yarn',
            'is_fabric',
            'is_technical_textile',
            'is_apparel',
            'is_madeup',
            'confidence',
        ];

        foreach ($required as $column) {
            if (!array_key_exists($column, $columns)) {
                fclose($handle);

                throw new \RuntimeException(
                    "Column CSV tidak ditemukan: {$column}"
                );
            }
        }

        $updated = 0;
        $skipped = 0;

        DB::transaction(function () use (
            $handle,
            $columns,
            $sectorIds,
            &$updated,
            &$skipped
        ) {
            while (($row = fgetcsv($handle)) !== false) {
                $hsCode = trim(
                    (string) $row[$columns['hs_code']]
                );

                if ($hsCode === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Only approve HIGH confidence classifications
                |--------------------------------------------------------------------------
                */

                $confidence = strtoupper(
                    trim(
                        (string) $row[$columns['confidence']]
                    )
                );

                if ($confidence !== 'HIGH') {
                    $skipped++;
                    continue;
                }

                $suggestedSector = trim(
                    (string) $row[$columns['suggested_sector']]
                );

                if (!isset($sectorIds[$suggestedSector])) {
                    $skipped++;

                    continue;
                }

                $hs = HsCode::query()
                    ->where('hs_code', $hsCode)
                    ->first();

                if (!$hs) {
                    throw new \RuntimeException(
                        "HS tidak ditemukan di mst_hscode: {$hsCode}"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Source description
                |--------------------------------------------------------------------------
                |
                | Source Kemendag is currently English.
                | Keep it as English and do not invent Indonesian text.
                |
                */

                $description = trim(
                    (string) $row[$columns['description']]
                );

                $hs->update([
                    'uraian_hs_id' => null,
                    'uraian_hs_en' => $description,

                    'sector_id' =>
                        $sectorIds[$suggestedSector],

                    'product_family' =>
                        trim(
                            (string) $row[
                                $columns[
                                    'suggested_product_family'
                                ]
                            ]
                        ) ?: null,

                    'is_fiber' =>
                        (bool) (
                            (int) $row[
                                $columns['is_fiber']
                            ]
                        ),

                    'is_yarn' =>
                        (bool) (
                            (int) $row[
                                $columns['is_yarn']
                            ]
                        ),

                    'is_fabric' =>
                        (bool) (
                            (int) $row[
                                $columns['is_fabric']
                            ]
                        ),

                    'is_technical_textile' =>
                        (bool) (
                            (int) $row[
                                $columns[
                                    'is_technical_textile'
                                ]
                            ]
                        ),

                    'is_apparel' =>
                        (bool) (
                            (int) $row[
                                $columns['is_apparel']
                            ]
                        ),

                    'is_madeup' =>
                        (bool) (
                            (int) $row[
                                $columns['is_madeup']
                            ]
                        ),

                    'is_active' => true,
                ]);

                $updated++;
            }
        });

        fclose($handle);

        echo PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "HS CLASSIFICATION SEED COMPLETE" . PHP_EOL;
        echo "========================================" . PHP_EOL;
        echo "Updated : {$updated}" . PHP_EOL;
        echo "Skipped : {$skipped}" . PHP_EOL;
        echo "========================================" . PHP_EOL;
    }
}