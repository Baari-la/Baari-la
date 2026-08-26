<?php

namespace App\Services\TradeIntelligence\Support;

final class TradeFlowNormalizer
{
    /*
    |--------------------------------------------------------------------------
    | Normalize Trade Flow
    |--------------------------------------------------------------------------
    |
    | Convert all supported trade-flow representations into
    | the canonical internal values:
    |
    |     import
    |     export
    |
    | Supported examples:
    |
    |     Import
    |     IMPORT
    |     impor
    |     I
    |     M
    |     Export
    |     EXPORT
    |     ekspor
    |     E
    |     X
    |
    */

    public function normalize(
        mixed $flow
    ): ?string {
        if ($flow === null) {
            return null;
        }

        $value = strtolower(
            trim(
                (string) $flow
            )
        );

        if ($value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        if (
            str_contains(
                $value,
                'import'
            )
            || in_array(
                $value,
                [
                    'impor',
                    'i',
                    'm',
                ],
                true
            )
        ) {
            return 'import';
        }

        /*
        |--------------------------------------------------------------------------
        | Export
        |--------------------------------------------------------------------------
        */

        if (
            str_contains(
                $value,
                'export'
            )
            || in_array(
                $value,
                [
                    'ekspor',
                    'e',
                    'x',
                ],
                true
            )
        ) {
            return 'export';
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Flow
        |--------------------------------------------------------------------------
        |
        | Preserve the original normalized value instead of
        | silently converting an unknown value into import/export.
        |
        */

        return $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Is Import
    |--------------------------------------------------------------------------
    */

    public function isImport(
        mixed $flow
    ): bool {
        return $this->normalize($flow) === 'import';
    }


    /*
    |--------------------------------------------------------------------------
    | Is Export
    |--------------------------------------------------------------------------
    */

    public function isExport(
        mixed $flow
    ): bool {
        return $this->normalize($flow) === 'export';
    }


    /*
    |--------------------------------------------------------------------------
    | Is Valid Canonical Flow
    |--------------------------------------------------------------------------
    */

    public function isTradeFlow(
        mixed $flow
    ): bool {
        return in_array(
            $this->normalize($flow),
            [
                'import',
                'export',
            ],
            true
        );
    }
}