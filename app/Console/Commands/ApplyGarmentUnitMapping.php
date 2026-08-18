<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApplyGarmentUnitMapping extends Command
{
    protected $signature = 'digestex:apply-garment-unit-mapping';

    protected $description =
        'Apply validated HS-8 Garment intelligence unit mapping.';

    public function handle(): int
    {
        $rows = TradeUnitClassification::query()
            ->where('sector', 'garment')
            ->where('status', 'active')
            ->select([
                'id',
                'hs_code',
                'hs_description',
            ])
            ->orderBy('hs_code')
            ->get();

        if ($rows->count() !== 352) {
            $this->error(
                'Safety check failed: expected 352 Garment HS-8 records, found '
                . $rows->count()
            );

            return self::FAILURE;
        }

        $mapping = [];

        foreach ($rows as $row) {

            $unit = $this->detectUnit(
                (string) $row->hs_description
            );

            if ($unit === null) {

                $this->error(
                    "UNMAPPED HS-8: {$row->hs_code}"
                );

                return self::FAILURE;
            }

            $mapping[$row->hs_code] = $unit;
        }

        $pcs = collect($mapping)
            ->filter(fn ($unit) => $unit === 'PCS')
            ->count();

        $pair = collect($mapping)
            ->filter(fn ($unit) => $unit === 'PAIR')
            ->count();

        if ($pcs !== 330 || $pair !== 22) {

            $this->error(
                'Safety check failed.'
            );

            $this->error(
                "Expected PCS=330 / PAIR=22, "
                . "got PCS={$pcs} / PAIR={$pair}"
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Commit
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($mapping) {

            foreach ($mapping as $hsCode => $unit) {

                TradeUnitClassification::query()
                    ->where('hs_code', $hsCode)
                    ->where('sector', 'garment')
                    ->update([
                        'intelligence_unit' => $unit,

                        /*
                        |--------------------------------------------------------------------------
                        | Conversion remains OFF
                        |--------------------------------------------------------------------------
                        */

                        'conversion_enabled' => false,

                        /*
                        |--------------------------------------------------------------------------
                        | Factor remains NULL
                        |--------------------------------------------------------------------------
                        */

                        'conversion_factor' => null,

                        /*
                        |--------------------------------------------------------------------------
                        | Metadata
                        |--------------------------------------------------------------------------
                        */

                        'conversion_method' => null,

                        'conversion_source' => null,

                        'conversion_confidence' => null,
                    ]);
            }
        });

        $this->newLine();

        $this->info(
            'Garment HS-8 Unit Mapping applied successfully.'
        );

        $this->table(
            ['Intelligence Unit', 'HS-8'],
            [
                ['PCS', $pcs],
                ['PAIR', $pair],
            ]
        );

        $this->newLine();

        $this->info(
            'Conversion factors remain disabled.'
        );

        $this->info(
            'No conversion factor has been applied.'
        );

        return self::SUCCESS;
    }

    /**
     * Same validated classification logic
     * used by the audit stage.
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
        | PCS
        |--------------------------------------------------------------------------
        */

        $pcsKeywords = [

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

            'swimwear',
            'swimsuit',
            'swimsuits',
            'swim suit',
            'swim suits',
            'wetsuit',
            'wetsuits',

            'protective work garment',
            'protective work garments',
            'protective garment',
            'protective garments',
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
            'suspender',
            'suspenders',
            'garter',
            'garters',

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

            'garment',
            'garments',
            'apparel',
            'clothing',

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

        return null;
    }
}