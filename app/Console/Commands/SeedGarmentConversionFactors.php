<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\GarmentConversionFactor;
use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class SeedGarmentConversionFactors extends Command
{
    protected $signature = 'garment:seed-conversion-factors
                            {--dry-run : Preview mapping without writing to database}
                            {--force : Replace existing ACTIVE factor for the same HS-8}';

    protected $description =
        'Assign initial garment HS-8 conversion factors from transparent category rules.';

    /**
 * Initial category conversion factors.
 *
 * Factor = Kilograms per Piece (KG/PCS).
 *
 * Formula:
 *
 *     Pieces = Kg / Factor
 *
 * Example:
 *
 *     35.26 KG / 0.180 KG/PCS
 *     = 195.89 PCS
 *
 * These are INITIAL INDUSTRY ESTIMATES.
 * They are intentionally designed to be replaceable
 * after real company/user feedback.
 */
private const CATEGORY_FACTORS = [

    't_shirt' => [
        'label' => 'T-shirt',
        'weight_per_piece' => 0.180,
    ],

    'shirt' => [
        'label' => 'Shirt',
        'weight_per_piece' => 0.225,
    ],

    'dress' => [
        'label' => 'Dress',
        'weight_per_piece' => 0.300,
    ],

    'trousers' => [
        'label' => 'Trousers',
        'weight_per_piece' => 0.425,
    ],

    'sweater' => [
        'label' => 'Sweater',
        'weight_per_piece' => 0.500,
    ],

    'jacket' => [
        'label' => 'Jacket',
        'weight_per_piece' => 0.950,
    ],

    'baby_garment' => [
        'label' => 'Baby garment',
        'weight_per_piece' => 0.115,
    ],

    'underwear' => [
        'label' => 'Underwear',
        'weight_per_piece' => 0.075,
    ],

    'suit' => [
        'label' => 'Suit',
        'weight_per_piece' => 0.800,
    ],

    'ensemble' => [
        'label' => 'Ensemble',
        'weight_per_piece' => 0.450,
    ],

    'skirt' => [
        'label' => 'Skirt',
        'weight_per_piece' => 0.300,
    ],

    'bra' => [
        'label' => 'Bra',
        'weight_per_piece' => 0.080,
    ],

    'girdle' => [
        'label' => 'Girdle',
        'weight_per_piece' => 0.180,
    ],

    'corselette' => [
        'label' => 'Corselette',
        'weight_per_piece' => 0.250,
    ],

    'medical_compression' => [
        'label' => 'Medical compression',
        'weight_per_piece' => 0.150,
    ],

    'support_athletic' => [
        'label' => 'Support / athletic band',
        'weight_per_piece' => 0.060,
    ],

    'swimwear' => [
        'label' => 'Swimwear',
        'weight_per_piece' => 0.250,
    ],

    'hosiery' => [
        'label' => 'Hosiery',
        'weight_per_piece' => 0.050,
    ],

    'gloves' => [
        'label' => 'Gloves / Mittens',
        'weight_per_piece' => 0.100,
    ],

    'ties' => [
        'label' => 'Ties',
        'weight_per_piece' => 0.125,
    ],

    'handkerchiefs' => [
        'label' => 'Handkerchiefs',
        'weight_per_piece' => 0.067,
    ],

    'protective_garment' => [
        'label' => 'Protective garment',
        'weight_per_piece' => 0.167,
    ],

    'fencing_wrestling' => [
        'label' => 'Fencing / wrestling',
        'weight_per_piece' => 0.500,
    ],

    'track_suit' => [
        'label' => 'Track suit',
        'weight_per_piece' => 0.500,
    ],

    'sarong' => [
    'label' => 'Sarong',
    'weight_per_piece' => 0.400,
    'factor' => 0.400,
],

'protective_garment' => [
    'label' => 'Protective garment',
    'weight_per_piece' => 0.167,
    'factor' => 0.167,
],

'support_athletic' => [
    'label' => 'Support / athletic band',
    'weight_per_piece' => 0.060,
    'factor' => 0.060,
],

'handkerchiefs' => [
    'label' => 'Handkerchiefs',
    'weight_per_piece' => 0.067,
    'factor' => 0.067,
],

'ski_suit' => [
    'label' => 'Ski suit',
    'weight_per_piece' => 0.667,
],

'wetsuit' => [
    'label' => 'Wetsuit',
    'weight_per_piece' => 0.500,
],

'surgical_gown' => [
    'label' => 'Surgical gown',
    'weight_per_piece' => 0.333,
],

'anti_explosive_suit' => [
    'label' => 'Anti-explosive protective suit',
    'weight_per_piece' => 1.000,
],

'flyers_coveralls' => [
    'label' => "Flyers' coveralls",
    'weight_per_piece' => 0.667,
],

'scarves' => [
    'label' => 'Scarves / Shawls',
    'weight_per_piece' => 0.125,
],

'judo_belt' => [
    'label' => 'Judo belt',
    'weight_per_piece' => 0.333,
],

];

    public function handle(): int
    {
        $startedAt = microtime(true);

        $this->info(
            'Starting initial garment conversion factor mapping...'
        );

        $this->newLine();

        try {

            $rows = TradeUnitClassification::query()
                ->where('sector', 'garment')
                ->where('status', 'active')
                ->orderBy('hs_code')
                ->get([
                    'hs_code',
                    'hs_description',
                    'product_type',
                    'product_group',
                ]);

            if ($rows->isEmpty()) {

                $this->warn(
                    'No active garment HS-8 classifications found.'
                );

                return self::SUCCESS;
            }

            $stats = [
                'total' => $rows->count(),
                'matched' => 0,
                'unmatched' => 0,
                'created' => 0,
                'updated' => 0,
                'existing_skipped' => 0,
            ];

            $categoryCounts = [];

            foreach ($rows as $row) {

                $category = $this->resolveCategory(
                    (string) $row->hs_description,
                    (string) $row->hs_code
                );

                /*
                |--------------------------------------------------------------------------
                | No Category Match
                |--------------------------------------------------------------------------
                */

                if ($category === null) {

                    $stats['unmatched']++;

                    continue;
                }

                $definition =
                    self::CATEGORY_FACTORS[$category];

                $stats['matched']++;

                $categoryCounts[$category] =
                    ($categoryCounts[$category] ?? 0) + 1;

                /*
                |--------------------------------------------------------------------------
                | Existing ACTIVE Factor
                |--------------------------------------------------------------------------
                */

                $existing = GarmentConversionFactor::query()
                    ->where('hs_code', $row->hs_code)
                    ->where('methodology', 'KG_PER_PCS')
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($existing !== null && ! $this->option('force')) {

                    $stats['existing_skipped']++;

                    if ($this->option('dry-run')) {

                        $this->line(
                            sprintf(
                                '[SKIP] %s | %s | existing ACTIVE factor %.2f',
                                $row->hs_code,
                                $definition['label'],
                                (float) $existing->factor
                            )
                        );
                    }

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Mapping Preview
                |--------------------------------------------------------------------------
                */

                if ($this->option('dry-run')) {

                        $this->line(
                            sprintf(
                                '[MAP] %s | %-25s | %.3f KG/PCS | %s',
                                $row->hs_code,
                                $definition['label'],
                                $definition['weight_per_piece'],
                                Str::limit(
                                    trim((string) $row->hs_description),
                                    90
                                )
                            )
                        );

                        continue;
                    }

               

                /*
                |--------------------------------------------------------------------------
                | Persist
                |--------------------------------------------------------------------------
                |
                | Factor is Pcs/Kg.
                |
                | Pieces = Kg × Factor
                |
                */

                $factor = GarmentConversionFactor::query()
                    ->updateOrCreate(
                        [
                            'hs_code' =>
                                $row->hs_code,

                            'methodology' =>
                                'KG_PER_PCS',

                            'status' =>
                                'ACTIVE',
                        ],
                        [
                            'factor' =>
                                $definition['weight_per_piece'],

                            'evidence_type' =>
                                'INITIAL_CATEGORY_ESTIMATE',

                            'weight_unit' =>
                                'KG_PER_PCS',

                            'evidence_count' =>
                                0,

                            'total_sample_size' =>
                                0,

                            'calculation_method' =>
                                'CATEGORY_ESTIMATE_PCS_PER_KG',

                            'observed_minimum' =>
                                $definition['weight_per_piece'],

                            'observed_maximum' =>
                                $definition['weight_per_piece'],

                            'evidence_references' => [
                                'source' =>
                                    'DIGESTEX initial garment conversion methodology',

                                'category' =>
                                    $definition['label'],

                                'weight_per_piece_kg' =>
                                    $definition['weight_per_piece'],

                                'factor_pcs_per_kg' =>
                                    $definition['weight_per_piece'],

                                'basis' =>
                                    'Initial transparent category estimate; subject to refinement from actual company/user feedback.',
                            ],

                            'reviewer' =>
                                'DIGESTEX',

                            'reviewer_role' =>
                                'Initial methodology',

                            'activator' =>
                                'DIGESTEX',

                            'activator_role' =>
                                'Initial conversion activation',

                            'status' =>
                                'ACTIVE',
                        ]
                    );

                if ($factor->wasRecentlyCreated) {

                    $stats['created']++;

                } else {

                    $stats['updated']++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            $this->info(
                $this->option('dry-run')
                    ? 'DRY-RUN completed.'
                    : 'Garment conversion factor seeding completed.'
            );

            $this->table(
                [
                    'Metric',
                    'Count',
                ],
                [
                    [
                        'Garment HS-8',
                        $stats['total'],
                    ],
                    [
                        'Matched to category',
                        $stats['matched'],
                    ],
                    [
                        'No category match',
                        $stats['unmatched'],
                    ],
                    [
                        'Created',
                        $stats['created'],
                    ],
                    [
                        'Updated',
                        $stats['updated'],
                    ],
                    [
                        'Existing skipped',
                        $stats['existing_skipped'],
                    ],
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Category Coverage
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            $this->info(
                'Category mapping:'
            );

            foreach ($categoryCounts as $category => $count) {

                $definition =
                    self::CATEGORY_FACTORS[$category];

                $this->line(
                                        sprintf(
                        '  %-25s : %d HS | %.3f KG/PCS',
                        $definition['label'],
                        $count,
                        $definition['weight_per_piece']
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Elapsed
            |--------------------------------------------------------------------------
            */

            $elapsed =
                round(
                    microtime(true) - $startedAt,
                    2
                );

            $this->newLine();

            $this->line(
                "Elapsed: {$elapsed} seconds"
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Garment conversion factor seeding failed.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }

    /**
     * Resolve an HS-8 description to an initial category.
     *
     * IMPORTANT:
     * This is deliberately transparent and conservative.
     * Unmatched descriptions remain without an HS-specific factor.
     */
    private function resolveCategory(
        string $description,
        ?string $hsCode = null
    ): ?string {

        $text = strtolower(
            trim($description)
        );
/*
|--------------------------------------------------------------------------
| Explicit HS-8 PRODUCT-SPECIFIC Mapping
|--------------------------------------------------------------------------
*/
if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Sarong / Pilgrimage Robes (Ihram)
            '62113340',
            '62113940',
        ],
        true
    )
) {
    return 'sarong';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Protective garments
            '62114350',
        ],
        true
    )
) {
    return 'protective_garment';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Braces / suspenders / garters and similar articles
            '62129019',
            '62129099',
        ],
        true
    )
) {
    return 'support_athletic';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Handkerchiefs
            '62139011',
            '62139019',
            '62139091',
        ],
        true
    )
) {
    return 'handkerchiefs';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Bras
            '62121011',
            '62121019',
            '62121091',
            '62121099',
        ],
        true
    )
) {
    return 'bra';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Girdles / Panty-Girdles
            '62122010',
            '62122090',
        ],
        true
    )
) {
    return 'girdle';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Corselettes
            '62123010',
            '62123090',
        ],
        true
    )
) {
    return 'corselette';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Medical Compression Garments
            '62129011',
            '62129091',
        ],
        true
    )
) {
    return 'medical_compression';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Support / Athletic Bands
            '62129012',
            '62129092',
        ],
        true
    )
) {
    return 'support_athletic';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Protective garment
            '62101010',
            '62101090',
            '62102010',
            '62102090',
            '62103010',
            '62103090',
            '62104010',
            '62104090',
            '62105010',
            '62105090',
            '62113310',
            '62113390',
            '62113910',
            '62113990',
        ],
        true
    )
) {
    return 'protective_garment';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Fencing / wrestling garments
            '62113210',
            '62113920',
            '62114210',
            '62114920',
            '62113220',
            
        ],
        true
    )
) {
    return 'fencing_wrestling';
}

