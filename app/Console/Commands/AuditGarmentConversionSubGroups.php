<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentConversionSubGroups extends Command
{
    protected $signature =
        'digestex:audit-garment-conversion-subgroups';

    protected $description =
        'Audit Garment HS-8 conversion sub-group classification v2.';

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
            'DIGESTEX Garment HS-8 Conversion Sub-Group Classification Audit v2'
        );

        $this->newLine();

        $this->line('Total HS-8: ' . $rows->count());

        if ($rows->count() !== 352) {
            $this->error(
                'Safety check failed: expected 352 Garment HS-8 records.'
            );

            return self::FAILURE;
        }

        $results = [];

        foreach ($rows as $row) {

            $classification = $this->classifySubGroup(
                (string) $row->hs_description,
                strtoupper((string) $row->intelligence_unit)
            );

            $results[] = [
                'hs_code' => $row->hs_code,
                'unit' => strtoupper((string) $row->intelligence_unit),
                'group' => $row->product_group,
                'sub_group' => $classification['sub_group'],
                'status' => $classification['status'],
                'method' => $classification['method'],
                'reason' => $classification['reason'],
                'description' => $row->hs_description,
            ];
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Status Summary
        |--------------------------------------------------------------------------
        */

        $statusSummary = collect($results)
            ->groupBy('status')
            ->map(
                fn ($items, $status) => [
                    'status' => $status,
                    'hs8' => $items->count(),
                ]
            )
            ->values()
            ->toArray();

        $this->table(
            [
                'Status',
                'HS-8',
            ],
            $statusSummary
        );

        /*
        |--------------------------------------------------------------------------
        | Sub-Group Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Conversion Sub-Group Summary');

        $subGroupSummary = collect($results)
            ->groupBy('sub_group')
            ->map(
                fn ($items, $subGroup) => [
                    'sub_group' => $subGroup,
                    'hs8' => $items->count(),
                    'status' => $items
                        ->groupBy('status')
                        ->map(fn ($x) => $x->count())
                        ->map(
                            fn ($count, $status) =>
                                $status . ': ' . $count
                        )
                        ->implode(' | '),
                ]
            )
            ->sortByDesc('hs8')
            ->values()
            ->toArray();

        $this->table(
            [
                'Sub-Group',
                'HS-8',
                'Status',
            ],
            $subGroupSummary
        );

        /*
        |--------------------------------------------------------------------------
        | Detailed Result
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Detailed Sub-Group Classification');

        $this->table(
            [
                'HS-8',
                'Unit',
                'Group',
                'Sub-Group',
                'Status',
                'Method',
            ],
            collect($results)
                ->map(
                    fn ($row) => [
                        $row['hs_code'],
                        $row['unit'],
                        $row['group'],
                        $row['sub_group'],
                        $row['status'],
                        $row['method'],
                    ]
                )
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Final
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Conversion Sub-Group Classification Audit v2 completed.'
        );

        $this->info(
            'No database records were modified.'
        );

        $this->info(
            'No conversion factors were assigned.'
        );

        return self::SUCCESS;
    }

    protected function classifySubGroup(
        string $description,
        string $unit
    ): array {

        $text = mb_strtolower(trim($description));

        /*
        |--------------------------------------------------------------------------
        | PAIR
        |--------------------------------------------------------------------------
        */

        if ($unit === 'PAIR') {

            if (
                str_contains($text, 'glove')
                || str_contains($text, 'mitten')
                || str_contains($text, 'mitts')
            ) {
                return $this->ready(
                    'Gloves / Mittens',
                    'PAIR_DIRECT',
                    'Pair-based handwear.'
                );
            }

            if (
                str_contains($text, 'sock')
                || str_contains($text, 'hosiery')
                || str_contains($text, 'stocking')
                || str_contains($text, 'tights')
            ) {
                return $this->ready(
                    'Socks / Hosiery',
                    'PAIR_DIRECT',
                    'Pair-based hosiery.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SLIPS / PETTICOATS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'slips')
            || str_contains($text, 'petticoats')
            || str_contains($text, 'petticoat')
        ) {
            return $this->review(
                'Slips / Petticoats',
                'UNDERWEAR_REVIEW',
                'Distinct lightweight foundation garment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SLEEPWEAR
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
            return $this->review(
                'Sleepwear',
                'SLEEPWEAR_REVIEW',
                'Sleepwear requires product-specific weight methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BATHROBES
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'bathrobe')
            || str_contains($text, 'dressing gown')
        ) {

            /*
             * Mixed HS: singlets/vests + bathrobes.
             */
            if (
                str_contains($text, 'singlets')
                || str_contains($text, 'vests')
                || str_contains($text, 'briefs')
                || str_contains($text, 'panties')
                || str_contains($text, 'negligees')
            ) {
                return $this->noDirect(
                    'Mixed Apparel / Bathrobe',
                    'MIXED_PRODUCT',
                    'HS-8 combines materially different garment types.'
                );
            }

            return $this->ready(
                'Bathrobes / Dressing Gowns',
                'BATHROBE_PCS',
                'Distinct outer/household garment form.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | T-SHIRTS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 't-shirt')
            || str_contains($text, 't shirt')
        ) {
            return $this->ready(
                'T-Shirts',
                'TSHIRT_PCS',
                'Recognizable single garment type.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SHIRTS / BLOUSES
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'shirt')
            || str_contains($text, 'blouse')
        ) {
            return $this->ready(
                'Shirts / Blouses',
                'SHIRT_PCS',
                'Recognizable upper-body garment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TROUSERS / SHORTS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'trouser')
            || str_contains($text, 'shorts')
            || str_contains($text, 'breeches')
            || str_contains($text, 'overalls')
            || str_contains($text, 'bib and brace')
            || str_contains($text, 'bib & brace')
        ) {
            return $this->ready(
                'Trousers / Shorts / Overalls',
                'LOWER_BODY_PCS',
                'Recognizable lower-body garment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DRESSES
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'dress')
        ) {
            return $this->ready(
                'Dresses',
                'DRESS_PCS',
                'Recognizable dress construction.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SKIRTS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'skirt')
        ) {
            return $this->ready(
                'Skirts',
                'SKIRT_PCS',
                'Recognizable skirt construction.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | JACKETS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'jacket')
            || str_contains($text, 'blazer')
        ) {
            return $this->ready(
                'Jackets / Blazers',
                'JACKET_PCS',
                'Recognizable upper outerwear.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | COATS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'coat')
            || str_contains($text, 'overcoat')
            || str_contains($text, 'raincoat')
            || str_contains($text, 'anorak')
        ) {
            return $this->review(
                'Coats / Outerwear',
                'OUTERWEAR_REVIEW',
                'Outerwear weight varies materially by construction.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUITS
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'suit')
            || str_contains($text, 'ensemble')
        ) {
            return $this->review(
                'Suits / Ensembles',
                'MULTI_PIECE_REVIEW',
                'Multi-piece garment; direct single-piece factor is unsuitable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PROTECTIVE / WORK
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'protective')
            || str_contains($text, 'protection from fire')
            || str_contains($text, 'chemical')
            || str_contains($text, 'radiation')
            || str_contains($text, 'surgical')
            || str_contains($text, 'anti-explosive')
            || str_contains($text, 'wetsuit')
            || str_contains($text, 'divers')
        ) {
            return $this->review(
                'Protective / Work Apparel',
                'PROTECTIVE_REVIEW',
                'Protection level and garment construction affect weight.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SWIMWEAR
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'swimwear')
            || str_contains($text, 'swimsuit')
            || str_contains($text, 'swimming costume')
        ) {
            return $this->review(
                'Swimwear',
                'SWIMWEAR_REVIEW',
                'Swimwear construction varies by garment type.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SPORTSWEAR
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'track suit')
            || str_contains($text, 'tracksuit')
            || str_contains($text, 'ski suit')
            || str_contains($text, 'sportswear')
            || str_contains($text, 'athletic supporter')
        ) {
            return $this->review(
                'Sportswear',
                'SPORTSWEAR_REVIEW',
                'Sports products have different construction and weight profiles.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ACCESSORIES
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'judo belt')
            || str_contains($text, 'tie')
            || str_contains($text, 'cravat')
            || str_contains($text, 'scarf')
            || str_contains($text, 'shawl')
            || str_contains($text, 'muffler')
            || str_contains($text, 'handkerchief')
            || str_contains($text, 'brace')
            || str_contains($text, 'suspender')
            || str_contains($text, 'garter')
            || str_contains($text, 'clothing accessory')
            || str_contains($text, 'clothing accessories')
        ) {
            return $this->review(
                'Clothing Accessories',
                'ACCESSORY_REVIEW',
                'Accessory weight varies substantially by product.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESIDUAL
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'other garments')
            || str_contains($text, 'other made up')
            || str_contains($text, 'other clothing')
        ) {
            return $this->noDirect(
                'Residual / Other Garments',
                'RESIDUAL',
                'HS-8 is too broad for a direct KG-to-PCS factor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        return $this->review(
            'Other Apparel',
            'MANUAL_REVIEW',
            'No sufficiently precise sub-group identified.'
        );
    }

    protected function ready(
        string $subGroup,
        string $method,
        string $reason
    ): array {
        return [
            'sub_group' => $subGroup,
            'status' => 'FACTOR_READY',
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
            'sub_group' => $subGroup,
            'status' => 'FACTOR_REVIEW',
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
            'sub_group' => $subGroup,
            'status' => 'NO_DIRECT_FACTOR',
            'method' => $method,
            'reason' => $reason,
        ];
    }
}