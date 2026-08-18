<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Models\HsCode;

echo "========================================\n";
echo "DIGESTEX HS CLASSIFICATION REVIEW V4\n";
echo "========================================\n\n";

$outputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_classification_review_v4.csv';

$outputDir = dirname($outputFile);

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function normalizeText(?string $value): string
{
    $value = trim((string) $value);

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtolower($value);
}

function containsAny(
    string $text,
    array $keywords
): bool {
    foreach ($keywords as $keyword) {
        if (str_contains($text, mb_strtolower($keyword))) {
            return true;
        }
    }

    return false;
}

function result(
    ?string $sector,
    ?string $family,
    bool $fiber = false,
    bool $yarn = false,
    bool $fabric = false,
    bool $technical = false,
    bool $apparel = false,
    bool $madeup = false,
    string $confidence = 'LOW',
    string $reason = ''
): array {
    return [
        'suggested_sector' => $sector,
        'suggested_product_family' => $family,
        'is_fiber' => $fiber,
        'is_yarn' => $yarn,
        'is_fabric' => $fabric,
        'is_technical_textile' => $technical,
        'is_apparel' => $apparel,
        'is_madeup' => $madeup,
        'confidence' => $confidence,
        'reason' => $reason,
    ];
}

/*
|--------------------------------------------------------------------------
| RULES V4
|--------------------------------------------------------------------------
*/

