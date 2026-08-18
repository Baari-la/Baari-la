<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentConversionSubGroupIntegrity extends Command
{
    protected $signature =
        'digestex:audit-garment-conversion-sub-group-integrity';

    protected $description =
        'Audit semantic integrity of Garment HS-8 conversion sub-groups v2.5.';

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
            'DIGESTEX Garment HS-8 Conversion Sub-Group'
        );

        $this->info(
            'Semantic Integrity Audit v2.5'
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
        | CLASSIFICATION
        |--------------------------------------------------------------------------
        */

        $results = [];

        foreach ($rows as $row) {
            $results[] = [
                'hs_code' => $row->hs_code,
                'unit' => strtoupper((string) $row->intelligence_unit),
                'current_group' => $row->product_group,
                'description' => $row->hs_description,
                ...$this->classifySubGroup(
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
        | INTEGRITY SUMMARY
        |--------------------------------------------------------------------------
        */

        $statusOrder = [
            'PASS' => 1,
            'REVIEW' => 2,
            'NO_DIRECT_FACTOR' => 3,
            'UNRESOLVED' => 4,
            'EXCEPTION' => 5,
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
                'Integrity Status',
                'HS-8',
            ],
            $summary
        );

        /*
        |--------------------------------------------------------------------------
        | SUB-GROUP SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Conversion Sub-Group Summary'
        );

        $subGroupSummary = $collection
            ->groupBy('sub_group')
            ->map(
                fn ($items, $subGroup) => [
                    'sub_group' => $subGroup,
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
                'Conversion Sub-Group',
                'HS-8',
                'Status',
            ],
            $subGroupSummary
        );

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
                'REVIEW — Semantic Group Resolved, Methodology Required'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Current Group',
                    'Sub-Group',
                    'Method',
                    'Reason',
                ],
                $review
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['current_group'],
                            $row['sub_group'],
                            $row['method'],
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
                'NO DIRECT FACTOR — Semantic Group Resolved'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Current Group',
                    'Sub-Group',
                    'Method',
                    'Reason',
                ],
                $noDirect
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['current_group'],
                            $row['sub_group'],
                            $row['method'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UNRESOLVED
        |--------------------------------------------------------------------------
        */

        $unresolved = $collection
            ->where('status', 'UNRESOLVED')
            ->values();

        if ($unresolved->isNotEmpty()) {
            $this->newLine();

            $this->error(
                'UNRESOLVED — No Conversion Sub-Group Identified'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Current Group',
                    'Description',
                ],
                $unresolved
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['current_group'],
                            $row['description'],
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
                'EXCEPTION — Semantic Classification Conflict'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Current Group',
                    'Sub-Group',
                    'Reason',
                ],
                $exceptions
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['current_group'],
                            $row['sub_group'],
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
                'Conversion Sub-Group Semantic Integrity Audit v2.5 FAILED.'
            );

            $this->error(
                'Semantic classification conflicts require review.'
            );

            $this->info(
                'No database records were modified.'
            );

            $this->info(
                'No conversion factors were assigned.'
            );

            return self::FAILURE;
        }

        if ($unresolved->isNotEmpty()) {
            $this->error(
                'Conversion Sub-Group Semantic Integrity Audit v2.5 FAILED.'
            );

            $this->error(
                'Unresolved HS-8 records remain.'
            );

            $this->info(
                'No database records were modified.'
            );

            $this->info(
                'No conversion factors were assigned.'
            );

            return self::FAILURE;
        }

        if ($review->isNotEmpty()) {
            $this->info(
                'Conversion Sub-Group Semantic Integrity Audit v2.5 PASSED WITH REVIEW.'
            );

            $this->info(
                'All HS-8 records have resolved semantic conversion sub-groups.'
            );

            $this->warn(
                'REVIEW status means methodology/factor validation is still required.'
            );

            $this->info(
                'No database records were modified.'
            );

            $this->info(
                'No conversion factors were assigned.'
            );

            return self::SUCCESS;
        }

        $this->info(
            'Conversion Sub-Group Semantic Integrity Audit v2.5 PASSED.'
        );

        $this->info(
            'All 352 HS-8 records have resolved semantic conversion sub-groups.'
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
    | SEMANTIC CLASSIFICATION ENGINE v2.5
    |--------------------------------------------------------------------------
    */

    protected function classifySubGroup(
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
        | 1. MIXED BABY GARMENTS / ACCESSORIES
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '61112000',
                    '61113000',
                    '61119010',
                    '61119090',
                ],
                true
            )
        ) {
            return $this->noDirect(
                'Mixed Baby Garments / Accessories',
                'MIXED_PRODUCT',
                'HS-8 explicitly combines babies’ garments and clothing accessories.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. BABY CLOTHING ACCESSORIES
        |--------------------------------------------------------------------------
        */

        if ($hsCode === '62093040') {
            return $this->review(
                'Clothing Accessories',
                'ACCESSORY_REVIEW',
                'Explicit babies’ clothing accessories description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. BRACES / SUSPENDERS / GARTERS
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62129019',
                    '62129099',
                ],
                true
            )
        ) {
            return $this->review(
                'Braces / Suspenders / Garters',
                'SUPPORT_GARMENT_ACCESSORY_REVIEW',
                'Explicit braces, suspenders, garters and similar articles.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. GLOVES / MITTENS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
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
                'Gloves / Mittens',
                'GLOVE_REVIEW',
                'Explicit gloves, mittens or mitts description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. SOCKS / HOSIERY
        |--------------------------------------------------------------------------
        */

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
                ]
            )
            ||
            $this->containsAnyPhrase(
                $text,
                [
                    'panty hose',
                    'pantyhose',
                ]
            )
        ) {
            return $this->review(
                'Socks / Hosiery',
                'HOSIERY_REVIEW',
                'Explicit hosiery, stockings, panty hose, tights or socks description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. SUPPORT / ATHLETIC BANDS
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
                'Support / Athletic Bands',
                'SUPPORT_BAND_REVIEW',
                'Explicit wrist, knee, ankle or athletic support description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. FLYERS' COVERALLS
        |--------------------------------------------------------------------------
        */

        if ($hsCode === '62114360') {
            return $this->review(
                'Protective / Work Apparel',
                'PROTECTIVE_REVIEW',
                'Explicit flyers’ coveralls description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. FENCING / WRESTLING
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
                ],
                true
            )
        ) {
            return $this->review(
                'Fencing / Wrestling Apparel',
                'SPECIALTY_APPAREL_REVIEW',
                'Explicit fencing/wrestling garment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. PILGRIMAGE ROBES / IHRAM
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62113220',
                    '62113340',
                    '62113940',
                ],
                true
            )
        ) {
            return $this->review(
                'Pilgrimage Robes / Ihram',
                'SPECIALTY_APPAREL_REVIEW',
                'Explicit pilgrimage robe / Ihram.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 10. PRAYER CLOAKS
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62114220',
                    '62114320',
                    '62114931',
                    '62114939',
                ],
                true
            )
        ) {
            return $this->review(
                'Prayer Cloaks',
                'SPECIALTY_APPAREL_REVIEW',
                'Explicit prayer cloak.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. SARONG
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62114230',
                    '62114370',
                    '62114950',
                ],
                true
            )
        ) {
            return $this->review(
                'Sarong',
                'SPECIALTY_APPAREL_REVIEW',
                'Explicit tubular-type sarong.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 12. SPECIALTY RESIDUAL
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
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
                'Other Specialty Woven Apparel',
                'SPECIALTY_RESIDUAL',
                'HS-8 describes garments broadly without a sufficiently precise product form.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. BRAS / MASTECTOMY BRAS
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
                ],
                true
            )
        ) {
            return $this->review(
                'Bras / Mastectomy Bras',
                'FOUNDATION_GARMENT_REVIEW',
                'Explicit brassiere or mastectomy bra.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 14. GIRDLES / PANTY-GIRDLES
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62122010',
                    '62122090',
                ],
                true
            )
        ) {
            return $this->review(
                'Girdles / Panty-Girdles',
                'FOUNDATION_GARMENT_REVIEW',
                'Explicit girdle/panty-girdle.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 15. CORSELETTES
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '62123010',
                    '62123090',
                ],
                true
            )
        ) {
            return $this->review(
                'Corselettes',
                'FOUNDATION_GARMENT_REVIEW',
                'Explicit corselette.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 16. MEDICAL COMPRESSION
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
                'Medical Compression Garments',
                'MEDICAL_COMPRESSION_REVIEW',
                'Compression garment intended for medical treatment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 17. SLIPS / PETTICOATS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'slip',
                    'slips',
                    'petticoat',
                    'petticoats',
                ]
            )
        ) {
            return $this->review(
                'Slips / Petticoats',
                'UNDERWEAR_REVIEW',
                'Explicit slips/petticoats description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 18. MIXED APPAREL / BATHROBE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | These HS explicitly combine bathrobes/dressing gowns
        | with other materially different garments.
        |
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
                ],
                true
            )
        ) {
            return $this->noDirect(
                'Mixed Apparel / Bathrobe',
                'MIXED_PRODUCT',
                'HS-8 combines bathrobes/dressing gowns with other materially different apparel.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 19. EXPLICIT BATHROBES / DRESSING GOWNS
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $hsCode,
                [
                    '61079100',
                    '61079900',
                ],
                true
            )
        ) {
            return $this->review(
                'Bathrobes / Dressing Gowns',
                'BATHROBE_REVIEW',
                'Explicit bathrobe/dressing gown description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 20. BRIEFS / PANTIES
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'brief',
                    'briefs',
                    'panty',
                    'panties',
                ]
            )
        ) {
            if (
                $this->containsAnyPhrase(
                    $text,
                    [
                        'bathrobe',
                        'dressing gown',
                        'negligee',
                    ]
                )
            ) {
                return $this->noDirect(
                    'Mixed Apparel / Underwear',
                    'MIXED_PRODUCT',
                    'HS-8 combines underwear with materially different garments.'
                );
            }

            return $this->review(
                'Briefs / Panties',
                'UNDERWEAR_REVIEW',
                'Explicit briefs/panties description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 21. SLEEPWEAR
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
                    'nightshirt',
                    'nightshirts',
                    'nightwear',
                    'sleepwear',
                ]
            )
        ) {
            if (
                $this->containsAnyPhrase(
                    $text,
                    [
                        'bathrobe',
                        'dressing gown',
                        'negligee',
                    ]
                )
            ) {
                return $this->noDirect(
                    'Mixed Sleepwear / Bathrobe',
                    'MIXED_PRODUCT',
                    'HS-8 combines sleepwear and bathrobe-type products.'
                );
            }

            return $this->review(
                'Sleepwear',
                'SLEEPWEAR_REVIEW',
                'Explicit sleepwear description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 22. SWEATERS / PULLOVERS
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with($hsCode, '6110')
            ||
            $this->containsAnyWord(
                $text,
                [
                    'pullover',
                    'pullovers',
                    'sweater',
                    'sweaters',
                    'cardigan',
                    'cardigans',
                    'jersey',
                    'jerseys',
                    'jumper',
                    'jumpers',
                ]
            )
        ) {
            return $this->review(
                'Sweaters / Pullovers',
                'SWEATER_REVIEW',
                'Explicit sweater/pullover construction or HS 6110.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 23. T-SHIRTS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    't-shirt',
                    't shirt',
                    't-shirts',
                    't shirts',
                ]
            )
        ) {
            return $this->resolved(
                'T-Shirts',
                'TSHIRT_PCS',
                'Recognizable single garment type.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 24. SHIRTS / BLOUSES
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'shirt',
                    'shirts',
                    'blouse',
                    'blouses',
                ]
            )
        ) {
            return $this->resolved(
                'Shirts / Blouses',
                'SHIRT_PCS',
                'Recognizable upper-body garment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 25. TROUSERS / SHORTS / OVERALLS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'trouser',
                    'trousers',
                    'shorts',
                    'breeches',
                    'overall',
                    'overalls',
                    'coverall',
                    'coveralls',
                ]
            )
        ) {
            return $this->resolved(
                'Trousers / Shorts / Overalls',
                'LOWER_BODY_PCS',
                'Recognizable lower-body garment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 26. DRESSES
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'dress',
                    'dresses',
                ]
            )
        ) {
            return $this->resolved(
                'Dresses',
                'DRESS_PCS',
                'Recognizable dress construction.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 27. SKIRTS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'skirt',
                    'skirts',
                ]
            )
        ) {
            return $this->resolved(
                'Skirts',
                'SKIRT_PCS',
                'Recognizable skirt construction.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 28. JACKETS / BLAZERS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'jacket',
                    'jackets',
                    'blazer',
                    'blazers',
                ]
            )
        ) {
            return $this->resolved(
                'Jackets / Blazers',
                'JACKET_PCS',
                'Recognizable upper outerwear.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 29. COATS / OUTERWEAR
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Word-boundary matching prevents:
        |
        | coated -> coat
        |
        */

        if (
            $this->containsAnyWord(
                $text,
                [
                    'coat',
                    'coats',
                    'overcoat',
                    'overcoats',
                    'raincoat',
                    'raincoats',
                    'anorak',
                    'anoraks',
                ]
            )
        ) {
            return $this->review(
                'Coats / Outerwear',
                'OUTERWEAR_REVIEW',
                'Explicit coat/outerwear description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 30. SUITS / ENSEMBLES
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
                'Suits / Ensembles',
                'MULTI_PIECE_REVIEW',
                'Multi-piece garment requires dedicated methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 31. PROTECTIVE / WORK APPAREL
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
                'Protective / Work Apparel',
                'PROTECTIVE_REVIEW',
                'Protective/work apparel has materially different weight profiles.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 32. SWIMWEAR
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    'swimwear',
                    'swimsuit',
                    'swimming costume',
                ]
            )
            &&
            !$this->containsAnyPhrase(
                $text,
                [
                    'other than swimwear',
                ]
            )
        ) {
            return $this->review(
                'Swimwear',
                'SWIMWEAR_REVIEW',
                'Explicit swimwear description.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 33. SPORTSWEAR
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    'track suit',
                    'tracksuit',
                    'ski suit',
                    'sportswear',
                    'athletic supporter',
                ]
            )
        ) {
            return $this->review(
                'Sportswear',
                'SPORTSWEAR_REVIEW',
                'Sportswear requires product-specific methodology.'
            );
        }
