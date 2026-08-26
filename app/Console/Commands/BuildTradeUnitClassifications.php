<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\HsCode;
use App\Models\TradeStatistic;
use App\Models\TradeUnitClassification;
use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use Illuminate\Console\Command;
use Throwable;

class BuildTradeUnitClassifications extends Command
{
    protected $signature = 'digestex:build-trade-unit-classifications
                            {--sector= : Build only one sector}
                            {--fresh : Delete existing classifications first}
                            {--batch=500 : Number of HS-8 records per batch}';

    protected $description =
        'Build DIGESTEX HS-8 Trade Unit Classification master from Canonical HS-8 Master and trade_statistics.';

    public function handle(
        TextileTaxonomyService $taxonomy
    ): int {

        $this->info(
            'Building DIGESTEX HS-8 Trade Unit Classification master...'
        );

        $startedAt = microtime(true);

        try {

            /*
            |--------------------------------------------------------------------------
            | Options
            |--------------------------------------------------------------------------
            */

            $sectorFilter = $this->option('sector');

            $batchSize = max(
                50,
                (int) $this->option('batch')
            );

            /*
            |--------------------------------------------------------------------------
            | Fresh
            |--------------------------------------------------------------------------
            */

            if ($this->option('fresh')) {

                if (
                    ! $this->confirm(
                        'Delete existing trade_unit_classifications first?'
                    )
                ) {
                    $this->warn(
                        'Operation cancelled.'
                    );

                    return self::SUCCESS;
                }

                TradeUnitClassification::query()->delete();

                $this->info(
                    'Existing classifications deleted.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CANONICAL HS-8 UNIVERSE
            |--------------------------------------------------------------------------
            |
            | Canonical HS-8 Master is the authoritative universe.
            |
            | trade_statistics is NOT used to discover the HS-8 universe.
            |
            */

            $canonicalQuery = HsCode::query()
                ->where('is_active', true)
                ->where('is_textile', true);

            /*
            |--------------------------------------------------------------------------
            | Sector Filter
            |--------------------------------------------------------------------------
            |
            | Use TextileTaxonomyService as the authoritative classifier.
            | This keeps sector selection consistent with the rest of DIGESTEX.
            |
            */

            if ($sectorFilter) {

                $sectorHsCodes = $taxonomy->hsCodesForSector(
                    (string) $sectorFilter
                );

                if (empty($sectorHsCodes)) {

                    $this->warn(
                        "No canonical HS-8 found for sector: {$sectorFilter}"
                    );

                    return self::SUCCESS;
                }

                $canonicalQuery->whereIn(
                    'hs_code',
                    $sectorHsCodes
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Canonical HS-8 Count
            |--------------------------------------------------------------------------
            */

            $total = (clone $canonicalQuery)->count();

            $this->info(
                "Canonical HS-8 candidates: {$total}"
            );

            if ($total === 0) {

                $this->warn(
                    'No canonical HS-8 records available.'
                );

                return self::SUCCESS;
            }

            /*
            |--------------------------------------------------------------------------
            | Counters
            |--------------------------------------------------------------------------
            */

            $processed = 0;
            $classified = 0;
            $unclassified = 0;
            $skipped = 0;

            $sectorCounts = [];

            /*
            |--------------------------------------------------------------------------
            | Process Canonical HS-8 Master
            |--------------------------------------------------------------------------
            */

            $canonicalQuery
                ->orderBy('id_hs')
                ->chunkById(
                    $batchSize,
                    function ($rows) use (
                        $taxonomy,
                        $sectorFilter,
                        &$processed,
                        &$classified,
                        &$unclassified,
                        &$skipped,
                        &$sectorCounts
                    ) {

                        foreach ($rows as $row) {

                            $processed++;

                            $hsCode = trim(
                                (string) $row->hs_code
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Canonical HS-8 Safety
                            |--------------------------------------------------------------------------
                            */

                            if (
                                ! preg_match(
                                    '/^\d{8}$/',
                                    $hsCode
                                )
                            ) {

                                $skipped++;

                                continue;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Taxonomy Classification
                            |--------------------------------------------------------------------------
                            */

                            $classification =
                                $taxonomy->classify(
                                    $hsCode
                                );

                            /*
                            |--------------------------------------------------------------------------
                            | No Classification
                            |--------------------------------------------------------------------------
                            */

                            if (
                                ! is_array($classification)
                                || empty(
                                    $classification['sector']
                                )
                            ) {

                                $unclassified++;

                                continue;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Sector Validation
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $sectorFilter
                                && $classification['sector']
                                    !== strtolower(
                                        trim(
                                            (string) $sectorFilter
                                        )
                                    )
                            ) {

                                $skipped++;

                                continue;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Representative Trade Description
                            |--------------------------------------------------------------------------
                            |
                            | Canonical HS-8 remains authoritative.
                            | Description may be taken from trade_statistics
                            | as the actual trade-data representation.
                            |
                            */

                            $tradeDescription =
                                TradeStatistic::query()
                                    ->where(
                                        'hs_code',
                                        $hsCode
                                    )
                                    ->whereNotNull(
                                        'hs_description'
                                    )
                                    ->where(
                                        'hs_description',
                                        '!=',
                                        ''
                                    )
                                    ->orderByDesc(
                                        'year'
                                    )
                                    ->orderByDesc(
                                        'month'
                                    )
                                    ->value(
                                        'hs_description'
                                    );

                            /*
                            |--------------------------------------------------------------------------
                            | Official Unit
                            |--------------------------------------------------------------------------
                            |
                            | Determine the most frequently used trade
                            | volume unit for this HS-8.
                            |
                            */

                            $officialUnit =
                                TradeStatistic::query()
                                    ->where(
                                        'hs_code',
                                        $hsCode
                                    )
                                    ->whereNotNull(
                                        'volume_unit'
                                    )
                                    ->select(
                                        'volume_unit'
                                    )
                                    ->selectRaw(
                                        'COUNT(*) AS unit_count'
                                    )
                                    ->groupBy(
                                        'volume_unit'
                                    )
                                    ->orderByDesc(
                                        'unit_count'
                                    )
                                    ->value(
                                        'volume_unit'
                                    );

                            $officialUnit =
                                $officialUnit
                                    ? strtoupper(
                                        trim(
                                            (string) $officialUnit
                                        )
                                    )
                                    : null;

                            /*
                            |--------------------------------------------------------------------------
                            | Current Intelligence Unit
                            |--------------------------------------------------------------------------
                            |
                            | Conversion remains disabled until a validated
                            | HS-8 conversion factor exists.
                            |
                            */

                            $intelligenceUnit =
                                $officialUnit;

                            /*
                            |--------------------------------------------------------------------------
                            | Product Classification
                            |--------------------------------------------------------------------------
                            */

                            $productType =
                                $classification['subsector']
                                ?? null;

                            $productGroup =
                                $classification['label_en']
                                ?? $classification['label_id']
                                ?? null;

                            /*
                            |--------------------------------------------------------------------------
                            | Persist
                            |--------------------------------------------------------------------------
                            */

                            TradeUnitClassification::updateOrCreate(

                                [
                                    'hs_code' =>
                                        $hsCode,
                                ],

                                [

                                    'hs_description' =>
                                        $tradeDescription
                                        ?? $row->uraian_hs_en
                                        ?? $row->uraian_hs_id
                                        ?? null,

                                    'sector' =>
                                        $classification['sector'],

                                    'product_type' =>
                                        $productType,

                                    'product_group' =>
                                        $productGroup,

                                    'official_unit' =>
                                        $officialUnit,

                                    'intelligence_unit' =>
                                        $intelligenceUnit,

                                    'conversion_enabled' =>
                                        false,

                                    'status' =>
                                        'active',

                                    'classification_source' =>
                                        'Canonical HS-8 Master + TextileTaxonomyService',

                                    'notes' =>
                                        'Generated from the Canonical HS-8 Master. Trade statistics are used only for representative description and official volume unit. Conversion is enabled only after validated HS-8 conversion factor resolution.',
                                ]
                            );

                            $classified++;

                            $sector =
                                $classification['sector'];

                            $sectorCounts[$sector] =
                                ($sectorCounts[$sector] ?? 0) + 1;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Progress
                        |--------------------------------------------------------------------------
                        */

                        $this->output->write(
                            "\rProcessed: {$processed}"
                            . " | Classified: {$classified}"
                            . " | Skipped: {$skipped}"
                            . " | Unclassified: {$unclassified}"
                        );
                    },
                    'id_hs'
                );

            $this->newLine(2);

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $elapsed =
                round(
                    microtime(true) - $startedAt,
                    2
                );

            $this->info(
                'Trade Unit Classification build completed.'
            );

            $this->table(
                [
                    'Metric',
                    'Count',
                ],
                [
                    [
                        'Canonical HS-8 candidates',
                        $total,
                    ],
                    [
                        'Processed',
                        $processed,
                    ],
                    [
                        'Classified',
                        $classified,
                    ],
                    [
                        'Unclassified',
                        $unclassified,
                    ],
                    [
                        'Skipped',
                        $skipped,
                    ],
                    [
                        'Elapsed seconds',
                        $elapsed,
                    ],
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Sector Summary
            |--------------------------------------------------------------------------
            */

            if (! empty($sectorCounts)) {

                $this->newLine();

                $this->info(
                    'Classification by sector:'
                );

                foreach (
                    $sectorCounts as $sector => $count
                ) {

                    $this->line(
                        "  {$sector}: {$count}"
                    );
                }
            }

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Trade Unit Classification build failed.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}