/*
|--------------------------------------------------------------------------
| Explicit HS-8 Batch #6 Mapping
|--------------------------------------------------------------------------
*/

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Suits
            '61041920',
            '61041990',
        ],
        true
    )
) {
    return 'suit';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Track suits
            '61121100',
            '61121200',
            '61121900',
        ],
        true
    )
) {
    return 'track_suit';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Ski suits
            '61122000',
            '62112000',
        ],
        true
    )
) {
    return 'ski_suit';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Wetsuit
            '61130010',
        ],
        true
    )
) {
    return 'wetsuit';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Additional protective garments
            '61130030',
            '61130040',
            '61143020',
            '62101011',
            '62101019',
            '62102020',
            '62102030',
            '62102040',
            '62103020',
            '62103030',
            '62103040',
            '62104020',
            '62105020',
            '62113320',
            '62113330',
            '62113930',
        ],
        true
    )
) {
    return 'protective_garment';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Additional swimwear
            '62111100',
            '62111200',
        ],
        true
    )
) {
    return 'swimwear';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Sarong
            '62114230',
            '62114370',
            '62114950',
        ],
        true
    )
) {
    return 'sarong';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Fencing / wrestling
            '62114340',
            '62114910',
        ],
        true
    )
) {
    return 'fencing_wrestling';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Surgical gown
            '62114310',
        ],
        true
    )
) {
    return 'surgical_gown';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Anti-explosive protective suit
            '62114330',
        ],
        true
    )
) {
    return 'anti_explosive_suit';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Flyers' coveralls
            '62114360',
        ],
        true
    )
) {
    return 'flyers_coveralls';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Scarves / shawls
            '61171010',
            '61171090',
            '62141010',
            '62141090',
            '62142000',
            '62143010',
            '62143090',
            '62144010',
            '62144090',
            '62149010',
            '62149090',
        ],
        true
    )
) {
    return 'scarves';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Support / athletic band
            '61178020',
        ],
        true
    )
) {
    return 'support_athletic';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Judo belt
            '62171010',
        ],
        true
    )
) {
    return 'judo_belt';
}