/*
|--------------------------------------------------------------------------
| 34. JUDO BELTS
|--------------------------------------------------------------------------
*/

if ($hsCode === '62171010') {
    return $this->review(
        'Clothing Accessories',
        'JUDO_BELT_REVIEW',
        'Explicit judo belt description.'
    );
}

        /*
        |--------------------------------------------------------------------------
        | 34. CLOTHING ACCESSORIES
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    'judo belt',
                    'tie',
                    'ties',
                    'cravat',
                    'cravats',
                    'scarf',
                    'scarves',
                    'shawl',
                    'shawls',
                    'muffler',
                    'mufflers',
                    'handkerchief',
                    'handkerchiefs',
                    'clothing accessory',
                    'clothing accessories',
                ]
            )
        ) {
            return $this->review(
                'Clothing Accessories',
                'ACCESSORY_REVIEW',
                'Generic clothing accessory requires product-specific methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 35. RESIDUAL / OTHER GARMENTS
        |--------------------------------------------------------------------------
        */

        if (
            $this->containsAnyPhrase(
                $text,
                [
                    'other garments',
                    'other made up clothing accessories',
                    'other made up',
                ]
            )
        ) {
            return $this->noDirect(
                'Residual / Other Garments',
                'RESIDUAL',
                'HS-8 is too broad for a direct conversion factor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL UNRESOLVED
        |--------------------------------------------------------------------------
        */

        return [
            'status' => 'UNRESOLVED',
            'sub_group' => 'UNRESOLVED',
            'method' => 'MANUAL_REVIEW',
            'reason' => 'No sufficiently specific semantic rule matched.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SAFE WORD MATCHING
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

    /*
    |--------------------------------------------------------------------------
    | RESULT HELPERS
    |--------------------------------------------------------------------------
    */

    protected function resolved(
        string $subGroup,
        string $method,
        string $reason
    ): array {
        return [
            'status' => 'PASS',
            'sub_group' => $subGroup,
            'method' => $method,
            'reason' => $reason,
        ];
    }

    protected function review(
        string $subGroup,
        string $method,
        string $reason
    ): array {
        return [
            'status' => 'REVIEW',
            'sub_group' => $subGroup,
            'method' => $method,
            'reason' => $reason,
        ];
    }

    protected function noDirect(
        string $subGroup,
        string $method,
        string $reason
    ): array {
        return [
            'status' => 'NO_DIRECT_FACTOR',
            'sub_group' => $subGroup,
            'method' => $method,
            'reason' => $reason,
        ];
    }
}