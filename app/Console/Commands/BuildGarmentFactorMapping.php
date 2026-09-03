<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ReflectionMethod;

class BuildGarmentFactorMapping extends Command
{
    protected $signature =
        'digestex:build-garment-factor-mapping';

    protected $description =
        'Build actual Factor Mapping Master for all canonical Garment HS-8.';

    /**
     * Baseline factors approved for direct-factor sub-groups.
     */
    private const FACTORS = [
        'BATHROBE_PCS'   => 0.700000,
        'TSHIRT_PCS'     => 0.200000,
        'SHIRT_PCS'      => 0.250000,
        'LOWER_BODY_PCS' => 0.450000,
        'DRESS_PCS'      => 0.350000,
        'SKIRT_PCS'      => 0.300000,
        'JACKET_PCS'     => 0.650000,

        /*
         * Existing classifier also has PAIR_DIRECT.
         * It is intentionally NOT assigned a KG/PCS baseline here.
         * Pair-based products require pair-specific methodology.
         */
    ];

    public function handle(): int
    {
        $rows = TradeUnitClassification::query()
            ->where('sector', 'garment')
            ->where('status', 'active')
            ->select([
                'hs_code',
                'hs_description',
                'product_type',
                'product_group',
                'intelligence_unit',
            ])
            ->orderBy('hs_code')
            ->get();

        $this->info(
            'DIGESTEX Garment Factor Mapping Master v1'
        );

        $this->newLine();

        $this->line('Canonical Garment HS-8: ' . $rows->count());

        if ($rows->count() !== 352) {
            $this->error(
                'Safety check failed: expected exactly 352 active Garment HS-8.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Use the EXISTING classifier
        |--------------------------------------------------------------------------
        |
        | We deliberately do not duplicate classifySubGroup().
        | AuditGarmentConversionSubGroups remains the single source of truth.
        |
        */

        $classifier = app(
            \App\Console\Commands\AuditGarmentConversionSubGroups::class
        );

        $method = new ReflectionMethod(
            $classifier,
            'classifySubGroup'
        );

        $method->setAccessible(true);

        $results = [];

        foreach ($rows as $row) {

            $classification = $method->invoke(
                $classifier,
                (string) $row->hs_description,
                strtoupper((string) $row->intelligence_unit)
            );

            $subGroup = $classification['sub_group'];
            $status = $classification['status'];
            $methodology = $classification['method'];

            /*
             * Only FACTOR_READY classifications may receive
             * a subgroup baseline factor.
             */
            $baselineFactor = null;
            $factorSource = null;
            $resolution = 'REVIEW';

            if (
                $status === 'FACTOR_READY'
                && array_key_exists($methodology, self::FACTORS)
            ) {
                $baselineFactor = self::FACTORS[$methodology];
                $factorSource = 'SUBGROUP_BASELINE_V1';
                $resolution = 'BASELINE_FACTOR';
            } elseif ($status === 'NO_DIRECT_FACTOR') {
                $resolution = 'NO_DIRECT_FACTOR';
            } elseif ($status === 'FACTOR_READY') {
                /*
                 * Example:
                 * PAIR_DIRECT
                 *
                 * Classification is ready semantically,
                 * but no KG/PCS baseline exists.
                 */
                $resolution = 'METHODOLOGY_REVIEW';
            }

            $results[] = [
                'hs_code' => (string) $row->hs_code,
                'product_family' => $row->product_group,
                'description' => $row->hs_description,
                'intelligence_unit' => strtoupper(
                    (string) $row->intelligence_unit
                ),
                'conversion_sub_group' => $subGroup,
                'classification_status' => $status,
                'classification_method' => $methodology,
                'baseline_factor_kg_per_pcs' => $baselineFactor,
                'factor_source' => $factorSource,
                'resolution' => $resolution,
                'reason' => $classification['reason'],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Safety check
        |--------------------------------------------------------------------------
        */

        if (count($results) !== 352) {
            $this->error(
                'Mapping safety check failed: expected 352 rows.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Resolution Summary');

        $summary = collect($results)
            ->groupBy('resolution')
            ->map(
                fn ($items, $resolution) => [
                    $resolution,
                    $items->count(),
                ]
            )
            ->sortByDesc(fn ($row) => $row[1])
            ->values()
            ->toArray();

        $this->table(
            [
                'Resolution',
                'HS-8',
            ],
            $summary
        );

        /*
        |--------------------------------------------------------------------------
        | Sub-group summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Factor Mapping Summary');

        $factorSummary = collect($results)
            ->groupBy('conversion_sub_group')
            ->map(
                fn ($items, $subGroup) => [
                    $subGroup,
                    $items->count(),
                    $items->first()['baseline_factor_kg_per_pcs'],
                    $items
                        ->groupBy('resolution')
                        ->map(fn ($x) => $x->count())
                        ->map(
                            fn ($count, $status) =>
                                $status . ': ' . $count
                        )
                        ->implode(' | '),
                ]
            )
            ->sortByDesc(fn ($row) => $row[1])
            ->values()
            ->toArray();

        $this->table(
            [
                'Sub-Group',
                'HS-8',
                'KG/PCS',
                'Resolution',
            ],
            $factorSummary
        );

        /*
        |--------------------------------------------------------------------------
        | Detailed mapping
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Actual 352 HS-8 Factor Mapping');

        $this->table(
            [
                'HS-8',
                'Product Family',
                'Sub-Group',
                'KG/PCS',
                'Resolution',
            ],
            collect($results)
                ->map(
                    fn ($row) => [
                        $row['hs_code'],
                        $row['product_family'],
                        $row['conversion_sub_group'],
                        $row['baseline_factor_kg_per_pcs'] !== null
                            ? number_format(
                                (float) $row['baseline_factor_kg_per_pcs'],
                                6,
                                '.',
                                ''
                            )
                            : '-',
                        $row['resolution'],
                    ]
                )
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | JSON export
        |--------------------------------------------------------------------------
        */

        Storage::disk('local')->put(
            'garment/factor-mapping-v1.json',
            json_encode(
                [
                    'dataset' => 'Garment Factor Mapping Master v1',
                    'sector' => 'garment',
                    'methodology' => 'KG_PER_PCS',
                    'canonical_hs8_count' => 352,
                    'generated_at' => now()->toIso8601String(),
                    'factors' => self::FACTORS,
                    'rows' => $results,
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        $this->newLine();

        $this->info(
            'Factor Mapping Master v1 generated successfully.'
        );

        $this->info(
            'No database records were modified.'
        );

        $this->info(
            'Output: storage/app/garment/factor-mapping-v1.json'
        );

        return self::SUCCESS;
    }
}