/*
|--------------------------------------------------------------------------
| Explicit HS-8 Underwear Mapping
|--------------------------------------------------------------------------
|
| These HS-8 codes have been explicitly approved
| for the initial Underwear category factor.
|
*/

            if (
                $hsCode !== null
                && in_array(
                    $hsCode,
                    [
                        '61159400',
                        '61159500',
                        '61159600',
                        '61159900',
                        '62121011',
                        '62121019',
                        '62121091',
                        '62121099',

                        // Additional Slips & Petticoats
                        '61081100',
                        '61081920',
                        '61081930',
                        '61081940',
                        '61081990',
                        '62081100',
                        '62081900',
                    ],
                    true
                )
            ) {
                return 'underwear';
            }
        

        if ($text === '') {
            return null;
        }


if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Swimwear
            '61123100',
            '61123900',
            '61124110',
            '61124190',
            '61124910',
            '61124990',
        ],
        true
    )
) {
    return 'swimwear';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Hosiery
            '61151010',
            '61151090',
            '61152100',
            '61152200',
            '61152910',
            '61152990',
            '61153010',
            '61153090',
        ],
        true
    )
) {
    return 'hosiery';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Gloves / Mittens
            '61161010',
            '61161090',
            '61169100',
            '61169200',
            '61169300',
            '61169900',
            '62160010',
            '62160091',
            '62160092',
            '62160099',
        ],
        true
    )
) {
    return 'gloves';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Ties / Bow ties / Cravats
            '61178011',
            '61178019',
            '62151010',
            '62151090',
            '62152010',
            '62152090',
            '62159010',
            '62159090',
        ],
        true
    )
) {
    return 'ties';
}

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            // Handkerchiefs
            '62132010',
            '62132090',
            '62139010',
            '62139090',
            '62139020',
            '62139099',
        ],
        true
    )
) {
    return 'handkerchiefs';
}

        /*
|--------------------------------------------------------------------------
| Explicit HS-8 Suit Mapping
|--------------------------------------------------------------------------
*/

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            '61031000',
            '61041300',
            '62031100',
            '62031200',
            '62031911',
            '62031919',
            '62031921',
            '62031929',
            '62031990',
            '62041100',
            '62041210',
            '62041290',
            '62041300',
            '62041911',
            '62041919',
            '62041990',
        ],
        true
    )
) {
    return 'suit';
}


