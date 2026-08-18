<?php

declare(strict_types=1);

namespace App\Services\Trade;

class TradeConversionMethodologyService
{
    /**
     * Resolve the conversion methodology for one Garment HS-8.
     *
     * This service is READ-ONLY.
     *
     * It does not:
     * - update database records
     * - assign conversion factors
     * - enable conversion
     * - persist conversion_method
     *
     * @return array{
     *     status: string,
     *     methodology: string,
     *     sub_group: string,
     *     reason: string
     * }
     */
    public function resolve(
        string $hsCode,
        string $description,
        string $unit,
        string $productGroup = '',
        string $productType = ''
    ): array {
        $hsCode = trim($hsCode);

        $text = mb_strtolower(
            trim($description)
        );

        $unit = strtoupper(
            trim($unit)
        );

        /*
        |--------------------------------------------------------------------------
        | 1. MIXED PRODUCT
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
                $this->subGroup($hsCode),
                'HS-8 combines materially different product forms.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. RESIDUAL
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
                $this->subGroup($hsCode),
                'Residual HS-8 is too broad for a defensible direct factor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. PAIR → KG
        |--------------------------------------------------------------------------
        */

        if ($unit === 'PAIR') {
            return $this->review(
                'PAIR_TO_KG',
                $this->subGroup($hsCode),
                'PAIR-based product requires validated average weight per pair.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. MULTI-PIECE PRODUCTS
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
        | 5. PROTECTIVE / WORK APPAREL
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
                $this->subGroup($hsCode),
                'Protective/work apparel has highly variable construction and weight.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. FOUNDATION GARMENTS
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
                $this->subGroup($hsCode),
                'Foundation garments require product-specific weight validation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. MEDICAL COMPRESSION
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
        | 8. SUPPORT / ATHLETIC BANDS
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
        | 9. SPECIALTY APPAREL
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
                    '62114931',
                    '62114939',

                    '62114230',
                    '62114370',
                    '62114950',
                ],
                true
            )
        ) {
            return $this->review(
                'PRODUCT_SPECIFIC',
                $this->subGroup($hsCode),
                'Specialty apparel requires product-specific methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 10. SLEEPWEAR / BATHROBES
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
                $this->subGroup($hsCode),
                'Sleepwear/bathrobe products require validated product-weight methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. CLOTHING ACCESSORIES
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
        | 12. DEFAULT PCS → KG
        |--------------------------------------------------------------------------
        */

        if ($unit === 'PCS') {
            return $this->review(
                'PCS_TO_KG',
                $this->subGroup($hsCode),
                'PCS garment requires validated average weight per piece.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. FALLBACK
        |--------------------------------------------------------------------------
        */

        return $this->review(
            'MANUAL_REVIEW',
            $this->subGroup($hsCode),
            'Conversion methodology could not be safely determined automatically.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CANONICAL SUB-GROUP RESOLUTION
    |--------------------------------------------------------------------------
    |
    | v1 intentionally uses the validated HS-8 mappings already established
    | during the semantic integrity audit.
    |
    | Unknown mappings remain explicitly marked instead of being guessed.
    |
    */

    protected function subGroup(
        string $hsCode
    ): string {
        $map = [

            /*
            | Mixed Baby Garments / Accessories
            */

            '61112000' => 'Mixed Baby Garments / Accessories',
            '61113000' => 'Mixed Baby Garments / Accessories',
            '61119010' => 'Mixed Baby Garments / Accessories',
            '61119090' => 'Mixed Baby Garments / Accessories',

            /*
            | Mixed Apparel / Bathrobe
            */

            '61089100' => 'Mixed Apparel / Bathrobe',
            '61089200' => 'Mixed Apparel / Bathrobe',
            '61089900' => 'Mixed Apparel / Bathrobe',
            '62079100' => 'Mixed Apparel / Bathrobe',
            '62079910' => 'Mixed Apparel / Bathrobe',
            '62079990' => 'Mixed Apparel / Bathrobe',

            /*
            | Residual / Other Garments
            */

            '61142000' => 'Residual / Other Garments',
            '61149010' => 'Residual / Other Garments',
            '61149090' => 'Residual / Other Garments',

            /*
            | Other Specialty Woven Apparel
            */

            '62113290' => 'Other Specialty Woven Apparel',
            '62114290' => 'Other Specialty Woven Apparel',
            '62114390' => 'Other Specialty Woven Apparel',
            '62114960' => 'Other Specialty Woven Apparel',
            '62114990' => 'Other Specialty Woven Apparel',

            /*
            | Foundation Garments
            */

            '62121011' => 'Bras / Mastectomy Bras',
            '62121019' => 'Bras / Mastectomy Bras',
            '62121091' => 'Bras / Mastectomy Bras',
            '62121099' => 'Bras / Mastectomy Bras',

            '62122010' => 'Girdles / Panty-Girdles',
            '62122090' => 'Girdles / Panty-Girdles',

            '62123010' => 'Corselettes',
            '62123090' => 'Corselettes',

            /*
            | Medical Compression
            */

            '62129011' => 'Medical Compression Garments',
            '62129091' => 'Medical Compression Garments',

            /*
            | Support / Athletic Bands
            */

            '61178020' => 'Support / Athletic Bands',
            '62129012' => 'Support / Athletic Bands',
            '62129092' => 'Support / Athletic Bands',

            /*
            | Fencing / Wrestling
            */

            '62113210' => 'Fencing / Wrestling Apparel',
            '62113310' => 'Fencing / Wrestling Apparel',
            '62113910' => 'Fencing / Wrestling Apparel',
            '62114210' => 'Fencing / Wrestling Apparel',
            '62114340' => 'Fencing / Wrestling Apparel',
            '62114910' => 'Fencing / Wrestling Apparel',

            /*
            | Pilgrimage Robes / Ihram
            */

            '62113220' => 'Pilgrimage Robes / Ihram',
            '62113340' => 'Pilgrimage Robes / Ihram',
            '62113940' => 'Pilgrimage Robes / Ihram',

            /*
            | Prayer Cloaks
            */

            '62114220' => 'Prayer Cloaks',
            '62114320' => 'Prayer Cloaks',
            '62114931' => 'Prayer Cloaks',
            '62114939' => 'Prayer Cloaks',

            /*
            | Sarong
            */

            '62114230' => 'Sarong',
            '62114370' => 'Sarong',
            '62114950' => 'Sarong',
        ];

        return $map[$hsCode]
            ?? 'SEMANTIC_GROUP_FROM_V2.6';
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
    | RESULT BUILDERS
    |--------------------------------------------------------------------------
    */

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