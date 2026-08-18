<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeStatistic;
use App\Models\TradeUnitClassification;
use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class BuildTradeUnitClassifications extends Command
{
    protected $signature = 'digestex:build-trade-unit-classifications
                            {--sector= : Build only one sector}
                            {--fresh : Delete existing classifications first}
                            {--batch=500 : Number of HS-8 records per batch}';

    protected $description =
        'Build DIGESTEX HS-8 Trade Unit Classification master from trade_statistics and TextileTaxonomyService.';

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

            $sectorFilter =
                $this->option('sector');

            $batchSize =
                max(
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

                TradeUnitClassification::query()
                    ->delete();

                $this->info(
                    'Existing classifications deleted.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Sector Candidate Optimization
            |--------------------------------------------------------------------------
            |
            | This is ONLY a database performance optimization.
            |
            | Final sector classification still comes from
            | TextileTaxonomyService.
            |
            */

            $chapterPrefixes = [];

            if ($sectorFilter === 'garment') {

                $chapterPrefixes = [
                    '61',
                    '62',
                ];
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

            $lastHsCode = '';

            /*
            |--------------------------------------------------------------------------
            | Batch Loop
            |--------------------------------------------------------------------------
            */

            while (true) {

                /*
                |--------------------------------------------------------------------------
                | Build Query
                |--------------------------------------------------------------------------
                */

                $query = TradeStatistic::query()

                    ->whereNotNull('hs_code')

                    ->whereRaw(
                        "CHAR_LENGTH(REPLACE(hs_code, '.', '')) = 8"
                    )

                    ->where(
                        'hs_code',
                        '>',
                        $lastHsCode
                    );

                /*
                |--------------------------------------------------------------------------
                | Candidate Chapter Filter
                |--------------------------------------------------------------------------
                */

                if (! empty($chapterPrefixes)) {

                    $query->where(
                        function ($q) use ($chapterPrefixes) {

                            foreach (
                                $chapterPrefixes as $prefix
                            ) {

                                $q->orWhere(
                                    'hs_code',
                                    'like',
                                    $prefix . '%'
                                );
                            }
                        }
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | One Record Per HS-8
                |--------------------------------------------------------------------------
                |
                | We only need one representative description here.
                |
                */

                $rows = $query

                    ->selectRaw(
                        'hs_code, MAX(hs_description) AS hs_description'
                    )

                    ->groupBy(
                        'hs_code'
                    )

                    ->orderBy(
                        'hs_code'
                    )

                    ->limit(
                        $batchSize
                    )

                    ->get();

                /*
                |--------------------------------------------------------------------------
                | End
                |--------------------------------------------------------------------------
                */

                if ($rows->isEmpty()) {
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | Process Batch
                |--------------------------------------------------------------------------
                */

                foreach ($rows as $row) {

                    $lastHsCode =
                        $row->hs_code;

                    $processed++;

                    /*
                    |--------------------------------------------------------------------------
                    | Taxonomy Classification
                    |--------------------------------------------------------------------------
                    */

                    $classification =
                        $taxonomy->classify(
                            $row->hs_code
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
                            !== $sectorFilter
                    ) {

                        $skipped++;

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Official Unit
                    |--------------------------------------------------------------------------
                    |
                    | We determine the most frequently used
                    | volume unit for this HS-8.
                    |
                    */

                    $officialUnit =
                        TradeStatistic::query()

                            ->where(
                                'hs_code',
                                $row->hs_code
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
                    | No conversion yet.
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
                                $row->hs_code,
                        ],

                        [
                            'hs_description' =>
                                $row->hs_description,

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
                                'TextileTaxonomyService',

                            'notes' =>
                                'Generated from canonical trade_statistics HS-8 data. Conversion will be applied only after HS-8 conversion factor validation.',
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

                if (
                    $processed % 1000 === 0
                    || $rows->count() < $batchSize
                ) {

                    $this->line(
                        'Processed: ' .
                        $processed .
                        ' | Classified: ' .
                        $classified .
                        ' | Last HS-8: ' .
                        $lastHsCode
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Release Memory
                |--------------------------------------------------------------------------
                */

                unset($rows);

                gc_collect_cycles();
            }

            /*
            |--------------------------------------------------------------------------
            | Execution Time
            |--------------------------------------------------------------------------
            */

            $executionTime =
                round(
                    microtime(true) - $startedAt,
                    3
                );

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            $this->info(
                'HS-8 classification master generated successfully.'
            );

            $this->line(
                'Execution time: ' .
                $executionTime .
                ' seconds'
            );

            $this->line(
                'Processed: ' .
                $processed
            );

            $this->line(
                'Classified: ' .
                $classified
            );

            $this->line(
                'Unclassified: ' .
                $unclassified
            );

            $this->line(
                'Skipped by sector filter: ' .
                $skipped
            );

            /*
            |--------------------------------------------------------------------------
            | Sector Breakdown
            |--------------------------------------------------------------------------
            */

            if (! empty($sectorCounts)) {

                $this->newLine();

                $this->info(
                    'Sector classification:'
                );

                foreach (
                    $sectorCounts as $sector => $count
                ) {

                    $this->line(
                        '  ' .
                        $sector .
                        ': ' .
                        $count
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Conversion Status
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            $this->info(
                'Conversion status:'
            );

            $this->line(
                '  Conversion enabled: 0'
            );

            $this->line(
                '  Intelligence unit: official trade unit'
            );

            $this->line(
                '  HS-8 conversion factors: NOT YET APPLIED'
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Failed to build Trade Unit Classification master.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}