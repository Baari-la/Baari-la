<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentUnitMapping extends Command
{
    protected $signature = 'digestex:audit-garment-unit-mapping';

    protected $description =
        'Audit HS-8 Garment intelligence unit mapping before conversion factors are enabled.';

    public function handle(): int
    {
        $rows = TradeUnitClassification::query()
            ->where('sector', 'garment')
            ->select([
                'hs_code',
                'hs_description',
                'product_type',
                'product_group',
            ])
            ->orderBy('hs_code')
            ->get();

        $this->info(
            'DIGESTEX Garment HS-8 Unit Mapping Audit'
        );

        $this->newLine();

        $this->line(
            'Total HS-8: ' . $rows->count()
        );

        $counts = [
            'PCS' => 0,
            'PAIR' => 0,
            'OTHER' => 0,
            'UNMAPPED' => 0,
        ];

        $unmapped = [];

        foreach ($rows as $row) {

            $unit = $this->detectUnit(
                (string) $row->hs_description
            );

            if ($unit === null) {

                $counts['UNMAPPED']++;

                $unmapped[] = [
                    'hs_code' => $row->hs_code,
                    'description' => $row->hs_description,
                    'product_type' => $row->product_type,
                ];

                continue;
            }

            if (isset($counts[$unit])) {
                $counts[$unit]++;
            } else {
                $counts['OTHER']++;
            }
        }

        $this->newLine();

        $this->table(
            ['Unit', 'HS-8'],
            [
                ['PCS', $counts['PCS']],
                ['PAIR', $counts['PAIR']],
                ['OTHER', $counts['OTHER']],
                ['UNMAPPED', $counts['UNMAPPED']],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Unmapped Detail
        |--------------------------------------------------------------------------
        */

        if (! empty($unmapped)) {

            $this->newLine();

            $this->warn(
                'UNMAPPED HS-8 records:'
            );

            $this->table(
                [
                    'HS-8',
                    'Product Type',
                    'Description',
                ],
                array_map(
                    fn ($row) => [
                        $row['hs_code'],
                        $row['product_type'],
                        $row['description'],
                    ],
                    $unmapped
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Final Decision
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        if ($counts['UNMAPPED'] > 0) {

            $this->warn(
                'Mapping is NOT ready for activation.'
            );

            $this->warn(
                'Review UNMAPPED HS-8 records first.'
            );

            return self::FAILURE;
        }

        $this->info(
            'All Garment HS-8 records have a unit classification.'
        );

        $this->info(
            'Mapping is ready for the next validation stage.'
        );

        return self::SUCCESS;
    }

    /**
     * Detect intelligence unit from HS-8 product description.
     *
     * This is an AUDIT classifier only.
     *
     * It does not write to the database.
     */
    protected function detectUnit(
    string $description
): ?string {

    $text = mb_strtolower(
        trim($description)
    );

    /*
    |--------------------------------------------------------------------------
    | PAIR
    |--------------------------------------------------------------------------
    |
    | Items conventionally counted as pairs.
    |
    */

    $pairKeywords = [
        'gloves',
        'mittens',
        'mitts',
        'socks',
        'hosiery',
        'stockings',
        'panty hose',
        'pantyhose',
        'tights',
    ];

    foreach ($pairKeywords as $keyword) {

        if (str_contains($text, $keyword)) {
            return 'PAIR';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PCS — Apparel
    |--------------------------------------------------------------------------
    */

    $pcsKeywords = [

        // Suits / Ensembles
        'suit',
        'suits',
        'ensemble',
        'ensembles',
        'track suit',
        'track suits',
        'tracksuit',
        'tracksuits',
        'ski suit',
        'ski suits',

        // Main apparel
        't-shirt',
        't shirt',
        'shirt',
        'shirts',
        'blouse',
        'blouses',
        'trouser',
        'trousers',
        'shorts',
        'dress',
        'dresses',
        'skirt',
        'skirts',
        'jacket',
        'jackets',
        'coat',
        'coats',
        'overcoat',
        'overcoats',
        'vest',
        'vests',
        'waistcoat',
        'waistcoats',

        // Underwear / sleepwear
        'brief',
        'briefs',
        'panties',
        'underwear',
        'underpants',
        'singlet',
        'singlets',
        'bathrobe',
        'bathrobes',
        'dressing gown',
        'dressing gowns',
        'negligee',
        'negligees',
        'pyjama',
        'pyjamas',
        'pajama',
        'pajamas',
        'nightdress',
        'nightdresses',
        'nightshirt',
        'nightshirts',

        // Swim / sport
        'swimwear',
        'swimsuit',
        'swimsuits',
        'swim suit',
        'swim suits',
        'wetsuit',
        'wetsuits',

        // Protective / industrial apparel
        'protective work garment',
        'protective work garments',
        'protective garment',
        'protective garments',
        'fire',
        'chemical substances',
        'radiation',
        'surgical gown',
        'surgical gowns',
        'coverall',
        'coveralls',
        'fencing',
        'wrestling',
        'pilgrimage robe',
        'pilgrimage robes',
        'prayer cloak',
        'prayer cloaks',
        'sarong',
        'sarongs',
        'anti-explosive protective suit',
        'flyers coveralls',

        // Special apparel
        'compression garment',
        'compression garments',
        'athletic supporter',
        'athletic supporters',
        'bra',
        'brassiere',
        'brassieres',
        'mastectomy bra',
        'girdle',
        'girdles',
        'panty-girdle',
        'panty-girdles',
        'corselette',
        'corselettes',
        'brace',
        'braces',
        'suspender',
        'suspenders',
        'garter',
        'garters',

        // Accessories / individual articles
        'tie',
        'ties',
        'bow tie',
        'bow ties',
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
        'belt',
        'belts',
        'judo belt',
        'judo belts',
        'wrist band',
        'wrist bands',
        'knee band',
        'knee bands',
        'ankle band',
        'ankle bands',

        // General garment classification
        'garment',
        'garments',
        'apparel',
        'clothing',

        // Other individual made-up clothing articles
        'clothing accessory',
        'clothing accessories',
        'made up clothing accessory',
        'made up clothing accessories',
    ];

    foreach ($pcsKeywords as $keyword) {

        if (str_contains($text, $keyword)) {
            return 'PCS';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Conservative fallback
    |--------------------------------------------------------------------------
    |
    | Do NOT guess.
    |
    */

    return null;
}
}