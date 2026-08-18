<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentConversionMethodology extends Command
{
    protected $signature =
        'digestex:audit-garment-conversion-methodology';

    protected $description =
        'Audit conversion methodology readiness for Garment HS-8 v1.';

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
            'DIGESTEX Garment HS-8 Conversion Methodology Audit v1'
        );

        $this->newLine();

        $total = $rows->count();

        $this->line("Total HS-8: {$total}");

        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        if ($total !== 352) {
            $this->error(
                'Safety check failed: expected exactly 352 Garment HS-8 records.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | AUDIT
        |--------------------------------------------------------------------------
        */

        $results = [];

        foreach ($rows as $row) {
            $results[] = [
                'hs_code' => $row->hs_code,
                'unit' => strtoupper((string) $row->intelligence_unit),
                'current_group' => $row->product_group,
                'description' => $row->hs_description,
                ...$this->classifyMethodology(
                    (string) $row->hs_code,
                    (string) $row->hs_description,
                    strtoupper((string) $row->intelligence_unit),
                    (string) $row->product_group
                ),
            ];
        }

        $collection = collect($results);

        /*
        |--------------------------------------------------------------------------
        | STATUS SUMMARY
        |--------------------------------------------------------------------------
        */

        $statusOrder = [
            'READY' => 1,
            'REVIEW' => 2,
            'NO_DIRECT_FACTOR' => 3,
            'EXCEPTION' => 4,
        ];

        $summary = $collection
            ->groupBy('status')
            ->map(
                fn ($items, $status) => [
                    'status' => $status,
                    'hs8' => $items->count(),
                ]
            )
            ->sortBy(
                fn ($row) =>
                    $statusOrder[$row['status']] ?? 99
            )
            ->values()
            ->toArray();

        $this->newLine();

        $this->table(
            [
                'Methodology Status',
                'HS-8',
            ],
            $summary
        );

        /*
        |--------------------------------------------------------------------------
        | METHODOLOGY SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Conversion Methodology Summary'
        );

        $methodSummary = $collection
            ->groupBy('methodology')
            ->map(
                fn ($items, $methodology) => [
                    'methodology' => $methodology,
                    'hs8' => $items->count(),
                    'status' => $items
                        ->groupBy('status')
                        ->map(
                            fn ($x) => $x->count()
                        )
                        ->map(
                            fn ($count, $status) =>
                                "{$status}: {$count}"
                        )
                        ->implode(' | '),
                ]
            )
            ->sortByDesc('hs8')
            ->values()
            ->toArray();

        $this->table(
            [
                'Conversion Methodology',
                'HS-8',
                'Status',
            ],
            $methodSummary
        );

        /*
        |--------------------------------------------------------------------------
        | READY
        |--------------------------------------------------------------------------
        */

        $ready = $collection
            ->where('status', 'READY')
            ->values();

        if ($ready->isNotEmpty()) {
            $this->newLine();

            $this->info(
                'READY — Methodology Suitable for Factor Validation'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Reason',
                ],
                $ready
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REVIEW
        |--------------------------------------------------------------------------
        */

        $review = $collection
            ->where('status', 'REVIEW')
            ->values();

        if ($review->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'REVIEW — Methodology Requires Validation'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Reason',
                ],
                $review
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NO DIRECT FACTOR
        |--------------------------------------------------------------------------
        */

        $noDirect = $collection
            ->where('status', 'NO_DIRECT_FACTOR')
            ->values();

        if ($noDirect->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'NO DIRECT FACTOR — Conversion Must Not Be Applied Automatically'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Reason',
                ],
                $noDirect
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | EXCEPTIONS
        |--------------------------------------------------------------------------
        */

        $exceptions = $collection
            ->where('status', 'EXCEPTION')
            ->values();

        if ($exceptions->isNotEmpty()) {
            $this->newLine();

            $this->error(
                'EXCEPTION — Methodology Conflict'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Reason',
                ],
                $exceptions
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL RESULT
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        if ($exceptions->isNotEmpty()) {
            $this->error(
                'Conversion Methodology Audit v1 FAILED.'
            );

            $this->error(
                'Methodology conflicts require resolution.'
            );

            $this->info(
                'No database records were modified.'
            );

            $this->info(
                'No conversion factors were assigned.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Conversion Methodology Audit v1 PASSED WITH REVIEW.'
        );

        $this->info(
            'Methodology classification completed for all Garment HS-8.'
        );

        $this->warn(
            'READY does NOT mean a conversion factor is approved.'
        );

        $this->warn(
            'Conversion factors require a separate factor validation stage.'
        );

        $this->info(
            'No database records were modified.'
        );

        $this->info(
            'No conversion factors were assigned.'
        );

        return self::SUCCESS;
    }

    /*
    |--------------------------------------------------------------------------
    | METHODOLOGY CLASSIFICATION
    |--------------------------------------------------------------------------
    */

    protected function classifyMethodology(
        string $hsCode,
        string $description,
        string $unit,
        string $currentGroup
    ): array {
        $text = mb_strtolower(
            trim($description)
        );

        /*
        |--------------------------------------------------------------------------
        | MIXED PRODUCT
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '61089100',
                    '61089200',
                    '61089900',
                    '62079100',
                    '62079910',
                    '62079990',
                    '61112000',
                    '61113000',
                    '61119010',
                    '61119090',
                ],
                true
            )
        ) {
            return $this->noDirect(
                'MIXED_PRODUCT',
                $this->subGroupFromKnownClassification($hsCode),
                'HS-8 combines materially different product forms.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESIDUAL
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '61142000',
                    '61149010',
                    '61149090',
                    '62113290',
                    '62114290',
                    '62114390',
                    '62114960',
                    '62114990',
                ],
                true
            )
        ) {
            return $this->noDirect(
                'RESIDUAL',
                $this->subGroupFromKnownClassification($hsCode),
                'Residual HS-8 is too broad for a defensible direct factor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAIR-BASED PRODUCTS
        |--------------------------------------------------------------------------
        */

        if ($unit === 'PAIR') {

            if (
                $this->containsAnyWord(
                    $text,
                    [
                        'sock',
                        'socks',
                        'hosiery',
                        'stocking',
                        'stockings',
                        'tights',
                        'glove',
                        'gloves',
                        'mitten',
                        'mittens',
                        'mitt',
                        'mitts',
                    ]
                )
            ) {
                return $this->review(
                    'PAIR_TO_KG',
                    $this->subGroupFromKnownClassification($hsCode),
                    'Pair-based product requires validated average weight per pair.'
                );
            }

            return $this->review(
                'PAIR_TO_KG',
                $this->subGroupFromKnownClassification($hsCode),
                'PAIR unit requires product-specific weight validation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUITS / ENSEMBLES
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'suit',
                    'suits',
                    'ensemble',
                    'ensembles',
                ]
            )
        ) {
            return $this->review(
                'MULTI_PIECE',
                'Suits / Ensembles',
                'Multi-piece garments cannot use a generic single-piece factor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PROTECTIVE / WORK APPAREL
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    'protective',
                    'protection from fire',
                    'workwear',
                    'work wear',
                    'coverall',
                    'coveralls',
                    'chemical',
                    'radiation',
                    'surgical',
                    'anti-explosive',
                    'wetsuit',
                    'diver',
                    'divers',
                ]
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                'Protective / Work Apparel',
                'Protective/work apparel has highly variable construction and weight.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FOUNDATION GARMENTS
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62121011',
                    '62121019',
                    '62121091',
                    '62121099',
                    '62122010',
                    '62122090',
                    '62123010',
                    '62123090',
                ],
                true
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                $this->subGroupFromKnownClassification($hsCode),
                'Foundation garments require product-specific weight validation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MEDICAL COMPRESSION
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62129011',
                    '62129091',
                ],
                true
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                'Medical Compression Garments',
                'Medical compression products require product-specific validation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUPPORT / ATHLETIC BANDS
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '61178020',
                    '62129012',
                    '62129092',
                ],
                true
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                'Support / Athletic Bands',
                'Support products have materially different dimensions and weights.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SPECIALTY APPAREL
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62113210',
                    '62113310',
                    '62113910',
                    '62114210',
                    '62114340',
                    '62114910',
                    '62113220',
                    '62113340',
                    '62113940',
                    '62114220',
                    '62114320',
                    '62114230',
                    '62114370',
                    '62114931',
                    '62114939',
                    '62114950',
                ],
                true
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                $this->subGroupFromKnownClassification($hsCode),
                'Specialty apparel requires product-specific methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SLEEPWEAR / BATHROBES
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    'pyjama',
                    'pyjamas',
                    'pajama',
                    'pajamas',
                    'nightdress',
                    'nightdresses',
                    'nightwear',
                    'sleepwear',
                    'bathrobe',
                    'dressing gown',
                    'negligee',
                ]
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                $this->subGroupFromKnownClassification($hsCode),
                'Sleepwear/bathrobe products require validated product-weight methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CLOTHING ACCESSORIES
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '61171010',
                    '61171090',
                    '61178011',
                    '61178019',
                    '61178090',
                    '61179000',
                    '62093040',
                    '62099000',
                    '62132010',
                    '62132090',
                    '62139011',
                    '62139019',
                    '62139091',
                    '62139099',
                    '62141010',
                    '62141090',
                    '62142000',
                    '62143010',
                    '62143090',
                    '62144010',
                    '62144090',
                    '62149010',
                    '62149090',
                    '62151010',
                    '62151090',
                    '62152010',
                    '62152090',
                    '62159010',
                    '62159090',
                    '62171010',
                    '62171090',
                    '62179000',
                ],
                true
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                'Clothing Accessories',
                'Accessory category contains materially different product forms.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT PCS APPAREL
        |--------------------------------------------------------------------------
        */

        if ($unit === 'PCS') {
            return $this->review(
                'PCS_TO_KG',
                $this->subGroupFromKnownClassification($hsCode),
                'PCS garment requires validated average weight per piece.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return $this->review(
            'MANUAL_REVIEW',
            $this->subGroupFromKnownClassification($hsCode),
            'Conversion methodology could not be safely determined automatically.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUB-GROUP LOOKUP
    |--------------------------------------------------------------------------
    |
    | v1 intentionally derives only from known semantic HS mappings.
    | It does NOT write to the database.
    |
    */

    protected function subGroupFromKnownClassification(
        string $hsCode
    ): string {
        $map = [

            '61089100' => 'Mixed Apparel / Bathrobe',
            '61089200' => 'Mixed Apparel / Bathrobe',
            '61089900' => 'Mixed Apparel / Bathrobe',

            '62079100' => 'Mixed Apparel / Bathrobe',
            '62079910' => 'Mixed Apparel / Bathrobe',
            '62079990' => 'Mixed Apparel / Bathrobe',

            '61112000' => 'Mixed Baby Garments / Accessories',
            '61113000' => 'Mixed Baby Garments / Accessories',
            '61119010' => 'Mixed Baby Garments / Accessories',
            '61119090' => 'Mixed Baby Garments / Accessories',

            '61142000' => 'Residual / Other Garments',
            '61149010' => 'Residual / Other Garments',
            '61149090' => 'Residual / Other Garments',

            '62113290' => 'Other Specialty Woven Apparel',
            '62114290' => 'Other Specialty Woven Apparel',
            '62114390' => 'Other Specialty Woven Apparel',
            '62114960' => 'Other Specialty Woven Apparel',
            '62114990' => 'Other Specialty Woven Apparel',

            '62121011' => 'Bras / Mastectomy Bras',
            '62121019' => 'Bras / Mastectomy Bras',
            '62121091' => 'Bras / Mastectomy Bras',
            '62121099' => 'Bras / Mastectomy Bras',

            '62122010' => 'Girdles / Panty-Girdles',
            '62122090' => 'Girdles / Panty-Girdles',

            '62123010' => 'Corselettes',
            '62123090' => 'Corselettes',

            '62129011' => 'Medical Compression Garments',
            '62129091' => 'Medical Compression Garments',

            '61178020' => 'Support / Athletic Bands',
            '62129012' => 'Support / Athletic Bands',
            '62129092' => 'Support / Athletic Bands',

            '62113210' => 'Fencing / Wrestling Apparel',
            '62113310' => 'Fencing / Wrestling Apparel',
            '62113910' => 'Fencing / Wrestling Apparel',
            '62114210' => 'Fencing / Wrestling Apparel',
            '62114340' => 'Fencing / Wrestling Apparel',
            '62114910' => 'Fencing / Wrestling Apparel',

            '62113220' => 'Pilgrimage Robes / Ihram',
            '62113340' => 'Pilgrimage Robes / Ihram',
            '62113940' => 'Pilgrimage Robes / Ihram',

            '62114220' => 'Prayer Cloaks',
            '62114320' => 'Prayer Cloaks',
            '62114931' => 'Prayer Cloaks',
            '62114939' => 'Prayer Cloaks',

            '62114230' => 'Sarong',
            '62114370' => 'Sarong',
            '62114950' => 'Sarong',
        ];

        return $map[$hsCode] ?? 'SEMANTIC_GROUP_FROM_V2.6';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    protected function containsWord(
        string $text,
        string $word
    ): bool {
        return preg_match(
            '/(?<![a-z])'
            . preg_quote($word, '/')
            . '(?![a-z])/i',
            $text
        ) === 1;
    }

    protected function containsAnyWord(
        string $text,
        array $words
    ): bool {
        foreach ($words as $word) {
            if ($this->containsWord($text, $word)) {
                return true;
            }
        }

        return false;
    }

    protected function containsAnyPhrase(
        string $text,
        array $phrases
    ): bool {
        foreach ($phrases as $phrase) {
            if (
                preg_match(
                    '/(?<![a-z])'
                    . preg_quote($phrase, '/')
                    . '(?![a-z])/i',
                    $text
                )
            ) {
                return true;
            }
        }

        return false;
    }

    protected function review(
        string $methodology,
        string $subGroup,
        string $reason
    ): array {
        return [
            'status' => 'REVIEW',
            'methodology' => $methodology,
            'sub_group' => $subGroup,
            'reason' => $reason,
        ];
    }

    protected function noDirect(
        string $methodology,
        string $subGroup,
        string $reason
    ): array {
        return [
            'status' => 'NO_DIRECT_FACTOR',
            'methodology' => $methodology,
            'sub_group' => $subGroup,
            'reason' => $reason,
        ];
    }
}