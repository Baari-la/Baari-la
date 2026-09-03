<?php

declare(strict_types=1);

namespace App\Services\Trade\Taxonomy;

use InvalidArgumentException;

class TextileTaxonomyService
{
    /**
     * --------------------------------------------------------------------------
     * Taxonomy Configuration
     * --------------------------------------------------------------------------
     */
    protected array $taxonomy;

    public function __construct()
    {
        $this->taxonomy = config(
            'digestex_textile_taxonomy',
            []
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Classify HS Code
     * --------------------------------------------------------------------------
     *
     * Returns:
     *
     * [
     *     'sector' => 'thread',
     *     'subsector' => 'cotton_sewing_thread',
     *     'label_en' => 'Cotton Sewing Thread',
     *     'label_id' => 'Benang Jahit Kapas',
     * ]
     *
     * Returns null when the HS code is not classified.
     */
    public function classify(
        string|int|null $hsCode
    ): ?array {
        $normalized = $this->normalizeHsCode(
            $hsCode
        );

        if ($normalized === null) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Exact / HS4 based classification
        |--------------------------------------------------------------------------
        */
        foreach ($this->taxonomy as $sectorKey => $sector) {

            $result = $this->searchSubsectors(
                $sectorKey,
                $sector,
                $normalized
            );

            if ($result !== null) {
                return $result;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Chapter based classification
            |--------------------------------------------------------------------------
            */
            if (
                isset($sector['chapters'])
                && is_array($sector['chapters'])
            ) {
                $chapter = substr(
                    $normalized,
                    0,
                    2
                );

                if (
                    in_array(
                        $chapter,
                        array_map(
                            'strval',
                            $sector['chapters']
                        ),
                        true
                    )
                ) {
                    return [
                        'sector' => $sectorKey,

                        'subsector' => null,

                        'label_en' =>
                            $sector['label_en'] ??
                            ucfirst($sectorKey),

                        'label_id' =>
                            $sector['label_id'] ??
                            ucfirst($sectorKey),

                        'hs_code' =>
                            $normalized,

                        'hs4' =>
                            substr(
                                $normalized,
                                0,
                                4
                            ),

                        'chapter' =>
                            $chapter,

                        'classification_level' =>
                            'chapter',
                    ];
                }
            }
        }

        return null;
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize HS Code
     * --------------------------------------------------------------------------
     *
     * Accepts:
     *
     * 52040000
     * 5204
     * "5204.00.00"
     * "HS 52040000"
     */
    public function normalizeHsCode(
        string|int|null $hsCode
    ): ?string {
        if ($hsCode === null) {
            return null;
        }

        $value = trim(
            (string) $hsCode
        );

        if ($value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove common formatting
        |--------------------------------------------------------------------------
        */
        $value = strtoupper($value);

        $value = str_replace(
            [
                'HS',
                '.',
                '-',
                ' ',
            ],
            '',
            $value
        );

        /*
        |--------------------------------------------------------------------------
        | HS should contain digits only.
        |--------------------------------------------------------------------------
        */
        if (!preg_match(
            '/^\d{2,8}$/',
            $value
        )) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize to 8 digits when possible.
        |--------------------------------------------------------------------------
        */
        if (strlen($value) < 8) {
            $value = str_pad(
                $value,
                8,
                '0'
            );
        }

        return substr(
            $value,
            0,
            8
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Search Sector Subsectors
     * --------------------------------------------------------------------------
     */
    protected function searchSubsectors(
        string $sectorKey,
        array $sector,
        string $hsCode
    ): ?array {
        if (
            !isset($sector['subsectors'])
            || !is_array($sector['subsectors'])
        ) {
            return null;
        }

        foreach (
            $sector['subsectors']
            as $subsectorKey => $subsector
        ) {
            /*
            |--------------------------------------------------------------------------
            | Chapter based classification at subsector level
            |--------------------------------------------------------------------------
            */
            if (
                isset($subsector['chapters'])
                && is_array($subsector['chapters'])
            ) {
                $chapter = substr(
                    $hsCode,
                    0,
                    2
                );

                $chapterList = array_map(
                    'strval',
                    $subsector['chapters']
                );

                if (
                    in_array(
                        $chapter,
                        $chapterList,
                        true
                    )
                ) {
                    return [
                        'sector' =>
                            $sectorKey,

                        'subsector' =>
                            $subsectorKey,

                        'label_en' =>
                            $subsector['label_en'] ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $subsectorKey
                                )
                            ),

                        'label_id' =>
                            $subsector['label_id'] ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $subsectorKey
                                )
                            ),

                        'hs_code' =>
                            $hsCode,

                        'hs4' =>
                            substr(
                                $hsCode,
                                0,
                                4
                            ),

                        'chapter' =>
                            $chapter,

                        'classification_level' =>
                            'chapter',
                    ];
                }
            }
        
            /*
            |--------------------------------------------------------------------------
            | Direct HS4
            |--------------------------------------------------------------------------
            */
            if (
                isset($subsector['hs4'])
                && is_array($subsector['hs4'])
            ) {
                $hs4 =
                    substr(
                        $hsCode,
                        0,
                        4
                    );

                $hs4List = array_map(
                    'strval',
                    $subsector['hs4']
                );

                if (
                    in_array(
                        $hs4,
                        $hs4List,
                        true
                    )
                ) {
                    return [
                        'sector' =>
                            $sectorKey,

                        'subsector' =>
                            $subsectorKey,

                        'label_en' =>
                            $subsector['label_en'] ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $subsectorKey
                                )
                            ),

                        'label_id' =>
                            $subsector['label_id'] ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $subsectorKey
                                )
                            ),

                        'hs_code' =>
                            $hsCode,

                        'hs4' =>
                            $hs4,

                        'chapter' =>
                            substr(
                                $hsCode,
                                0,
                                2
                            ),

                        'classification_level' =>
                            'hs4',
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Nested subsectors
            |--------------------------------------------------------------------------
            */
            if (
                isset($subsector['subsectors'])
                && is_array(
                    $subsector['subsectors']
                )
            ) {
                $nested =
                    $this->searchNestedSubsectors(
                        $sectorKey,
                        $subsectorKey,
                        $subsector,
                        $hsCode
                    );

                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        return null;
    }

    /**
     * --------------------------------------------------------------------------
     * Search Nested Subsectors
     * --------------------------------------------------------------------------
     */
    protected function searchNestedSubsectors(
        string $sectorKey,
        string $parentKey,
        array $parent,
        string $hsCode
    ): ?array {
        foreach (
            $parent['subsectors']
            as $subsectorKey => $subsector
        ) {
            if (
                isset($subsector['hs4'])
                && is_array($subsector['hs4'])
            ) {
                $hs4 =
                    substr(
                        $hsCode,
                        0,
                        4
                    );

                $hs4List = array_map(
                    'strval',
                    $subsector['hs4']
                );

                if (
                    in_array(
                        $hs4,
                        $hs4List,
                        true
                    )
                ) {
                    return [
                        'sector' =>
                            $sectorKey,

                        'subsector' =>
                            $subsectorKey,

                        'parent_subsector' =>
                            $parentKey,

                        'label_en' =>
                            $subsector['label_en'] ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $subsectorKey
                                )
                            ),

                        'label_id' =>
                            $subsector['label_id'] ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $subsectorKey
                                )
                            ),

                        'hs_code' =>
                            $hsCode,

                        'hs4' =>
                            $hs4,

                        'chapter' =>
                            substr(
                                $hsCode,
                                0,
                                2
                            ),

                        'classification_level' =>
                            'hs4',
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Recursive nested levels
            |--------------------------------------------------------------------------
            */
            if (
                isset($subsector['subsectors'])
                && is_array(
                    $subsector['subsectors']
                )
            ) {
                $nested =
                    $this->searchNestedSubsectors(
                        $sectorKey,
                        $subsectorKey,
                        $subsector,
                        $hsCode
                    );

                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        return null;
    }

/*
|--------------------------------------------------------------------------
| Get Canonical HS-8 Codes for a Sector
|--------------------------------------------------------------------------
|
| Returns only HS-8 codes that:
| - exist in the active HS master
| - are marked as textile
| - are successfully classified by the taxonomy
| - belong to the requested sector
|
| IMPORTANT:
| - HS-8 is the authoritative filtering level.
| - Chapter is NOT used as the final filter.
| - HS4/chapter taxonomy rules are still used for classification.
| - No trade rows are loaded here.
*/
public function hsCodesForSector(
    string $sectorKey
): array {
    $sectorKey = strtolower(
        trim($sectorKey)
    );

    if ($sectorKey === '') {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Load distinct HS-8 codes from trade_statistics
    |--------------------------------------------------------------------------
    */

            $hsCodes =
            \App\Models\HsCode::query()
                ->where('is_active', true)
                ->where('is_textile', true)
                ->pluck('hs_code')
                ->map(
                    fn ($hsCode) =>
                        $this->normalizeHsCode($hsCode)
                )
                ->filter()
                ->unique()
                ->values();

        if ($hsCodes->isEmpty()) {
            return [];
        }
    /*
    |--------------------------------------------------------------------------
    | Classify each HS-8
    |--------------------------------------------------------------------------
    */

    $classificationMap =
        $this->classifyMany(
            $hsCodes->all()
        );

    /*
    |--------------------------------------------------------------------------
    | Keep ONLY requested sector
    |--------------------------------------------------------------------------
    */

    return collect(
        $classificationMap
    )
        ->filter(
            fn ($classification) =>
                is_array($classification)
                &&
                ($classification['sector'] ?? null)
                    === $sectorKey
        )
        ->keys()
        ->map(
            fn ($hsCode) =>
                (string) $hsCode
        )
        ->values()
        ->all();
}

    /**
     * --------------------------------------------------------------------------
     * Classify Many HS Codes
     * --------------------------------------------------------------------------
     */
    public function classifyMany(
        array $hsCodes
    ): array {
        $result = [];

        foreach ($hsCodes as $hsCode) {
            $normalized =
                $this->normalizeHsCode(
                    $hsCode
                );

            if ($normalized === null) {
                continue;
            }

            $result[$normalized] =
                $this->classify(
                    $normalized
                );
        }

        return $result;
    }

    /**
     * --------------------------------------------------------------------------
     * Sector Check
     * --------------------------------------------------------------------------
     */
    public function isSector(
        string|int|null $hsCode,
        string $sector
    ): bool {
        $classification =
            $this->classify(
                $hsCode
            );

        if ($classification === null) {
            return false;
        }

        return $classification['sector'] ===
            $sector;
    }

    /**
     * --------------------------------------------------------------------------
     * Get Sector
     * --------------------------------------------------------------------------
     */
    public function sector(
        string|int|null $hsCode
    ): ?string {
        return $this->classify(
            $hsCode
        )['sector'] ?? null;
    }

    /**
     * --------------------------------------------------------------------------
     * Get Subsector
     * --------------------------------------------------------------------------
     */
    public function subsector(
        string|int|null $hsCode
    ): ?string {
        return $this->classify(
            $hsCode
        )['subsector'] ?? null;
    }

    /**
     * --------------------------------------------------------------------------
     * Get Full Taxonomy
     * --------------------------------------------------------------------------
     */
    public function taxonomy(): array
    {
        return $this->taxonomy;
    }

    /**
     * --------------------------------------------------------------------------
     * Get Sector Configuration
     * --------------------------------------------------------------------------
     */
    public function sectorConfig(
        string $sector
    ): ?array {
        return $this->taxonomy[$sector]
            ?? null;
    }

    /**
     * --------------------------------------------------------------------------
     * List Sectors
     * --------------------------------------------------------------------------
     */
    public function sectors(): array
    {
        return array_keys(
            $this->taxonomy
        );
    }
}