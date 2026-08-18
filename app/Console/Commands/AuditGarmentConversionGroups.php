<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentConversionGroups extends Command
{
    protected $signature = 'digestex:audit-garment-conversion-groups';

    protected $description =
        'Audit HS-8 Garment conversion groups before assigning conversion factors.';

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
            'DIGESTEX HS-8 Garment Conversion Group Audit'
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

        /*
        |--------------------------------------------------------------------------
        | Group records
        |--------------------------------------------------------------------------
        */

        $groups = [];

        foreach ($rows as $row) {

            $group = $this->detectGroup(
                (string) $row->hs_description,
                (string) $row->intelligence_unit
            );

            $groups[$group][] = $row;
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = collect($groups)
            ->map(
                fn ($items, $group) => [
                    'group' => $group,
                    'total' => count($items),
                ]
            )
            ->sortByDesc('total')
            ->values();

        $this->newLine();

        $this->table(
            [
                'Conversion Group',
                'HS-8',
            ],
            $summary->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Detailed HS-8
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'HS-8 Detail by Conversion Group'
        );

        foreach ($groups as $group => $items) {

            $this->newLine();

            $this->line(
                "[$group] — " . count($items) . ' HS-8'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Description',
                ],
                collect($items)
                    ->map(
                        fn ($row) => [
                            $row->hs_code,
                            $row->intelligence_unit,
                            $row->hs_description,
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | No Database Mutation
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Audit completed.'
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
     * Determine the conversion group.
     *
     * This is an audit grouping only.
     * It does NOT determine the conversion factor.
     */
    protected function detectGroup(
        string $description,
        string $unit
    ): string {

        $text = mb_strtolower(
            trim($description)
        );

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
                return 'Gloves / Mittens';
            }

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
        | PCS — T-Shirt
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 't-shirt')
            || str_contains($text, 't shirt')
        ) {
            return 'T-Shirts';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Shirts / Blouses
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
        | PCS — Trousers / Shorts
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'trouser')
            || str_contains($text, 'shorts')
        ) {
            return 'Trousers / Shorts';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Dresses / Skirts
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'dress')
            || str_contains($text, 'skirt')
        ) {
            return 'Dresses / Skirts';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Jackets / Coats
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'jacket')
            || str_contains($text, 'coat')
            || str_contains($text, 'overcoat')
        ) {
            return 'Jackets / Coats';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Suits / Ensembles
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'suit')
            || str_contains($text, 'ensemble')
        ) {
            return 'Suits / Ensembles';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Underwear
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'brief')
            || str_contains($text, 'panties')
            || str_contains($text, 'underwear')
            || str_contains($text, 'underpants')
            || str_contains($text, 'brassiere')
            || str_contains($text, 'bra')
            || str_contains($text, 'girdle')
            || str_contains($text, 'corselette')
            || str_contains($text, 'corset')
        ) {
            return 'Underwear / Foundation Garments';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Sleepwear
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'pyjama')
            || str_contains($text, 'pajama')
            || str_contains($text, 'nightdress')
            || str_contains($text, 'nightshirt')
            || str_contains($text, 'bathrobe')
            || str_contains($text, 'dressing gown')
            || str_contains($text, 'negligee')
        ) {
            return 'Sleepwear';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Swimwear / Sports
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'swimwear')
            || str_contains($text, 'swimsuit')
            || str_contains($text, 'wetsuit')
            || str_contains($text, 'track suit')
            || str_contains($text, 'tracksuit')
            || str_contains($text, 'ski suit')
        ) {
            return 'Swimwear / Sportswear';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Protective / Work Apparel
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'protective')
            || str_contains($text, 'surgical')
            || str_contains($text, 'coverall')
            || str_contains($text, 'fencing')
            || str_contains($text, 'wrestling')
            || str_contains($text, 'diver')
            || str_contains($text, 'chemical')
            || str_contains($text, 'radiation')
            || str_contains($text, 'anti-explosive')
            || str_contains($text, 'fire')
        ) {
            return 'Protective / Work Apparel';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — Accessories
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($text, 'tie')
            || str_contains($text, 'cravat')
            || str_contains($text, 'scarf')
            || str_contains($text, 'shawl')
            || str_contains($text, 'muffler')
            || str_contains($text, 'handkerchief')
            || str_contains($text, 'belt')
            || str_contains($text, 'judo')
            || str_contains($text, 'wrist band')
            || str_contains($text, 'knee band')
            || str_contains($text, 'ankle band')
        ) {
            return 'Clothing Accessories';
        }

        /*
        |--------------------------------------------------------------------------
        | PCS — General Apparel
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
        | Safety
        |--------------------------------------------------------------------------
        */

        return 'REVIEW REQUIRED';
    }
}