function classifyHs(
    string $hsCode,
    string $description
): array {
    $text = normalizeText($description);

    $chapter = substr($hsCode, 0, 2);
    $heading = substr($hsCode, 0, 4);

    /*
    |--------------------------------------------------------------------------
    | 1. APPAREL
    |--------------------------------------------------------------------------
    */

    if (
        in_array($chapter, ['61', '62'], true)
        ||
        containsAny($text, [
            't-shirt',
            't shirt',
            'shirt',
            'shirts',
            'blouse',
            'blouses',
            'trousers',
            'trouser',
            'pants',
            'shorts',
            'jacket',
            'jackets',
            'coat',
            'coats',
            'overcoat',
            'dress',
            'dresses',
            'skirt',
            'skirts',
            'underwear',
            'underpants',
            'bra',
            'sportswear',
            'swimwear',
            'garment',
            'garments',
            'apparel',
            'clothing',
        ])
    ) {
        $family = 'Other Apparel';

        if (
            containsAny($text, [
                't-shirt',
                't shirt',
            ])
        ) {
            $family = 'T-Shirts';
        } elseif (
            containsAny($text, [
                'shirt',
                'blouse',
            ])
        ) {
            $family = 'Shirts / Blouses';
        } elseif (
            containsAny($text, [
                'trousers',
                'trouser',
                'pants',
                'shorts',
            ])
        ) {
            $family = 'Trousers / Pants';
        } elseif (
            containsAny($text, [
                'jacket',
                'coat',
                'overcoat',
            ])
        ) {
            $family = 'Jackets / Outerwear';
        } elseif (
            containsAny($text, [
                'underwear',
                'underpants',
                'bra',
            ])
        ) {
            $family = 'Underwear';
        } elseif (
            containsAny($text, [
                'sportswear',
                'swimwear',
            ])
        ) {
            $family = 'Sportswear';
        }

        return result(
            'apparel',
            $family,
            false,
            false,
            false,
            false,
            true,
            false,
            'HIGH',
            'Apparel chapter / garment form'
        );
    }

/*
|--------------------------------------------------------------------------
| 5105 - Carded / Combed Wool
|--------------------------------------------------------------------------
*/

if ($heading === '5105') {

    $family = 'Wool / Animal Hair';

    if (str_contains($text, 'carded wool')) {
        $family = 'Carded Wool';
    } elseif (str_contains($text, 'combed wool in fragments')) {
        $family = 'Combed Wool';
    } elseif (str_contains($text, 'wool tops')) {
        $family = 'Wool Tops';
    } elseif (str_contains($text, 'combed wool')) {
        $family = 'Combed Wool';
    }

    return result(
        'fiber',
        $family,
        true,
        false,
        false,
        false,
        false,
        false,
        'HIGH',
        'HS heading 5105 - carded/combed wool'
    );
}
    
/*
|--------------------------------------------------------------------------
| 560130 - Textile Flock / Dust / Mill Neps
|--------------------------------------------------------------------------
*/

if ($heading === '5601' && str_starts_with($hsCode, '560130')) {

    $family = 'Textile Flock / Mill Neps';

    if ($hsCode === '56013010') {
        $family = 'Polyamide Fibre Flock';
    } elseif ($hsCode === '56013020') {
        $family = 'Polypropylene Fibre Flock';
    } elseif ($hsCode === '56013090') {
        $family = 'Other Textile Flock / Mill Neps';
    }

    return result(
        'fiber',
        $family,
        true,
        false,
        false,
        false,
        false,
        false,
        'HIGH',
        'HS heading 560130 - textile flock / mill neps'
    );
}
    /*
    |--------------------------------------------------------------------------
    | 2. HEADING-SPECIFIC RULES
    |--------------------------------------------------------------------------
    */

    /*
    | 5202 - Cotton waste
    */

    if ($heading === '5202') {
        return result(
            'fiber',
            'Cotton Waste / Recovered Fiber',
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'HS heading 5202'
        );
    }

    /*
    | 5208 - Cotton woven fabric
    */

    if ($heading === '5208') {
        return result(
            'fabric',
            'Woven / Cotton',
            false,
            false,
            true,
            false,
            false,
            false,
            'HIGH',
            'HS heading 5208 - cotton woven fabric'
        );
    }

    /*
    | 5301 - Flax
    */

    if ($heading === '5301') {
        return result(
            'fiber',
            'Flax / Linen',
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'HS heading 5301'
        );
    }

    /*
    | 5302 - Hemp
    */

    if ($heading === '5302') {
        return result(
            'fiber',
            'Hemp',
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'HS heading 5302'
        );
    }
/*
|--------------------------------------------------------------------------
| 5701–5705 - Carpets / Rugs / Floor Coverings
|--------------------------------------------------------------------------
*/

if (
    in_array(
        $heading,
        ['5701', '5702', '5703', '5704', '5705'],
        true
    )
) {

    if (
        containsAny($text, [
            'motor vehicle',
            'motor vehicles',
            'automotive',
            'vehicle',
        ])
    ) {
        return result(
            'technical-textile',
            'Automotive Floor Covering',
            false,
            false,
            true,
            true,
            false,
            true,
            'HIGH',
            'HS heading 5701-5705 with automotive application'
        );
    }

    $family = 'Floor Coverings / Carpets';

    if (
        containsAny($text, [
            'prayer rug',
            'prayer rugs',
        ])
    ) {
        $family = 'Prayer Rugs';
    } elseif (
        containsAny($text, [
            'kelem',
            'schumacks',
            'karamanie',
            'hand-woven rugs',
            'hand woven rugs',
        ])
    ) {
        $family = 'Hand-woven Rugs';
    } elseif (
        containsAny($text, [
            'carpet',
            'carpets',
        ])
    ) {
        $family = 'Carpets';
    } elseif (
        containsAny($text, [
            'rug',
            'rugs',
        ])
    ) {
        $family = 'Rugs';
    } elseif (
        containsAny($text, [
            'felt',
        ])
    ) {
        $family = 'Felt Floor Coverings';
    }

    return result(
        'made-up-textile',
        $family,
        false,
        false,
        true,
        false,
        false,
        true,
        'HIGH',
        'HS heading 5701-5705 - floor covering'
    );
}

    /*
    | 5703 - Tufted floor coverings
    */

    if ($heading === '5703') {
        if (
            containsAny($text, [
                'motor vehicles',
                'motor vehicle',
                'automotive',
                'vehicle',
            ])
        ) {
            return result(
                'technical-textile',
                'Automotive Floor Covering',
                false,
                false,
                true,
                true,
                false,
                true,
                'HIGH',
                'HS heading 5703 + automotive application'
            );
        }

        return result(
            'made-up-textile',
            'Floor Coverings / Carpets',
            false,
            false,
            true,
            false,
            false,
            true,
            'HIGH',
            'HS heading 5703 - tufted floor covering'
        );
    }

    /*
    | 5704 - Felt floor coverings
    */

    if ($heading === '5704') {
        return result(
            'made-up-textile',
            'Felt Floor Coverings',
            false,
            false,
            true,
            false,
            false,
            true,
            'HIGH',
            'HS heading 5704'
        );
    }

    /*
    | 5805 - Tapestries
    */

    if ($heading === '5805') {
        return result(
            'made-up-textile',
            'Tapestries',
            false,
            false,
            true,
            false,
            false,
            true,
            'HIGH',
            'HS heading 5805'
        );
    }

    /*
    | 5806 - Narrow fabrics / ribbons
    */

    if ($heading === '5806') {
        if (containsAny($text, [
            'ribbon',
            'ribbons',
        ])) {
            return result(
                'fabric',
                'Ribbons / Narrow Textile',
                false,
                false,
                true,
                false,
                false,
                false,
                'HIGH',
                'HS heading 5806 - ribbon / narrow fabric'
            );
        }

        return result(
            'fabric',
            'Narrow Woven Textile',
            false,
            false,
            true,
            false,
            false,
            false,
            'HIGH',
            'HS heading 5806'
        );
    }

    /*
    | 5810 - Embroidery
    */

    if ($heading === '5810') {
        return result(
            'made-up-textile',
            'Embroidery',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 5810'
        );
    }

    /*
    | 5901 - Prepared textile materials
    */

    if ($heading === '5901') {
        return result(
            'technical-textile',
            'Prepared / Coated Textile Materials',
            false,
            false,
            true,
            true,
            false,
            false,
            'HIGH',
            'HS heading 5901'
        );
    }

    /*
    | 5904 - Linoleum / floor covering
    */

    if ($heading === '5904') {
        return result(
            'technical-textile',
            'Floor Covering / Linoleum',
            false,
            false,
            true,
            true,
            false,
            true,
            'HIGH',
            'HS heading 5904'
        );
    }

    /*
    | 5906 - Rubberised / adhesive textile
    */

    if ($heading === '5906') {
        return result(
            'technical-textile',
            'Industrial / Coated Textile',
            false,
            false,
            true,
            true,
            false,
            false,
            'HIGH',
            'HS heading 5906'
        );
    }

    /*
    | 5907 - Painted / otherwise impregnated textile
    */

    if ($heading === '5907') {
        return result(
            'technical-textile',
            'Coated / Painted Textile',
            false,
            false,
            true,
            true,
            false,
            false,
            'HIGH',
            'HS heading 5907'
        );
    }

    /*
    | 5908 - Wicks / industrial textile articles
    */

    if ($heading === '5908') {
        return result(
            'technical-textile',
            'Industrial Textile Articles',
            false,
            false,
            false,
            true,
            false,
            true,
            'HIGH',
            'HS heading 5908'
        );
    }

    /*
    | 5909 - Hosepiping
    */

    if ($heading === '5909') {
        return result(
            'technical-textile',
            'Hose / Textile Tubing',
            false,
            false,
            false,
            true,
            false,
            true,
            'HIGH',
            'HS heading 5909'
        );
    }

    /*
    | 5911 - Technical textile / filtering
    */

    if ($heading === '5911') {
        $family = 'Industrial / Technical Textile';

        if (
            containsAny($text, [
                'filtering',
                'straining',
                'filter',
                'press cloth',
            ])
        ) {
            $family = 'Filtration Textile';
        } elseif (
            containsAny($text, [
                'gaskets',
                'seals',
            ])
        ) {
            $family = 'Technical Textile Components';
        } elseif (
            containsAny($text, [
                'bolting cloth',
            ])
        ) {
            $family = 'Industrial Screening Textile';
        }

        return result(
            'technical-textile',
            $family,
            false,
            false,
            true,
            true,
            false,
            true,
            'HIGH',
            'HS heading 5911 - technical textile'
        );
    }

    /*
    | 6002 - Knitted / crocheted fabric
    */

    if ($heading === '6002') {
        return result(
            'fabric',
            'Knitted',
            false,
            false,
            true,
            false,
            false,
            false,
            'HIGH',
            'HS heading 6002'
        );
    }

    /*
    | 6301 - Blankets
    */

    if ($heading === '6301') {
        return result(
            'made-up-textile',
            'Blankets / Travel Rugs',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6301'
        );
    }

    /*
    | 6302 - Bed / table / kitchen linen
    */

    if ($heading === '6302') {
        $family = 'Bed / Table / Kitchen Linen';

        if (containsAny($text, [
            'bed linen',
            'bed-linen',
            'bedspread',
            'pillowcase',
            'pillow cases',
            'sheet',
            'sheets',
        ])) {
            $family = 'Bed Linen';
        } elseif (containsAny($text, [
            'table linen',
            'tablecloth',
            'table cloth',
            'napkin',
        ])) {
            $family = 'Table Linen';
        } elseif (containsAny($text, [
            'kitchen linen',
            'kitchen towel',
        ])) {
            $family = 'Kitchen Linen';
        }

        return result(
            'made-up-textile',
            $family,
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6302'
        );
    }

    /*
    | 6303 - Curtains
    */

    if ($heading === '6303') {
        return result(
            'made-up-textile',
            'Curtains / Interior Textile',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6303'
        );
    }

    /*
    | 6304 - Furnishing articles
    */

    if ($heading === '6304') {
        return result(
            'made-up-textile',
            'Household / Furnishing Textile',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6304'
        );
    }

    /*
    | 6305 - Sacks and bags
    */

    if ($heading === '6305') {
        return result(
            'made-up-textile',
            'Sacks / Bags',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6305'
        );
    }

    /*
    | 6306 - Covers / tarpaulins / camping
    */

    if ($heading === '6306') {
        return result(
            'made-up-textile',
            'Covers / Tarpaulins / Camping',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6306'
        );
    }

    /*
    | 6307 - Other made-up textile articles
    */

    if ($heading === '6307') {
        $family = 'Other Made-up Textile Articles';

        if (
            containsAny($text, [
                'mask',
                'masks',
            ])
        ) {
            $family = 'Textile Masks / Protective Articles';
        } elseif (
            containsAny($text, [
                'cleaning cloth',
                'cleaning cloths',
            ])
        ) {
            $family = 'Cleaning Textiles';
        } elseif (
            containsAny($text, [
                'shopping bag',
                'shopping bags',
            ])
        ) {
            $family = 'Shopping Bags';
        }

        return result(
            'made-up-textile',
            $family,
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6307'
        );
    }

    /*
    | 6310 - Textile rags / recovered textiles
    */

    if ($heading === '6310') {
        return result(
            'made-up-textile',
            'Textile Rags / Recovered Textile',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'HS heading 6310'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. GENERAL FABRIC PRIORITY
    |--------------------------------------------------------------------------
    */

    if (
        containsAny($text, [
            'woven fabric',
            'woven fabrics',
            'knitted fabric',
            'knitted fabrics',
            'fabric',
            'fabrics',
            'denim',
            'coated fabric',
            'coated fabrics',
            'laminated fabric',
            'laminated fabrics',
        ])
    ) {
        $family = 'Other Fabric';

        if (
            $chapter === '60'
            ||
            containsAny($text, [
                'knitted fabric',
                'knitted fabrics',
            ])
        ) {
            $family = 'Knitted';
        } elseif (
            str_contains($text, 'denim')
        ) {
            $family = 'Denim';
        } elseif (
            containsAny($text, [
                'coated fabric',
                'coated fabrics',
                'laminated fabric',
                'laminated fabrics',
            ])
        ) {
            $family = 'Coated / Laminated';
        } else {
            $family = 'Woven';
        }

        return result(
            'fabric',
            $family,
            false,
            false,
            true,
            false,
            false,
            false,
            'HIGH',
            'Explicit fabric form'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 4. GENERAL TWINE / YARN
    |--------------------------------------------------------------------------
    */

    if (
        containsAny($text, [
            'yarn',
            'sewing thread',
            'thread',
            'twine',
            'cordage',
            'cabled',
            'spun yarn',
        ])
    ) {
        $family = 'Other Yarn / Thread';

        if (containsAny($text, [
            'cotton yarn',
            'yarn of cotton',
            'cotton sewing thread',
        ])) {
            $family = 'Cotton Yarn';
        } elseif (
            containsAny($text, [
                'polyester yarn',
                'yarn of polyester',
            ])
        ) {
            $family = 'Polyester Yarn';
        } elseif (
            containsAny($text, [
                'polyamide yarn',
                'nylon yarn',
            ])
        ) {
            $family = 'Polyamide / Nylon Yarn';
        } elseif (
            containsAny($text, [
                'viscose yarn',
                'rayon yarn',
            ])
        ) {
            $family = 'Viscose / Rayon Yarn';
        } elseif (
            containsAny($text, [
                'twine',
                'cordage',
            ])
        ) {
            $family = 'Twine / Cordage';
        } elseif (
            containsAny($text, [
                'sewing thread',
            ])
        ) {
            $family = 'Sewing Thread';
        }

        return result(
            'yarn',
            $family,
            false,
            true,
            false,
            false,
            false,
            false,
            'HIGH',
            'Yarn / thread / twine form'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 5. MAN-MADE FILAMENT / MONOFILAMENT
    |--------------------------------------------------------------------------
    */

    if (
        in_array($chapter, ['54'], true)
        &&
        containsAny($text, [
            'monofilament',
            'synthetic filament',
            'artificial filament',
            'filament',
            'strip',
        ])
    ) {
        $family = 'Man-Made Filament';

        if (str_contains($text, 'monofilament')) {
            $family = 'Monofilament';
        } elseif (
            containsAny($text, [
                'strip',
                'artificial straw',
            ])
        ) {
            $family = 'Man-Made Textile Strip';
        }

        return result(
            'fiber',
            $family,
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'Man-made filament intermediate'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 6. FILAMENT TOW / MAN-MADE STAPLE FIBERS
    |--------------------------------------------------------------------------
    */

    if (
        $chapter === '55'
        &&
        containsAny($text, [
            'filament tow',
        ])
    ) {
        $family = 'Man-Made Filament Tow';

        if (str_contains($text, 'polyester')) {
            $family = 'Polyester Filament Tow';
        } elseif (
            containsAny($text, [
                'polyamide',
                'nylon',
            ])
        ) {
            $family = 'Polyamide / Nylon Filament Tow';
        } elseif (
            containsAny($text, [
                'acrylic',
                'modacrylic',
            ])
        ) {
            $family = 'Acrylic / Modacrylic Filament Tow';
        } elseif (
            str_contains($text, 'polypropylene')
        ) {
            $family = 'Polypropylene Filament Tow';
        }

        return result(
            'fiber',
            $family,
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'Filament tow'
        );
    }

    if (
        $chapter === '55'
        &&
        containsAny($text, [
            'staple fibres',
            'staple fibers',
        ])
    ) {
        $family = 'Other Man-Made Fiber';

        if (str_contains($text, 'polyester')) {
            $family = 'Polyester';
        } elseif (
            containsAny($text, [
                'polyamide',
                'nylon',
            ])
        ) {
            $family = 'Polyamide / Nylon';
        } elseif (
            containsAny($text, [
                'acrylic',
                'modacrylic',
            ])
        ) {
            $family = 'Acrylic / Modacrylic';
        } elseif (
            str_contains($text, 'polypropylene')
        ) {
            $family = 'Polypropylene';
        } elseif (
            containsAny($text, [
                'viscose',
                'rayon',
            ])
        ) {
            $family = 'Viscose / Rayon';
        }

        return result(
            'fiber',
            $family,
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'Man-made staple fiber'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 7. WADDING / TEXTILE FLOCK
    |--------------------------------------------------------------------------
    */

    if (
        $heading === '5601'
        ||
        containsAny($text, [
            'wadding',
            'textile flock',
            'mill neps',
        ])
    ) {
        if (containsAny($text, ['wadding'])) {
            return result(
                'made-up-textile',
                'Wadding',
                false,
                false,
                false,
                true,
                false,
                true,
                'HIGH',
                'Wadding'
            );
        }

        return result(
            'fiber',
            'Fiber Flock / Textile Waste',
            true,
            false,
            false,
            false,
            false,
            false,
            'MEDIUM',
            'Textile flock / mill neps'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 8. NETS
    |--------------------------------------------------------------------------
    */

    if (
        $heading === '5608'
        ||
        containsAny($text, [
            'fishing net',
            'fishing nets',
            'made up fishing nets',
            'nets',
        ])
    ) {
        return result(
            'made-up-textile',
            'Nets',
            false,
            false,
            false,
            true,
            false,
            true,
            'HIGH',
            'Textile net / made-up article'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 9. LABELS / BADGES
    |--------------------------------------------------------------------------
    */

    if (
        $heading === '5807'
        ||
        containsAny($text, [
            'label',
            'labels',
            'badge',
            'badges',
        ])
    ) {
        return result(
            'made-up-textile',
            'Labels / Badges',
            false,
            false,
            false,
            false,
            false,
            true,
            'HIGH',
            'Textile label / badge'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 10. RAW NATURAL FIBER
    |--------------------------------------------------------------------------
    */

    if (
        containsAny($text, [
            'silk-worm cocoons',
            'raw silk',
            'cotton, not carded or combed',
            'cotton, carded or combed',
            'wool, not carded or combed',
            'wool not carded or combed',
            'fine animal hair',
            'coarse animal hair',
            'flax, raw',
            'flax, broken',
            'hemp, raw',
            'jute',
            'coconut fibres',
            'abaca fibres',
        ])
    ) {
        $family = 'Other Natural Fiber';

        if (str_contains($text, 'cotton')) {
            $family = 'Cotton';
        } elseif (
            containsAny($text, [
                'silk',
                'silk-worm',
            ])
        ) {
            $family = 'Silk';
        } elseif (
            containsAny($text, [
                'wool',
                'animal hair',
                'cashmere',
            ])
        ) {
            $family = 'Wool / Animal Hair';
        } elseif (
            containsAny($text, [
                'flax',
                'linen',
            ])
        ) {
            $family = 'Flax / Linen';
        } elseif (
            str_contains($text, 'jute')
        ) {
            $family = 'Jute';
        } elseif (
            str_contains($text, 'hemp')
        ) {
            $family = 'Hemp';
        } elseif (
            containsAny($text, [
                'coconut fibres',
                'abaca fibres',
            ])
        ) {
            $family = 'Other Natural Bast / Leaf Fiber';
        }

        return result(
            'fiber',
            $family,
            true,
            false,
            false,
            false,
            false,
            false,
            'HIGH',
            'Raw / natural fiber'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 11. OTHER TEXTILE ARTICLES
    |--------------------------------------------------------------------------
    */

    if (
        containsAny($text, [
            'articles of textile materials',
            'textile articles',
        ])
    ) {
        return result(
            'made-up-textile',
            'Textile Articles',
            false,
            false,
            false,
            false,
            false,
            true,
            'MEDIUM',
            'General textile article'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 12. UNCLASSIFIED
    |--------------------------------------------------------------------------
    */

    return result(
        null,
        null,
        false,
        false,
        false,
        false,
        false,
        false,
        'LOW',
        'No V4 classification rule matched'
    );
}

/*
|--------------------------------------------------------------------------
| Load Master HS
|--------------------------------------------------------------------------
*/

$hsCodes = HsCode::query()
    ->orderBy('hs_code')
    ->get([
        'id_hs',
        'hs_code',
        'uraian_hs_id',
        'chapter',
        'heading',
        'subheading',
    ]);

echo "HS RECORDS: {$hsCodes->count()}\n\n";

/*
|--------------------------------------------------------------------------
| Output CSV
|--------------------------------------------------------------------------
*/

$handle = fopen($outputFile, 'wb');

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuat output: {$outputFile}"
    );
}

fputcsv(
    $handle,
    [
        'id_hs',
        'hs_code',
        'description',
        'chapter',
        'heading',
        'subheading',
        'suggested_sector',
        'suggested_product_family',
        'is_fiber',
        'is_yarn',
        'is_fabric',
        'is_technical_textile',
        'is_apparel',
        'is_madeup',
        'confidence',
        'reason',
    ]
);

$summary = [
    'fiber' => 0,
    'yarn' => 0,
    'fabric' => 0,
    'technical-textile' => 0,
    'apparel' => 0,
    'made-up-textile' => 0,
    'unclassified' => 0,
];

foreach ($hsCodes as $hs) {
    $classification = classifyHs(
        $hs->hs_code,
        $hs->uraian_hs_id
    );

    $sector = $classification['suggested_sector'];

    if ($sector === null) {
        $summary['unclassified']++;
    } else {
        $summary[$sector]++;
    }

    fputcsv(
        $handle,
        [
            $hs->id_hs,
            $hs->hs_code,
            $hs->uraian_hs_id,
            $hs->chapter,
            $hs->heading,
            $hs->subheading,
            $classification['suggested_sector'],
            $classification['suggested_product_family'],
            $classification['is_fiber'] ? 1 : 0,
            $classification['is_yarn'] ? 1 : 0,
            $classification['is_fabric'] ? 1 : 0,
            $classification['is_technical_textile'] ? 1 : 0,
            $classification['is_apparel'] ? 1 : 0,
            $classification['is_madeup'] ? 1 : 0,
            $classification['confidence'],
            $classification['reason'],
        ]
    );
}

fclose($handle);

echo "========================================\n";
echo "CLASSIFICATION V4 COMPLETE\n";
echo "========================================\n\n";

echo "Output:\n{$outputFile}\n\n";

echo "SUMMARY:\n";

foreach ($summary as $sector => $count) {
    echo sprintf(
        "%-25s : %d\n",
        $sector,
        $count
    );
}

echo "\n========================================\n";
echo "NO DATABASE UPDATE WAS PERFORMED.\n";
echo "========================================\n";