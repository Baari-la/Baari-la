<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use Illuminate\Console\Command;

class AuditGarmentConversionFactors extends Command
{
    protected $signature = 'digestex:audit-garment-conversion-factors';

    protected $description =
        'Audit HS-8 Garment conversion factors before activation.';

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
                'official_unit',
                'intelligence_unit',
                'conversion_enabled',
                'conversion_factor',
                'conversion_method',
                'conversion_source',
                'conversion_confidence',
            ])
            ->orderBy('hs_code')
            ->get();

        $this->info(
            'DIGESTEX Garment HS-8 Conversion Factor Audit'
        );

        $this->newLine();

        $this->line(
            'Total HS-8: ' . $rows->count()
        );

        $this->newLine();

        $this->table(
            [
                'HS-8',
                'Product Type',
                'Unit',
                'Factor',
                'Enabled',
                'Confidence',
            ],
            $rows->map(function ($row) {

                return [
                    $row->hs_code,

                    $row->product_type,

                    $row->intelligence_unit
                        ?: $row->official_unit
                        ?: '-',

                    $row->conversion_factor
                        !== null
                        ? $row->conversion_factor
                        : '-',

                    $row->conversion_enabled
                        ? 'YES'
                        : 'NO',

                    $row->conversion_confidence
                        ?: '-',
                ];

            })->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $summary = [
            'PCS' => $rows
                ->where('intelligence_unit', 'PCS')
                ->count(),

            'PAIR' => $rows
                ->where('intelligence_unit', 'PAIR')
                ->count(),

            'With Factor' => $rows
                ->whereNotNull('conversion_factor')
                ->count(),

            'Conversion Enabled' => $rows
                ->where('conversion_enabled', true)
                ->count(),

            'Without Factor' => $rows
                ->whereNull('conversion_factor')
                ->count(),
        ];

        $this->table(
            ['Status', 'HS-8'],
            collect($summary)
                ->map(
                    fn ($value, $key) => [
                        $key,
                        $value,
                    ]
                )
                ->values()
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        $enabledWithoutFactor = $rows
            ->filter(
                fn ($row) =>
                    $row->conversion_enabled
                    && $row->conversion_factor === null
            );

        if ($enabledWithoutFactor->isNotEmpty()) {

            $this->newLine();

            $this->error(
                'SAFETY CHECK FAILED.'
            );

            $this->error(
                'Some HS-8 records are conversion-enabled without a factor.'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info(
            'Safety check passed.'
        );

        $this->info(
            'No conversion factor has been activated automatically.'
        );

        return self::SUCCESS;
    }
}