/*
|--------------------------------------------------------------------------
| Explicit HS-8 Ensemble Mapping
|--------------------------------------------------------------------------
*/

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            '61032200',
            '61032300',
            '61032900',
            '61042200',
            '61042300',
            '61042900',
            '62032210',
            '62032290',
            '62032300',
            '62032910',
            '62032990',
            '62042100',
            '62042210',
            '62042290',
            '62042300',
            '62042910',
            '62042990',
        ],
        true
    )
) {
    return 'ensemble';
}


/*
|--------------------------------------------------------------------------
| Explicit HS-8 Skirt Mapping
|--------------------------------------------------------------------------
*/

if (
    $hsCode !== null
    && in_array(
        $hsCode,
        [
            '61045100',
            '61045200',
            '61045300',
            '61045900',
            '62045100',
            '62045210',
            '62045290',
            '62045300',
            '62045910',
            '62045990',
        ],
        true
    )
) {
    return 'skirt';
}
        
        /*
        |--------------------------------------------------------------------------
        | Baby garment
        |--------------------------------------------------------------------------
        |
        | Must be checked before generic garment terms.
        */

        if (
            str_contains($text, 'babies')
            || str_contains($text, 'baby garment')
        ) {
            return 'baby_garment';
        }

        /*
        |--------------------------------------------------------------------------
        | Underwear
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'underpants')
            || str_contains($text, 'briefs')
            || str_contains($text, 'panties')
            || str_contains($text, 'underwear')
            || str_contains($text, 'singlets')
        ) {
            return 'underwear';
        }

        /*
        |--------------------------------------------------------------------------
        | T-shirt
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 't-shirt')
            || str_contains($text, 't shirt')
            || str_contains($text, 'tee shirt')
        ) {
            return 't_shirt';
        }

        /*
        |--------------------------------------------------------------------------
        | Sweater
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'sweater')
            || str_contains($text, 'pullover')
            || str_contains($text, 'cardigan')
            || str_contains($text, 'jersey')
        ) {
            return 'sweater';
        }

        /*
        |--------------------------------------------------------------------------
        | Jacket / outerwear
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'jacket')
            || str_contains($text, 'blazer')
            || str_contains($text, 'overcoat')
            || str_contains($text, 'car-coat')
            || str_contains($text, 'anorak')
            || str_contains($text, 'wind-cheater')
            || str_contains($text, 'wind-jacket')
            || str_contains($text, 'windbreaker')
            || str_contains($text, 'cloak')
            || str_contains($text, 'cape')
        ) {
            return 'jacket';
        }

        /*
        |--------------------------------------------------------------------------
        | Dress
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'dress')
            || str_contains($text, 'nightdress')
        ) {
            return 'dress';
        }

        /*
        |--------------------------------------------------------------------------
        | Trousers
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'trousers')
            || str_contains($text, 'shorts')
            || str_contains($text, 'breeches')
            || str_contains($text, 'bib and brace overalls')
        ) {
            return 'trousers';
        }

        /*
        |--------------------------------------------------------------------------
        | Shirt / blouse
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'shirt')
            || str_contains($text, 'blouse')
        ) {
            return 'shirt';
        }

        return null;
    }
}
