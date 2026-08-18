<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentProductClassification extends Command
{
    protected $signature = 'digestex:audit-garment-product-classification';

    protected $description =
        'Audit HS-8 Garment product classification v2 before conversion factors are assigned.';

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
            'DIGESTEX HS-8 Garment Product Classification Audit v2'
        );

        $this->newLine();

        $this->line(
            'Total HS-8: ' . $rows->count()
        );

        if ($rows->count() !== 352) {
            $this->error(
                'Safety check failed: expected 352 Garment HS-8 records.'
            );

            return self::FAILURE;
        }

        $classified = [];
        $review = [];

        foreach ($rows as $row) {

            $classification = $this->classify(
                (string) $row->hs_description,
                (string) $row->intelligence_unit
            );

            if ($classification === 'REVIEW REQUIRED') {

                $review[] = [
                    'hs_code' => $row->hs_code,
                    'unit' => $row->intelligence_unit,
                    'description' => $row->hs_description,
                ];

                continue;
            }

            $classified[] = [
                'hs_code' => $row->hs_code,
                'unit' => $row->intelligence_unit,
                'classification' => $classification,
                'description' => $row->hs_description,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = collect($classified)
            ->groupBy('classification')
            ->map(
                fn ($items, $classification) => [
                    'classification' => $classification,
                    'total' => $items->count(),
                ]
            )
            ->sortByDesc('total')
            ->values();

        $this->newLine();

        $this->table(
            [
                'Product Classification',
                'HS-8',
            ],
            $summary->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Unit Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Intelligence Unit Summary'
        );

        $unitSummary = collect($classified)
            ->groupBy('unit')
            ->map(
                fn ($items, $unit) => [
                    'unit' => $unit,
                    'total' => $items->count(),
                ]
            )
            ->values();

        $this->table(
            [
                'Unit',
                'HS-8',
            ],
            $unitSummary->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Review Required
        |--------------------------------------------------------------------------
        */

        if (! empty($review)) {

            $this->newLine();

            $this->warn(
                'REVIEW REQUIRED: ' . count($review) . ' HS-8'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Description',
                ],
                $review
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Detailed Classification
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'HS-8 Product Classification Detail'
        );

        foreach (
            collect($classified)
                ->groupBy('classification')
            as $classification => $items
        ) {

            $this->newLine();

            $this->line(
                '[' . $classification . '] — '
                . $items->count()
                . ' HS-8'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Description',
                ],
                $items
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['description'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Final Audit Status
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        if (! empty($review)) {

            $this->warn(
                'Product Classification Audit v2 requires review.'
            );

            $this->warn(
                'No database records were modified.'
            );

            return self::FAILURE;
        }

        $this->info(
            'All 352 HS-8 Garment records are classified.'
        );

        $this->info(
            'Product Classification Audit v2 PASSED.'
        );

        $this->info(
            'No database records were modified.'
        );

        $this->info(
            'No conversion factors were assigned.'
        );

        return self::SUCCESS;
    }

    /**
     * --------------------------------------------------------------------------
     * HS-8 Product Classification
     * --------------------------------------------------------------------------
     */
    protected function classify(
    string $description,
    string $unit
): string {

    $text = mb_strtolower(trim($description));

    /*
    |--------------------------------------------------------------------------
    | 1. PAIR — Gloves / Mittens
    |--------------------------------------------------------------------------
    */

    if ($unit === 'PAIR') {

        if (
            str_contains($text, 'glove')
            || str_contains($text, 'mitten')
            || str_contains($text, 'mitts')
        ) {
            return 'Gloves / Mittens';
        }

        /*
        |--------------------------------------------------------------------------
        | PAIR — Hosiery / Socks
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'sock')
            || str_contains($text, 'hosiery')
            || str_contains($text, 'stocking')
            || str_contains($text, 'panty hose')
            || str_contains($text, 'pantyhose')
            || str_contains($text, 'tights')
        ) {
            return 'Hosiery / Socks';
        }

        return 'Other PAIR Apparel';
    }

    /*
    |--------------------------------------------------------------------------
    | 2. PROTECTIVE / WORK APPAREL
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | Must run BEFORE generic garment/suit rules.
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'protective')
        || str_contains($text, 'protection from fire')
        || str_contains($text, 'protection from chemical')
        || str_contains($text, 'protection from radiation')
        || str_contains($text, 'chemical substances')
        || str_contains($text, 'radiation')
        || str_contains($text, 'surgical gown')
        || str_contains($text, 'anti-explosive')
        || str_contains($text, 'flyers\' coveralls')
        || str_contains($text, 'divers’ suit')
        || str_contains($text, 'divers\' suit')
        || str_contains($text, 'wetsuit')
        || str_contains($text, 'fencing')
        || str_contains($text, 'wrestling')
    ) {
        return 'Protective / Work Apparel';
    }

    /*
    |--------------------------------------------------------------------------
    | 3. RELIGIOUS / SPECIALTY APPAREL
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'ihram')
        || str_contains($text, 'ehram')
        || str_contains($text, 'prayer cloak')
        || str_contains($text, 'pilgrimage robe')
        || str_contains($text, 'sarong of tubular type')
    ) {
        return 'Religious / Specialty Apparel';
    }

    /*
    |--------------------------------------------------------------------------
    | 4. SWIMWEAR
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | "other than swimwear" MUST NOT be classified as swimwear.
    |--------------------------------------------------------------------------
    */

    $isExplicitNonSwimwear =
        str_contains($text, 'other than swimwear');

    if (
        !$isExplicitNonSwimwear
        && (
            str_contains($text, 'swimwear')
            || str_contains($text, 'swimsuit')
            || str_contains($text, 'swimming costume')
        )
    ) {
        return 'Swimwear';
    }

    /*
    |--------------------------------------------------------------------------
    | 5. SPORTSWEAR
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'track suit')
        || str_contains($text, 'tracksuit')
        || str_contains($text, 'ski suit')
        || str_contains($text, 'sportswear')
        || str_contains($text, 'athletic supporter')
    ) {
        return 'Sportswear';
    }

    /*
    |--------------------------------------------------------------------------
    | 6. BATHROBES / DRESSING GOWNS
    |--------------------------------------------------------------------------
    | Must run BEFORE generic "dress".
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'bathrobe')
        || str_contains($text, 'bath robes')
        || str_contains($text, 'dressing gown')
        || str_contains($text, 'dressing-gown')
    ) {
        return 'Bathrobes / Dressing Gowns';
    }

    /*
    |--------------------------------------------------------------------------
    | 7. SLEEPWEAR
    |--------------------------------------------------------------------------
    | Must run BEFORE generic "shirt".
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'pyjama')
        || str_contains($text, 'pajama')
        || str_contains($text, 'nightdress')
        || str_contains($text, 'nightshirt')
        || str_contains($text, 'nightwear')
        || str_contains($text, 'sleepwear')
        || str_contains($text, 'negligee')
    ) {
        return 'Sleepwear';
    }

    /*
    |--------------------------------------------------------------------------
    | 8. UNDERWEAR / FOUNDATION
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'underpants')
        || str_contains($text, 'underwear')
        || str_contains($text, 'underpants')
        || str_contains($text, 'briefs')
        || str_contains($text, 'brief ')
        || str_contains($text, 'panties')
        || str_contains($text, 'brassiere')
        || str_contains($text, 'mastectomy bra')
        || str_contains($text, 'girdle')
        || str_contains($text, 'panty-girdle')
        || str_contains($text, 'corselette')
        || str_contains($text, 'corset')
        || str_contains($text, 'petticoat')
        || str_contains($text, 'slips')
    ) {
        return 'Underwear / Foundation Garments';
    }

    /*
    |--------------------------------------------------------------------------
    | 9. T-SHIRTS
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 't-shirts')
        || str_contains($text, 't-shirt')
        || str_contains($text, 't shirts')
        || str_contains($text, 't shirt')
    ) {
        return 'T-Shirts';
    }

    /*
    |--------------------------------------------------------------------------
    | 10. SHIRTS / BLOUSES
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'shirt')
        || str_contains($text, 'blouse')
    ) {
        return 'Shirts / Blouses';
    }

    /*
    |--------------------------------------------------------------------------
    | 11. SWEATERS / PULLOVERS
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'pullover')
        || str_contains($text, 'sweater')
        || str_contains($text, 'cardigan')
        || str_contains($text, 'jersey')
        || str_contains($text, 'jumper')
        || str_contains($text, 'waistcoat')
    ) {
        return 'Sweaters / Pullovers';
    }

    /*
    |--------------------------------------------------------------------------
    | 12. TROUSERS / SHORTS / OVERALLS
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'trouser')
        || str_contains($text, 'shorts')
        || str_contains($text, 'overalls')
        || str_contains($text, 'overall')
        || str_contains($text, 'bib and brace')
        || str_contains($text, 'bib & brace')
        || str_contains($text, 'breeches')
    ) {
        return 'Trousers / Shorts / Overalls';
    }

    /*
    |--------------------------------------------------------------------------
    | 13. DRESSES
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'dresses')
        || str_contains($text, 'dress,')
        || str_contains($text, 'dress ')
    ) {
        return 'Dresses';
    }

    /*
    |--------------------------------------------------------------------------
    | 14. SKIRTS
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'skirt')
    ) {
        return 'Skirts';
    }

    /*
    |--------------------------------------------------------------------------
    | 15. JACKETS / COATS
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'jacket')
        || str_contains($text, 'coat')
        || str_contains($text, 'overcoat')
        || str_contains($text, 'raincoat')
        || str_contains($text, 'anorak')
        || str_contains($text, 'wind-cheater')
        || str_contains($text, 'wind-jacket')
        || str_contains($text, 'poncho')
        || str_contains($text, 'cape')
        || str_contains($text, 'cloak')
    ) {
        return 'Jackets / Coats';
    }

    /*
    |--------------------------------------------------------------------------
    | 16. SUITS / ENSEMBLES
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'suits')
        || str_contains($text, 'suit,')
        || str_contains($text, 'ensemble')
    ) {
        return 'Suits / Ensembles';
    }

    /*
    |--------------------------------------------------------------------------
    | 17. CLOTHING ACCESSORIES
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'tie')
        || str_contains($text, 'cravat')
        || str_contains($text, 'scarf')
        || str_contains($text, 'shawl')
        || str_contains($text, 'muffler')
        || str_contains($text, 'handkerchief')
        || str_contains($text, 'judo belt')
        || str_contains($text, 'brace')
        || str_contains($text, 'suspender')
        || str_contains($text, 'garter')
        || str_contains($text, 'wrist band')
        || str_contains($text, 'knee band')
        || str_contains($text, 'ankle band')
        || str_contains($text, 'clothing accessory')
        || str_contains($text, 'clothing accessories')
        || str_contains($text, 'made up clothing accessory')
    ) {
        return 'Clothing Accessories';
    }

    /*
    |--------------------------------------------------------------------------
    | 18. OTHER APPAREL
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'garment')
        || str_contains($text, 'apparel')
        || str_contains($text, 'clothing')
    ) {
        return 'Other Apparel';
    }

    /*
    |--------------------------------------------------------------------------
    | 19. REVIEW REQUIRED
    |--------------------------------------------------------------------------
    */

    return 'REVIEW REQUIRED';
}
}