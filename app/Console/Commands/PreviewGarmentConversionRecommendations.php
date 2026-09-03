<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Trade\GarmentConversionFactorCalculationService;
use App\Services\Trade\GarmentConversionFactorResolverService;
use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class PreviewGarmentConversionRecommendations extends Command
{
    protected $signature = 'digestex:garment-conversion-preview';

    protected $description =
        'Preview KG to PCS conversion recommendations for canonical Garment HS-8.';

    public function handle(
        TextileTaxonomyService $taxonomy,
        GarmentConversionFactorResolverService $resolver,
        GarmentConversionFactorCalculationService $calculator,
    ): int {
        $hsCodes = $taxonomy->hsCodesForSector('garment');

        $rows = [];

        foreach ($hsCodes as $hsCode) {
            $master = DB::table('mst_hscode')
                ->where('hs_code', $hsCode)
                ->where('is_apparel', 1)
                ->where('is_active', 1)
                ->first([
                    'hs_code',
                    'product_family',
                    'uraian_hs_en',
                ]);

            if ($master === null) {
                continue;
            }

            /*
             * 1. Existing ACTIVE factor is authoritative.
             *
             * Never recalculate or override an already active factor.
             */
            $resolved = $resolver->resolve(
                $master->hs_code,
                'KG_PER_PCS'
            );

            $existingFactor = $resolved['factor'] ?? null;

            if ($existingFactor !== null) {
                $rows[] = [
                    'hs_code' => $master->hs_code,
                    'product_family' => $master->product_family,
                    'methodology' => $resolved['methodology'] ?? 'KG_PER_PCS',
                    'existing_factor' => $existingFactor,
                    'recommended_factor' => $existingFactor,
                    'basis' => 'ACTIVE_FACTOR',
                    'status' => 'ACTIVE',
                ];

                continue;
            }

            /*
             * 2. No ACTIVE factor.
             *
             * Use the existing calculation service.
             *
             * Important:
             * calculate() is the existing evidence-based calculation
             * layer. This preview does NOT create or activate a factor.
             */
            $candidate = $calculator->calculate(
                $master->hs_code
            );

            $recommendedFactor = $candidate['factor'] ?? null;

            /*
             * 3. Preserve the calculation service's actual result.
             *
             * Do not invent a factor when the existing evidence engine
             * cannot produce one.
             */
            $rows[] = [
                'hs_code' => $master->hs_code,
                'product_family' => $master->product_family,
                'methodology' => $candidate['methodology'] ?? null,
                'existing_factor' => null,
                'recommended_factor' => $recommendedFactor,
                'basis' => $recommendedFactor !== null
                    ? ($candidate['calculation_method'] ?? 'VALIDATED_EVIDENCE')
                    : 'INSUFFICIENT_BASIS',
                'status' => $recommendedFactor !== null
                    ? 'RECOMMENDED'
                    : 'NO_RECOMMENDATION',
            ];
        }

        $this->info(
            'Canonical Garment HS-8: ' . count($hsCodes)
        );

        $this->info(
            'Preview rows: ' . count($rows)
        );

        $this->newLine();

        $this->table(
            [
                'HS-8',
                'Product Family',
                'Methodology',
                'Existing',
                'Recommended',
                'Basis',
                'Status',
            ],
            array_map(
                static function (array $row): array {
                    return [
                        $row['hs_code'],
                        $row['product_family'],
                        $row['methodology'] ?? '-',

                        $row['existing_factor'] === null
                            ? '-'
                            : number_format(
                                (float) $row['existing_factor'],
                                6,
                                '.',
                                ''
                            ),

                        $row['recommended_factor'] === null
                            ? '-'
                            : number_format(
                                (float) $row['recommended_factor'],
                                6,
                                '.',
                                ''
                            ),

                        $row['basis'],
                        $row['status'],
                    ];
                },
                $rows
            )
        );

        return self::SUCCESS;
    }
}