<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GarmentTradeIntelligenceService
{
    /*
    |--------------------------------------------------------------------------
    | Snapshot / Cache
    |--------------------------------------------------------------------------
    */

    public const CACHE_KEY =
        'digestex.trade.sector.garment';

    protected const SNAPSHOT_TYPE =
        'sector';

    protected const SECTOR =
        'garment';

    protected const CACHE_TTL =
        1800;


    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    public function __construct(
    protected TextileTaxonomyService $taxonomy,
    protected TradeIntelligenceSnapshotService $snapshotService,
    protected TradeReportingPeriodProvider $periodProvider,
    protected GarmentConversionService $conversionService,
    protected CountryResolverService $countryResolver,
) {
}


    /*
    |--------------------------------------------------------------------------
    | PUBLIC: Get Current Garment Intelligence
    |--------------------------------------------------------------------------
    */

    public function get(): array
{
    $period =
        $this->periodProvider->current();

    /*
    |--------------------------------------------------------------------------
    | Persistent Snapshot — Current Period
    |--------------------------------------------------------------------------
    */

    $snapshot =
        $this->snapshotService->get(
            self::CACHE_KEY
        );
   
    if (
        is_array($snapshot)
        && $this->snapshotMatchesPeriod(
            $snapshot,
            $period
        )
    ) {
        return $this->hasPeriodDatasets($snapshot)
            ? $this->assembleSnapshotForPeriod($snapshot, $period)
            : $snapshot;
    }

    /*
    |--------------------------------------------------------------------------
    | 3. LAST VALID SNAPSHOT FALLBACK
    |--------------------------------------------------------------------------
    |
    | If the latest snapshot is not yet available,
    | never immediately return an empty snapshot.
    |
    | The user should continue seeing the latest
    | valid intelligence available.
    |
    */

    if (
        is_array($snapshot)
        && $this->isValidSnapshot(
            $snapshot
        )
    ) {
        if ($this->hasPeriodDatasets($snapshot)) {
            return $this->markSnapshotAsFallback(
                $this->assembleSnapshotForPeriod(
                    $snapshot,
                    $this->snapshotPeriod($snapshot)
                ),
                $period
            );
        }

        return $this->markSnapshotAsFallback(
            $snapshot,
            $period
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Safe Empty Snapshot
    |--------------------------------------------------------------------------
    |
    | Only used when Digestex has never had a valid
    | snapshot available.
    |
    */

    return $this->emptySnapshot(
        $period
    );
}

/*
|--------------------------------------------------------------------------
| PUBLIC: Get Intelligence for Selected Period
|--------------------------------------------------------------------------
*/

public function getForPeriod(
    TradeReportingPeriod $period
): array {
    /*
    |--------------------------------------------------------------------------
    | 1. Persistent Period Snapshot
    |--------------------------------------------------------------------------
    |
    | Historical and selected periods are shared intelligence snapshots.
    | If the requested period has already been built by any user,
    | reuse it immediately.
    |
    */

    $periodSnapshotKey =
    self::CACHE_KEY
    . '.period.'
    . $period->snapshotKey();

$snapshot =
    $this->snapshotService->getForPeriod(
        $periodSnapshotKey
    );


/*
|--------------------------------------------------------------------------
| Historical Yearly Snapshot Rebuild
|--------------------------------------------------------------------------
|
| Older historical snapshots may have been generated before
| country bilingual metadata and flag information were added.
|
| If the existing snapshot does not contain country_name_en,
| country_name_id, and flag_emoji, force one rebuild so the
| latest validated snapshot receives the new structure.
|
*/

$needsHistoricalYearlyRebuild = false;

if (
    is_array($snapshot)
    && $period->isFullYear()
    && $period->publicThroughYear >= 2019
    && $period->publicThroughYear <= 2024
) {

    $descriptor =
        $this->periodDatasetDescriptor(
            $this->periodDatasetPeriod(
                $period->publicThroughYear,
                $period->publicThroughMonth,
                $period->mode,
            )
        );

    $exportRows =
        $snapshot['period_datasets'][$descriptor]
            ['major_export_destinations']
            [$period->publicThroughYear]
            ?? [];

    $importRows =
        $snapshot['period_datasets'][$descriptor]
            ['major_import_sources']
            [$period->publicThroughYear]
            ?? [];

    $sampleExport =
        $exportRows[0] ?? [];

    $sampleImport =
        $importRows[0] ?? [];

    $hasCountryMetadata =
        array_key_exists(
            'country_name_en',
            $sampleExport
        )
        && array_key_exists(
            'country_name_id',
            $sampleExport
        )
        && array_key_exists(
            'flag_emoji',
            $sampleExport
        )
        && array_key_exists(
            'country_name_en',
            $sampleImport
        )
        && array_key_exists(
            'country_name_id',
            $sampleImport
        )
        && array_key_exists(
            'flag_emoji',
            $sampleImport
        );

    $needsHistoricalYearlyRebuild =
        !$hasCountryMetadata;
}


if (
    is_array($snapshot)
    && $this->isPublishableSnapshot($snapshot)
    && !$needsHistoricalYearlyRebuild
) {
    return $this->assembleSnapshotForPeriod(
        $snapshot,
        $period
    );
}


    /*
    |--------------------------------------------------------------------------
    | 2. Build Snapshot Only When Not Yet Available
    |--------------------------------------------------------------------------
    |
    | This is the expensive path.
    |
    | It should happen only once for a given period snapshot key.
    |
    */

    $snapshot =
        $this->buildSnapshot(
            $period
        );


/*
|--------------------------------------------------------------------------
| Historical Yearly Snapshot Rebuild
|--------------------------------------------------------------------------
|
| Older historical snapshots may have been generated before
| country bilingual metadata and flag information were added.
|
| If the existing snapshot does not contain country_name_en,
| country_name_id, and flag_emoji, force one rebuild so the
| latest validated snapshot receives the new structure.
|
*/

$needsHistoricalYearlyRebuild = false;

if (
    is_array($snapshot)
    && $period->isFullYear()
    && $period->publicThroughYear >= 2019
    && $period->publicThroughYear <= 2024
) {

    $descriptor =
        $this->periodDatasetDescriptor(
            $this->periodDatasetPeriod(
                $period->publicThroughYear,
                $period->publicThroughMonth,
                $period->mode,
            )
        );

    $exportRows =
        $snapshot['period_datasets'][$descriptor]
            ['major_export_destinations']
            [$period->publicThroughYear]
            ?? [];

    $importRows =
        $snapshot['period_datasets'][$descriptor]
            ['major_import_sources']
            [$period->publicThroughYear]
            ?? [];

    $sampleExport =
        $exportRows[0] ?? [];

    $sampleImport =
        $importRows[0] ?? [];

    $hasCountryMetadata =
        array_key_exists(
            'country_name_en',
            $sampleExport
        )
        && array_key_exists(
            'country_name_id',
            $sampleExport
        )
        && array_key_exists(
            'flag_emoji',
            $sampleExport
        )
        && array_key_exists(
            'country_name_en',
            $sampleImport
        )
        && array_key_exists(
            'country_name_id',
            $sampleImport
        )
        && array_key_exists(
            'flag_emoji',
            $sampleImport
        );

    $needsHistoricalYearlyRebuild =
        !$hasCountryMetadata;
}


if (
    is_array($snapshot)
    && $this->isPublishableSnapshot($snapshot)
    && !$needsHistoricalYearlyRebuild
) {
    return $this->assembleSnapshotForPeriod(
        $snapshot,
        $period
    );
}

    /*
    |--------------------------------------------------------------------------
    | 3. Validate Before Publish
    |--------------------------------------------------------------------------
    */

    if (
        !$this->isPublishableSnapshot(
            $snapshot
        )
    ) {
        throw new \RuntimeException(
            'Unable to build valid Garment intelligence for selected period.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. Persist Shared Period Snapshot
    |--------------------------------------------------------------------------
    */

    $this->snapshotService->save(
        $periodSnapshotKey,
        $snapshot,
        [
            'snapshot_type' =>
                self::SNAPSHOT_TYPE,

            'sector' =>
                self::SECTOR,

            'period_key' =>
                $period->snapshotKey(),

            'generated_at' =>
                $snapshot['meta']['generated_at']
                    ?? now(),
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | 5. Return Published Snapshot
    |--------------------------------------------------------------------------
    */

    return $this->assembleSnapshotForPeriod(
        $snapshot,
        $period
    );
}


/*
|--------------------------------------------------------------------------
| Period Cache Key
|--------------------------------------------------------------------------
*/

protected function periodCacheKey(
    TradeReportingPeriod $period
): string {
    return self::CACHE_KEY
        . '.period.'
        . $period->snapshotKey();
}


    /*
    |--------------------------------------------------------------------------
    | PUBLIC: Refresh Snapshot
    |--------------------------------------------------------------------------
    */

    public function refresh(): array
{
    $period =
        $this->periodProvider->current();


    /*
    |--------------------------------------------------------------------------
    | Build Candidate Snapshot
    |--------------------------------------------------------------------------
    */

    $candidate =
        $this->buildSnapshot(
            $period
        );


    /*
    |--------------------------------------------------------------------------
    | Validate Candidate Before Publish
    |--------------------------------------------------------------------------
    */

    if (
        !$this->isPublishableSnapshot(
            $candidate
        )
    ) {
        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Never overwrite the last valid snapshot
        | with an invalid or incomplete candidate.
        |
        */

        throw new \RuntimeException(
            'Garment snapshot validation failed. Last valid snapshot was preserved.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Persistent Validated Snapshot
    |--------------------------------------------------------------------------
    */

    $this->snapshotService->save(
    $this->periodCacheKey($period),
    $candidate,
        [
            'snapshot_type' =>
                self::SNAPSHOT_TYPE,

            'sector' =>
                self::SECTOR,

            'period_key' =>
                $period->snapshotKey(),
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Return Published Snapshot
    |--------------------------------------------------------------------------
    */

    return $this->assembleSnapshotForPeriod(
        $candidate,
        $period
    );
}


    protected function isPublishableSnapshot(
    array $snapshot
): bool {
    /*
    |--------------------------------------------------------------------------
    | Basic Snapshot Structure
    |--------------------------------------------------------------------------
    */

    if (
        !isset($snapshot['meta'])
    ) {
        return false;
    }

    if ($this->hasPeriodDatasets($snapshot)) {
        return (int) ($snapshot['meta']['record_count'] ?? 0) > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Record Count
    |--------------------------------------------------------------------------
    */

    $recordCount =
        (int) (
            $snapshot['meta']['record_count']
            ?? 0
        );

    if ($recordCount <= 0) {
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Executive Overview
    |--------------------------------------------------------------------------
    */

    $import =
        $snapshot['overview']['import']
        ?? null;

    $export =
        $snapshot['overview']['export']
        ?? null;

    if (
        !is_array($import)
        || !is_array($export)
    ) {
        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Trade Value
    |--------------------------------------------------------------------------
    */

    foreach (
    [
        $import,
        $export,
    ] as $flow
) {
    if (
        !array_key_exists(
            'physical_volume_kg',
            $flow
        )
        ||
        !array_key_exists(
            'previous_physical_volume_kg',
            $flow
        )
        ||
        !array_key_exists(
            'physical_volume_growth_percent',
            $flow
        )
    ) {
        return false;
    }
}


    /*
    |--------------------------------------------------------------------------
    | Official Physical Volume — KG
    |--------------------------------------------------------------------------
    |
    | KG is now a mandatory part of the new
    | Garment Intelligence snapshot.
    |
    */

    foreach (
        [
            $import,
            $export,
        ] as $flow
    ) {
        if (
            !array_key_exists(
                'physical_volume_kg',
                $flow
            )
            ||
            !array_key_exists(
                'previous_physical_volume_kg',
                $flow
            )
        ) {
            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PCS Intelligence Structure
    |--------------------------------------------------------------------------
    |
    | PCS itself may still be NULL because conversion
    | coverage is intentionally being built gradually.
    |
    | We validate the structure, not the PCS value.
    |
    */

    foreach (
        [
            $import,
            $export,
        ] as $flow
    ) {
        if (
            !array_key_exists(
                'physical_volume_pcs',
                $flow
            )
            ||
            !array_key_exists(
                'physical_volume_coverage_percent',
                $flow
            )
            ||
            !array_key_exists(
                'physical_volume_converted_rows',
                $flow
            )
            ||
            !array_key_exists(
                'physical_volume_total_rows',
                $flow
            )
        ) {
            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Core Intelligence Collections
    |--------------------------------------------------------------------------
    */

    $requiredCollections = [
        'by_subsector',
        'by_flow',
        'top_import_products',
        'top_export_products',
        'top_import_origins',
        'top_export_destinations',
        'monthly_trend',
        'yearly_trend',
        'hs8_products',
    ];

    foreach (
        $requiredCollections as $key
    ) {
        if (
            !array_key_exists(
                $key,
                $snapshot
            )
            ||
            !is_array(
                $snapshot[$key]
            )
        ) {
            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Snapshot Period
    |--------------------------------------------------------------------------
    */

    if (
        empty(
            $snapshot['meta']['snapshot_period_key']
        )
    ) {
        return false;
    }


    return true;
}

/*
|--------------------------------------------------------------------------
| PUBLIC: Get Trade Data for Custom Period Selection
|--------------------------------------------------------------------------
|
| Example:
| year   = 2025
| months = [1, 2, 3]
| flow   = export
|
| Canonical HS-8 is the only product filter.
| Official trade_value and trade_volume are aggregated in SQL.
|
*/

public function getForSelection(
    int $year,
    array $months,
    ?string $flow = null
): array {

    /*
    |--------------------------------------------------------------------------
    | Normalize Months
    |--------------------------------------------------------------------------
    */

    $months = collect($months)
        ->map(fn ($month) => (int) $month)
        ->filter(
            fn ($month) =>
                $month >= 1 && $month <= 12
        )
        ->unique()
        ->sort()
        ->values()
        ->all();

    if (empty($months)) {
        return [
            'year' =>
                $year,

            'months' =>
                [],

            'flow' =>
                $flow,

            'trade_value' =>
                0,

            'trade_volume' =>
                0,

            'volume_unit' =>
                'KG',

            'hs8_count' =>
                0,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Canonical HS-8
    |--------------------------------------------------------------------------
    |
    | Garment universe comes exclusively from the
    | Canonical HS-8 Master.
    |
    */

    $garmentHsCodes =
        $this->taxonomy->hsCodesForSector(
            self::SECTOR
        );

    /*
    |--------------------------------------------------------------------------
    | Safety Check
    |--------------------------------------------------------------------------
    */

    if (empty($garmentHsCodes)) {
        return [
            'year' =>
                $year,

            'months' =>
                $months,

            'flow' =>
                $flow,

            'trade_value' =>
                0,

            'trade_volume' =>
                0,

            'volume_unit' =>
                'KG',

            'derived_pcs' =>
                0,

            'hs8_count' =>
                0,

            'aggregated_hs8_count' =>
                0,

            'convertible_hs8_count' =>
                0,

            'non_convertible_hs8_count' =>
                0,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Trade Query
    |--------------------------------------------------------------------------
    */

    $query = DB::table('trade_statistics')
        ->whereIn(
            'hs_code',
            $garmentHsCodes
        )
        ->where(
            'year',
            $year
        )
        ->whereIn(
            'month',
            $months
        );

    /*
    |--------------------------------------------------------------------------
    | Trade Flow
    |--------------------------------------------------------------------------
    */

    if ($flow !== null) {
        $query->where(
            'trade_flow',
            $flow
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregate Official Trade Data
    |--------------------------------------------------------------------------
    */

    $rows = $query
        ->select('hs_code')
        ->selectRaw(
            'SUM(trade_value) AS trade_value'
        )
        ->selectRaw(
            'SUM(trade_volume) AS trade_volume'
        )
        ->groupBy('hs_code')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Resolve Conversion Factors
    |--------------------------------------------------------------------------
    */

    $hsCodes = $rows
        ->pluck('hs_code')
        ->map(
            fn ($hsCode) =>
                trim((string) $hsCode)
        )
        ->filter(
            fn ($hsCode) =>
                preg_match(
                    '/^\d{8}$/',
                    $hsCode
                )
        )
        ->unique()
        ->values()
        ->all();

    $factorMap =
        $this->conversionService->resolveFactors(
            $hsCodes
        );

    
    /*
    |--------------------------------------------------------------------------
    | Calculate Totals
    |--------------------------------------------------------------------------
    */

    $totalTradeValue = 0.0;
    $totalTradeVolume = 0.0;
    $totalDerivedPcs = 0.0;

    $convertibleHs8Count = 0;
    $nonConvertibleHs8Count = 0;

    foreach ($rows as $row) {

        $hsCode = trim(
            (string) $row->hs_code
        );

        $tradeValue =
            $this->toFloat(
                $row->trade_value
            );

        $tradeVolume =
            $this->toFloat(
                $row->trade_volume
            );

        $totalTradeValue +=
            $tradeValue;

        $totalTradeVolume +=
            $tradeVolume;

        $resolved =
            $factorMap[$hsCode]
            ?? null;

        $factor =
            isset($resolved['factor'])
            && $resolved['factor'] !== null
                ? (float) $resolved['factor']
                : null;

        $isConvertible =
            ($resolved['resolution_code'] ?? null)
                === 'ACTIVE_FACTOR_FOUND'
            && $factor !== null
            && $factor > 0;

        if ($isConvertible) {

            $derivedPcs =
                $tradeVolume / $factor;

            $totalDerivedPcs +=
                $derivedPcs;

            $convertibleHs8Count++;

        } else {

            $nonConvertibleHs8Count++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Return
    |--------------------------------------------------------------------------
    */

    return [

        'year' =>
            $year,

        'months' =>
            $months,

        'flow' =>
            $flow,

        'trade_value' =>
            round(
                $totalTradeValue,
                6
            ),

        'trade_volume' =>
            round(
                $totalTradeVolume,
                6
            ),

        'volume_unit' =>
            'KG',

        'derived_pcs' =>
            round(
                $totalDerivedPcs,
                6
            ),

        'hs8_count' =>
            count(
                $garmentHsCodes
            ),

        'aggregated_hs8_count' =>
            count(
                $rows
            ),

        'convertible_hs8_count' =>
            $convertibleHs8Count,

        'non_convertible_hs8_count' =>
            $nonConvertibleHs8Count,
    ];
}

        /*
    |--------------------------------------------------------------------------
    | CORE SNAPSHOT BUILDER
    |--------------------------------------------------------------------------
    */

    protected function buildSnapshot(
        TradeReportingPeriod $period
    ): array {

    
    
        $periodDatasets = [];

        foreach ($this->periodDatasetRequests($period) as $descriptor => $datasetPeriod) {
            if (array_key_exists($descriptor, $periodDatasets)) {
                continue;
            }

            $periodDatasets[$descriptor] =
                $this->buildPeriodDataset($datasetPeriod);
        }

        return [
            'meta' => $this->snapshotMeta($period, $periodDatasets),
            'period_datasets' => $periodDatasets,
        ];
    }

/**
 * Build Executive Overview for Historical Yearly Intelligence
 *
 * Historical yearly data is already aggregated by:
 *
 *     year + trade_flow
 *
 * This method intentionally returns the SAME payload structure
 * used by the legacy buildOverview() method so the React frontend
 * does not need a separate historical-yearly contract.
 */
protected function buildHistoricalYearlyOverview(
    array $yearlyTrend,
    int $currentYear,
    int $comparisonYear
): array {

    /*
    |--------------------------------------------------------------------------
    | Resolve Current Year
    |--------------------------------------------------------------------------
    */

    $current = null;

    foreach ($yearlyTrend as $yearData) {

        if (
            (int) ($yearData['year'] ?? 0)
            === $currentYear
        ) {
            $current = $yearData;
            break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve Comparison Year
    |--------------------------------------------------------------------------
    */

    $previous = null;

    foreach ($yearlyTrend as $yearData) {

        if (
            (int) ($yearData['year'] ?? 0)
            === $comparisonYear
        ) {
            $previous = $yearData;
            break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Safe Defaults
    |--------------------------------------------------------------------------
    */

    $current ??= [
        'year' => $currentYear,

        'import' => [
            'trade_value' => 0,
            'trade_volume' => 0,
        ],

        'export' => [
            'trade_value' => 0,
            'trade_volume' => 0,
        ],
    ];

    $previous ??= [
        'year' => $comparisonYear,

        'import' => [
            'trade_value' => 0,
            'trade_volume' => 0,
        ],

        'export' => [
            'trade_value' => 0,
            'trade_volume' => 0,
        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | Import Trade Value
    |--------------------------------------------------------------------------
    */

    $currentImport =
        (float) (
            $current['import']['trade_value']
            ?? 0
        );

    $previousImport =
        (float) (
            $previous['import']['trade_value']
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | Export Trade Value
    |--------------------------------------------------------------------------
    */

    $currentExport =
        (float) (
            $current['export']['trade_value']
            ?? 0
        );

    $previousExport =
        (float) (
            $previous['export']['trade_value']
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | Import Physical Volume — KG
    |--------------------------------------------------------------------------
    */

    $currentImportKg =
        (float) (
            $current['import']['trade_volume']
            ?? 0
        );

    $previousImportKg =
        (float) (
            $previous['import']['trade_volume']
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | Export Physical Volume — KG
    |--------------------------------------------------------------------------
    */

    $currentExportKg =
        (float) (
            $current['export']['trade_volume']
            ?? 0
        );

    $previousExportKg =
        (float) (
            $previous['export']['trade_volume']
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | Growth Percentage
    |--------------------------------------------------------------------------
    */

    $growthPercent =
        static function (
            float $currentValue,
            float $previousValue
        ): float {

            if ($previousValue == 0.0) {
                return 0.0;
            }

            return round(
                (
                    (
                        $currentValue
                        - $previousValue
                    )
                    / $previousValue
                ) * 100,
                6
            );
        };


    /*
    |--------------------------------------------------------------------------
    | Return Legacy-Compatible Executive Overview
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | The keys below intentionally match buildOverview().
    |
    | React expects:
    |
    |     current
    |     previous
    |     growth_percent
    |     physical_volume_kg
    |     previous_physical_volume_kg
    |     physical_volume_growth_percent
    |
    */

    return [

        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        'import' => [

            'current' =>
                $currentImport,

            'previous' =>
                $previousImport,

            'growth_percent' =>
                $growthPercent(
                    $currentImport,
                    $previousImport
                ),

            'physical_volume_kg' =>
                $currentImportKg,

            'previous_physical_volume_kg' =>
                $previousImportKg,

            'physical_volume_growth_percent' =>
                $growthPercent(
                    $currentImportKg,
                    $previousImportKg
                ),
        ],


        /*
        |--------------------------------------------------------------------------
        | Export
        |--------------------------------------------------------------------------
        */

        'export' => [

            'current' =>
                $currentExport,

            'previous' =>
                $previousExport,

            'growth_percent' =>
                $growthPercent(
                    $currentExport,
                    $previousExport
                ),

            'physical_volume_kg' =>
                $currentExportKg,

            'previous_physical_volume_kg' =>
                $previousExportKg,

            'physical_volume_growth_percent' =>
                $growthPercent(
                    $currentExportKg,
                    $previousExportKg
                ),
        ],
    ];
}

    /*
    |--------------------------------------------------------------------------
    | Build One Canonical Period Dataset
    |--------------------------------------------------------------------------
    |
    | This deliberately receives a period whose current and comparison windows
    | are identical. The existing query therefore remains unchanged while each
    | invocation retains only one period's source and classified collections.
    |
    */
   

   protected function buildPeriodDataset(
    TradeReportingPeriod $period
): array {

    /*
    |--------------------------------------------------------------------------
    | 1. Resolve Database Columns
    |--------------------------------------------------------------------------
    */

    $columns = $this->resolveColumns();


    /*
    |--------------------------------------------------------------------------
    | 2. Canonical HS-8
    |--------------------------------------------------------------------------
    |
    | Canonical taxonomy remains the single authoritative source
    | for the garment sector.
    |
    */

    $sectorHsCodes =
        $this->taxonomy->hsCodesForSector(
            self::SECTOR
        );


    /*
    |--------------------------------------------------------------------------
    | 3. Safety Check
    |--------------------------------------------------------------------------
    */

    if (empty($sectorHsCodes)) {

        \Log::warning(
            'GARMENT INTELLIGENCE: No canonical HS-8 codes found.',
            [
                'sector' => self::SECTOR,
            ]
        );

        return [
            'dataset' => [],
            'yearly_trend' => [],
            'major_export_destinations' => [],
            'major_import_sources' => [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 4. Detect Historical Yearly Mode
    |--------------------------------------------------------------------------
    |
    | Historical years 2019–2024 use the new yearly aggregation path.
    |
    | Current / YTD / Monthly remain on the existing production path.
    |
    */

    $isHistoricalYearly =
        $period->isFullYear()
        && $period->publicThroughYear >= 2019
        && $period->publicThroughYear <= 2024;


    /*
    |--------------------------------------------------------------------------
    | 5. Historical Yearly Path
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | This branch will contain ONLY the new yearly SQL aggregation.
    |
    | It must return before the existing monthly/detail query is built.
    |
    */

    if ($isHistoricalYearly) {

        return $this->buildHistoricalYearlyDataset(
            $period,
            $columns,
            $sectorHsCodes
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 6. Existing Current / YTD / Monthly Path
    |--------------------------------------------------------------------------
    |
    | Everything below this point remains the existing working logic.
    |
    */

    $query = DB::table(
        'trade_statistics'
    );

    $query->whereIn(
        $columns['hs_code'],
        $sectorHsCodes
    );

        $query = DB::table('trade_statistics');

        $query->whereIn(
            $columns['hs_code'],
            $sectorHsCodes
        );

        $currentMonthStart = $period->isMonthly()
            ? $period->publicThroughMonth
            : 1;

        $currentMonthEnd = $period->isFullYear()
            ? 12
            : $period->publicThroughMonth;

        $comparisonMonthStart = $period->isMonthly()
            ? $period->comparisonThroughMonth
            : 1;

        $comparisonMonthEnd = $period->isFullYear()
            ? 12
            : $period->comparisonThroughMonth;

        $query->where(
            function ($q) use (
                $columns,
                $period,
                $currentMonthStart,
                $currentMonthEnd,
                $comparisonMonthStart,
                $comparisonMonthEnd
            ) {
                $q->where(
                    function ($currentQuery) use (
                        $columns,
                        $period,
                        $currentMonthStart,
                        $currentMonthEnd
                    ) {
                        $currentQuery
                            ->where(
                                $columns['year'],
                                $period->publicThroughYear
                            )
                            ->whereBetween(
                                $columns['month'],
                                [
                                    $currentMonthStart,
                                    $currentMonthEnd,
                                ]
                            );
                    }
                );

                $q->orWhere(
                    function ($comparisonQuery) use (
                        $columns,
                        $period,
                        $comparisonMonthStart,
                        $comparisonMonthEnd
                    ) {
                        $comparisonQuery
                            ->where(
                                $columns['year'],
                                $period->comparisonYear
                            )
                            ->whereBetween(
                                $columns['month'],
                                [
                                    $comparisonMonthStart,
                                    $comparisonMonthEnd,
                                ]
                            );
                    }
                );
            }
        );

        $uniqueHsCodes = (clone $query)
            ->select('hs_code')
            ->distinct()
            ->pluck('hs_code')
            ->map(
                fn ($hsCode) => trim((string) $hsCode)
            )
            ->filter(
                fn ($hsCode) => preg_match('/^\d{8}$/', $hsCode)
            )
            ->unique()
            ->values();

        $factorMap = $this->conversionService->resolveFactors(
            $uniqueHsCodes
        );

        $classificationMap = $this->taxonomy->classifyMany(
            $uniqueHsCodes->all()
        );

        $dataset = [];

/*
|--------------------------------------------------------------------------
| HISTORICAL YEARLY AGGREGATE — PREPARED, NOT ACTIVE YET
|--------------------------------------------------------------------------
|
| This query is prepared for historical yearly intelligence.
| It is intentionally NOT replacing the existing aggregate query yet.
|
*/

$historicalYearlyQuery = (clone $query)
    ->select([
        $columns['year'] . ' as trade_year',
        $columns['hs_code'] . ' as hs_code',
        $columns['hs_description'] . ' as hs_description',
        $columns['flow'] . ' as trade_flow',
        $columns['country'] . ' as trade_country',
    ])
    ->selectRaw(
        'SUM(' .
        $columns['trade_value'] .
        ') AS trade_value'
    )
    ->selectRaw(
        'SUM(' .
        $columns['trade_volume'] .
        ') AS trade_volume'
    )
    ->groupBy([
        $columns['year'],
        $columns['hs_code'],
        $columns['hs_description'],
        $columns['flow'],
        $columns['country'],
    ])
    ->orderBy($columns['year'])
    ->orderBy($columns['hs_code'])
    ->orderBy($columns['flow'])
    ->orderBy($columns['country']);

/*
|--------------------------------------------------------------------------
| HISTORICAL YEARLY INTELLIGENCE — TEST ONLY
|--------------------------------------------------------------------------
|
| Purpose:
| - Historical years: 2019–2024
| - Aggregate directly in SQL
| - 12 months are summed by SQL
| - Yearly trend: year + trade_flow
| - Country intelligence: year + trade_flow + country
|
| IMPORTANT:
| - TEST ONLY
| - Does NOT replace the existing aggregate query
| - Does NOT modify current/YTD processing
|
*/

/*
|--------------------------------------------------------------------------
| Canonical HS-8
|--------------------------------------------------------------------------
*/

$historicalHsCodes =
    $this->taxonomy->hsCodesForSector(
        self::SECTOR
    );


/*
|--------------------------------------------------------------------------
| Base Historical Query
|--------------------------------------------------------------------------
|
| Only 2019–2024.
| Month is intentionally NOT selected or grouped.
|
| The database therefore aggregates the complete
| January–December period directly.
|
*/

$historicalBaseQuery =
    DB::table('trade_statistics')
        ->whereIn(
            $columns['hs_code'],
            $historicalHsCodes
        )
        ->whereBetween(
            $columns['year'],
            [2019, 2024]
        )
        ->whereBetween(
            $columns['month'],
            [1, 12]
        );

/*
|--------------------------------------------------------------------------
| TEST 1 — HISTORICAL YEARLY SUMMARY
|--------------------------------------------------------------------------
|
| One row per:
|
|     year + trade_flow
|
| No:
| - month
| - HS-8
| - country
|
*/

$historicalYearlySummaryQuery =
    (clone $historicalBaseQuery)
        ->select([
            $columns['year'] .
                ' as trade_year',

            $columns['flow'] .
                ' as trade_flow',
        ])
        ->selectRaw(
            'SUM(' .
            $columns['trade_value'] .
            ') AS trade_value'
        )
        ->selectRaw(
            'SUM(' .
            $columns['trade_volume'] .
            ') AS trade_volume'
        )
        ->groupBy([
            $columns['year'],
            $columns['flow'],
        ])
        ->orderBy(
            $columns['year']
        )
        ->orderBy(
            $columns['flow']
        );

/*
|--------------------------------------------------------------------------
| TEST 2 — HISTORICAL YEARLY BY COUNTRY
|--------------------------------------------------------------------------
|
| One row per:
|
|     year + trade_flow + country
|
| This is used to build:
|
|     major_export_destinations
|     major_import_sources
|
| No month dimension.
| No HS-8 dimension.
|
*/

$historicalYearlyCountryQuery =
    (clone $historicalBaseQuery)
        ->select([
            $columns['year'] .
                ' as trade_year',

            $columns['flow'] .
                ' as trade_flow',

            $columns['country'] .
                ' as trade_country',
        ])
        ->selectRaw(
            'SUM(' .
            $columns['trade_value'] .
            ') AS trade_value'
        )
        ->selectRaw(
            'SUM(' .
            $columns['trade_volume'] .
            ') AS trade_volume'
        )
        ->groupBy([
            $columns['year'],
            $columns['flow'],
            $columns['country'],
        ])
        ->orderBy(
            $columns['year']
        )
        ->orderBy(
            $columns['flow']
        )
        ->orderBy(
            $columns['country']
        );

/*
|--------------------------------------------------------------------------
| Execute TEST Queries
|--------------------------------------------------------------------------
|
| These are still isolated from the production dataset.
|
*/


$historicalYearlySummary =
    $historicalYearlySummaryQuery
        ->get();

$historicalYearlyCountry =
    $historicalYearlyCountryQuery
        ->get();

/*
|--------------------------------------------------------------------------
| Build Yearly Trend
|--------------------------------------------------------------------------
*/

$historicalYearlyTrend = [];

foreach (
    $historicalYearlySummary
    as $row
) {

    $year =
        (int) $row->trade_year;

    $flow =
        strtolower(
            trim(
                (string) $row->trade_flow
            )
        );

    if (!isset(
        $historicalYearlyTrend[$year]
    )) {

        $historicalYearlyTrend[$year] = [

            'year' =>
                $year,

            'import' => [
                'trade_value' =>
                    0,

                'trade_volume' =>
                    0,
            ],

            'export' => [
                'trade_value' =>
                    0,

                'trade_volume' =>
                    0,
            ],
        ];
    }

    if (
        !in_array(
            $flow,
            ['export', 'import'],
            true
        )
    ) {
        continue;
    }

    $historicalYearlyTrend[$year][$flow] = [

        'trade_value' =>
            (float) $row->trade_value,

        'trade_volume' =>
            (float) $row->trade_volume,
    ];
}

$historicalYearlyTrend =
    array_values(
        $historicalYearlyTrend
    );

/*
|--------------------------------------------------------------------------
| 6. Build Country Aggregation
|--------------------------------------------------------------------------
|
| One row per:
|
|     year + trade_flow + country
|
| Country identity is resolved through CountryResolver so the
| historical intelligence contains:
|
| - country_name_en
| - country_name_id
| - country_code
| - iso3
| - flag_emoji
|
*/

$majorExportDestinations = [];

$majorImportSources = [];

foreach (
    $historicalYearlyCountry as $row
) {

    $year =
        (int) $row->trade_year;

    $flow =
        strtolower(
            trim(
                (string) $row->trade_flow
            )
        );

    $countryRaw =
        trim(
            (string) $row->trade_country
        );

    if ($countryRaw === '') {
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Canonical Country
    |--------------------------------------------------------------------------
    */

    $resolvedCountry =
        $this->countryResolver->resolve(
            $countryRaw,
            'KEMENDAG'
        );

    /*
    |--------------------------------------------------------------------------
    | Country Intelligence Item
    |--------------------------------------------------------------------------
    */

    $item = [

        'country' =>
            $resolvedCountry?->country_name_id
            ?? $countryRaw,

        'country_name_en' =>
            $resolvedCountry?->country_name_en
            ?? $countryRaw,

        'country_name_id' =>
            $resolvedCountry?->country_name_id
            ?? $countryRaw,

        'country_code' =>
            $resolvedCountry?->country_code,

        'iso3' =>
            $resolvedCountry?->iso3,

        'flag_emoji' =>
            $resolvedCountry?->flag_emoji,

        'trade_value' =>
            (float) $row->trade_value,

        'trade_volume' =>
            (float) $row->trade_volume,
    ];

    /*
    |--------------------------------------------------------------------------
    | Separate Import / Export
    |--------------------------------------------------------------------------
    */

    if ($flow === 'export') {

        $majorExportDestinations[$year][] =
            $item;

    } elseif ($flow === 'import') {

        $majorImportSources[$year][] =
            $item;
    }
}

/*
|--------------------------------------------------------------------------
| 7. Sort Country Results
|--------------------------------------------------------------------------
|
| Highest trade value first.
|
*/

foreach (
    $majorExportDestinations as $year => &$countries
) {

    usort(
        $countries,
        static function (
            $a,
            $b
        ) {

            return
                $b['trade_value']
                <=>
                $a['trade_value'];
        }
    );
}

unset($countries);


foreach (
    $majorImportSources as $year => &$countries
) {

    usort(
        $countries,
        static function (
            $a,
            $b
        ) {

            return
                $b['trade_value']
                <=>
                $a['trade_value'];
        }
    );
}

unset($countries);


/*
|--------------------------------------------------------------------------
| 8. Limit Top 10 Countries
|--------------------------------------------------------------------------
|
| The yearly country intelligence is intended for the
| executive overview and market intelligence panels.
|
*/

foreach (
    $majorExportDestinations as $year => &$countries
) {

    $countries =
        array_slice(
            $countries,
            0,
            10
        );
}

unset($countries);


foreach (
    $majorImportSources as $year => &$countries
) {

    $countries =
        array_slice(
            $countries,
            0,
            10
        );
}

unset($countries);

/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
|
| Verify:
|
|     Yearly Summary
|             ==
|     SUM(all countries)
|
| for every:
|
|     year + trade_flow
|
*/

$historicalValidation = [];

foreach (
    $historicalYearlyTrend
    as $yearData
) {

    $year =
        (int) $yearData['year'];

    foreach (
    ['export', 'import']
    as $flow
) {

    $expectedValue =
        (float) (
            $yearData[$flow]['trade_value']
            ?? 0
        );

    $expectedVolume =
        (float) (
            $yearData[$flow]['trade_volume']
            ?? 0
        );

    $countryData =
        $flow === 'export'
            ? (
                $majorExportDestinations[
                    $year
                ] ?? []
            )
            : (
                $majorImportSources[
                    $year
                ] ?? []
            );

    $countryValue =
        collect($countryData)
            ->sum(
                fn ($country) =>
                    (float) (
                        $country['trade_value']
                        ?? 0
                    )
            );

    $countryVolume =
        collect($countryData)
            ->sum(
                fn ($country) =>
                    (float) (
                        $country['trade_volume']
                        ?? 0
                    )
            );

    /*
    |--------------------------------------------------------------------------
    | Floating-Point Tolerance
    |--------------------------------------------------------------------------
    |
    | The yearly SQL aggregate and country aggregation can produce
    | microscopic floating-point differences.
    |
    | These differences are numerical precision artifacts and do not
    | represent a real discrepancy in the trade data.
    |
    */

    $valueTolerance = max(
        0.00001,
        abs($expectedValue) *
            0.000000000001
    );

    $volumeTolerance = max(
        0.00001,
        abs($expectedVolume) *
            0.000000000001
    );

    $valueDifference =
        $expectedValue -
        $countryValue;

    $volumeDifference =
        $expectedVolume -
        $countryVolume;

    $valid =
        abs($valueDifference) <=
            $valueTolerance
        &&
        abs($volumeDifference) <=
            $volumeTolerance;

    $historicalValidation[] = [

        'year' =>
            $year,

        'trade_flow' =>
            $flow,

        'yearly_trade_value' =>
            round(
                $expectedValue,
                6
            ),

        'country_trade_value' =>
            round(
                $countryValue,
                6
            ),

        'value_difference' =>
            round(
                $valueDifference,
                6
            ),

        'yearly_trade_volume' =>
            round(
                $expectedVolume,
                6
            ),

        'country_trade_volume' =>
            round(
                $countryVolume,
                6
            ),

        'volume_difference' =>
            round(
                $volumeDifference,
                6
            ),

        'valid' =>
            $valid,
    ];
}
}


    $aggregateQuery =
    (clone $query)
        ->select([
            $columns['year'] . ' as trade_year',
            $columns['month'] . ' as trade_month',
            $columns['hs_code'] . ' as hs_code',
            $columns['hs_description'] . ' as hs_description',
            $columns['flow'] . ' as trade_flow',
            $columns['country'] . ' as trade_country',
        ])
        ->selectRaw(
            'SUM(' .
            $columns['trade_value'] .
            ') AS trade_value'
        )
        ->selectRaw(
            'SUM(' .
            $columns['trade_volume'] .
            ') AS trade_volume'
        )
        ->groupBy([
            $columns['year'],
            $columns['month'],
            $columns['hs_code'],
            $columns['hs_description'],
            $columns['flow'],
            $columns['country'],
        ])
        ->orderBy($columns['year'])
        ->orderBy($columns['month'])
        ->orderBy($columns['hs_code'])
        ->orderBy($columns['flow'])
        ->orderBy($columns['country']);

    $aggregateQuery->chunk(
        500,
        function ($rows) use (
            &$dataset,
            $factorMap,
            $classificationMap
        ) {
                foreach ($rows as $row) {
                    $hsCode = trim((string) $row->hs_code);

                    $classification = $classificationMap[$hsCode] ?? null;

                    if (
                        $classification === null
                        || ($classification['sector'] ?? null) !== self::SECTOR
                    ) {
                        continue;
                    }

                    $volumeKg = $this->toFloat($row->trade_volume);

                    $resolved = $factorMap[$hsCode] ?? [
                        'status' => 'NOT_AVAILABLE',
                        'resolution_code' => 'NO_ACTIVE_FACTOR',
                        'hs_code' => $hsCode,
                        'methodology' => 'KG_PER_PCS',
                        'factor_id' => null,
                        'factor' => null,
                        'evidence_type' => null,
                        'weight_unit' => null,
                        'evidence_count' => null,
                        'total_sample_size' => null,
                        'calculation_method' => null,
                    ];

                    $factor = isset($resolved['factor']) && $resolved['factor'] !== null
                        ? (float) $resolved['factor']
                        : null;

                    $isConvertible = ($resolved['resolution_code'] ?? null) === 'ACTIVE_FACTOR_FOUND'
                        && $factor !== null
                        && $factor > 0;

                    $derivedPcs = $isConvertible
                        ? round($volumeKg / $factor, 6)
                        : null;

                    $conversionStatus = $isConvertible
                        ? 'CONVERTED'
                        : 'NOT_CONVERTIBLE';

                    $conversionCode = $isConvertible
                        ? 'KG_TO_PCS_CONVERTED'
                        : 'NO_ACTIVE_FACTOR';

                    $country = $this->countryResolver->resolve(
                        $row->trade_country,
                        'KEMENDAG'
                    );


                    
                    $dataset[] = [
                        'year' => (int) $row->trade_year,
                        'month' => (int) $row->trade_month,
                        'hs_code' => $hsCode,
                        'hs4' => substr($hsCode, 0, 4),
                        'chapter' => substr($hsCode, 0, 2),
                        'description' => $row->hs_description,
                        'subsector' => $classification['subsector'],
                        'label_en' => $classification['label_en'],
                        'label_id' => $classification['label_id'],
                        'flow' => $this->normalizeFlow($row->trade_flow),
                        'country' => $row->trade_country,
                        'country_id' => $country?->id,
                        'country_code' => $country?->country_code,
                        'iso3' => $country?->iso3,
                        'country_name_en' => $country?->country_name_en,
                        'country_name_id' => $country?->country_name_id,
                        'flag_emoji' => $country?->flag_emoji,
                        'value' => $this->toFloat($row->trade_value),
                        'volume' => $volumeKg,
                        'volume_unit' => 'KG',
                        'derived_pcs' => $derivedPcs,
                        'conversion_status' => $conversionStatus,
                        'conversion_code' => $conversionCode,
                        'conversion_factor_id' => $resolved['factor_id'] ?? null,
                        'conversion_factor' => $factor,
                        'conversion_methodology' => $resolved['methodology'] ?? 'KG_PER_PCS',
                        'conversion_factor_status' => $resolved['status'] ?? null,
                        'conversion_evidence_count' => $resolved['evidence_count'] ?? null,
                        'conversion_total_sample_size' => $resolved['total_sample_size'] ?? null,
                        'conversion_calculation_method' => $resolved['calculation_method'] ?? null,
                    ];
                }
            }
        );

        unset(
            $uniqueHsCodes,
            $factorMap,
            $classificationMap
        );

        $current = collect($dataset)
            ->filter(
                fn ($row) =>
                    $row['year'] === $period->publicThroughYear
                    && $row['month'] >= 1
                    && $row['month'] <= $period->publicThroughMonth
            )
            ->values();

        $previous = collect($dataset)
            ->filter(
                fn ($row) =>
                    $row['year'] === $period->comparisonYear
                    && $row['month'] >= 1
                    && $row['month'] <= $period->comparisonThroughMonth
            )
            ->values();

            
        return [
            'meta' => [
                'sector' => self::SECTOR,
                'snapshot_key' => self::CACHE_KEY,
                'snapshot_type' => self::SNAPSHOT_TYPE,
                'period' => $period->periodLabel(),
                'period_label_en' => $period->periodLabel(),
                'period_label_id' => $period->periodLabel(),
                'display_period_label_en' => $period->displayPeriodLabelEn(),
                'display_period_label_id' => $period->displayPeriodLabelId(),
                'comparison_period_label_en' => $period->comparisonPeriodLabelEn(),
                'comparison_period_label_id' => $period->comparisonPeriodLabelId(),
                'current_period' => $period->currentPeriod(),
                'comparison_period' => $period->comparisonPeriod(),
                'current_year' => $period->publicThroughYear,
                'comparison_year' => $period->comparisonYear,
                'through_month' => $period->publicThroughMonth,
                'comparison_through_month' => $period->comparisonThroughMonth,
                'buffer_period' => $period->bufferPeriod(),
                'buffer_status' => $period->status,
                'data_status' => $this->dataStatus($period),
                'generated_at' => now(),
                'record_count' => count($dataset),
                'snapshot_period_key' => $period->snapshotKey(),
                'hs_codes' => [],
            ],
            'current' => $current->all(),
            'previous' => $previous->all(),
            'overview' => $this->buildOverview($current, $previous),
            'by_subsector' => $this->buildSubsectorPerformance($current, $previous),
            'by_flow' => $this->buildFlowPerformance($current, $previous),
            'top_import_products' => $this->buildTopProducts($current, 'import'),
            'top_export_products' => $this->buildTopProducts($current, 'export'),
            'top_import_origins' => $this->buildTopCountries($current, 'import'),
            'top_export_destinations' => $this->buildTopCountries($current, 'export'),
            'import_market_share' => $this->buildCountryMarketShare($current, 'import'),
            'export_market_share' => $this->buildCountryMarketShare($current, 'export'),
            'monthly_trend' => $this->buildMonthlyTrend(collect($dataset)),
            'yearly_trend' => $this->buildYearlyTrend(collect($dataset)),
            'hs8_products' => $this->buildHs8Products($current),
        ];
    }


/**
 * =========================================================================
 * HISTORICAL YEARLY DATASET
 * =========================================================================
 *
 * Historical intelligence for completed years.
 *
 * Scope:
 * - 2019–2024
 * - Full-year only
 * - Canonical garment HS-8
 *
 * Aggregation:
 * 1. Year + Trade Flow
 *    -> yearly_trend
 *
 * 2. Year + Trade Flow + Country
 *    -> major_export_destinations
 *    -> major_import_sources
 *
 * IMPORTANT:
 * - No month dimension in the aggregation result.
 * - No HS-8 dimension in the final summary.
 * - Existing current / YTD / monthly logic remains untouched.
 *
 * @param  TradeReportingPeriod $period
 * @param  array $columns
 * @param  array $sectorHsCodes
 * @return array
 */
protected function buildHistoricalYearlyDataset(
    TradeReportingPeriod $period,
    array $columns,
    array $sectorHsCodes
): array {

    /*
    |--------------------------------------------------------------------------
    | 1. Determine Historical Year Range
    |--------------------------------------------------------------------------
    */

    $endYear =
        (int) $period->publicThroughYear;

    $startYear = 2019;


     /*
    |--------------------------------------------------------------------------
    | 2. Canonical Historical Classification
    |--------------------------------------------------------------------------
    |
    | Use the same canonical HS-8 taxonomy as the parent dataset.
    |
    */

    $classificationMap =
        $this->taxonomy->classifyMany(
            $sectorHsCodes
        );


    
    /*
    |--------------------------------------------------------------------------
    | 2. Historical Yearly Base Query
    |--------------------------------------------------------------------------
    |
    | Historical intelligence uses the complete January–December period.
    |
    | IMPORTANT:
    | - No month dimension in SELECT.
    | - No month dimension in GROUP BY.
    | - SQL performs the complete yearly aggregation.
    |
    */

    $historicalBaseQuery =
        DB::table('trade_statistics')
            ->whereIn(
                $columns['hs_code'],
                $sectorHsCodes
            )
            ->whereBetween(
                $columns['year'],
                [
                    $startYear,
                    $endYear,
                ]
            )
            ->whereBetween(
                $columns['month'],
                [1, 12]
            );


    /*
    |--------------------------------------------------------------------------
    | 3. Historical Yearly Summary
    |--------------------------------------------------------------------------
    |
    | One row per:
    |
    |     year + trade_flow
    |
    | Source for:
    |
    |     yearly_trend
    |
    */

    $historicalYearlySummary =
        (clone $historicalBaseQuery)
            ->select([
                $columns['year']
                    . ' as trade_year',

                $columns['flow']
                    . ' as trade_flow',
            ])
            ->selectRaw(
                'SUM(' .
                $columns['trade_value'] .
                ') AS trade_value'
            )
            ->selectRaw(
                'SUM(' .
                $columns['trade_volume'] .
                ') AS trade_volume'
            )
            ->groupBy([
                $columns['year'],
                $columns['flow'],
            ])
            ->orderBy(
                $columns['year']
            )
            ->orderBy(
                $columns['flow']
            )
            ->get();


    /*
    |--------------------------------------------------------------------------
    | 4. Historical Yearly Trend
    |--------------------------------------------------------------------------
    */

    $historicalYearlyTrend = [];

    foreach (
        $historicalYearlySummary as $row
    ) {

        $year =
            (int) $row->trade_year;

        $flow =
            strtolower(
                trim(
                    (string) $row->trade_flow
                )
            );

        if (!isset(
            $historicalYearlyTrend[$year]
        )) {

            $historicalYearlyTrend[$year] = [

                'year' =>
                    $year,

                'import' => [
                    'trade_value' =>
                        0,

                    'trade_volume' =>
                        0,
                ],

                'export' => [
                    'trade_value' =>
                        0,

                    'trade_volume' =>
                        0,
                ],
            ];
        }

        if (
            !in_array(
                $flow,
                [
                    'export',
                    'import',
                ],
                true
            )
        ) {
            continue;
        }

        $historicalYearlyTrend[$year][$flow] = [

            'trade_value' =>
                (float) $row->trade_value,

            'trade_volume' =>
                (float) $row->trade_volume,
        ];
    }

    $historicalYearlyTrend =
        array_values(
            $historicalYearlyTrend
        );


    /*
    |--------------------------------------------------------------------------
    | 5. Historical Yearly Country Query
    |--------------------------------------------------------------------------
    |
    | One row per:
    |
    |     year + trade_flow + country
    |
    | Source for:
    |
    |     major_export_destinations
    |     major_import_sources
    |
    */

    $historicalYearlyCountry =
        (clone $historicalBaseQuery)
            ->select([
                $columns['year']
                    . ' as trade_year',

                $columns['flow']
                    . ' as trade_flow',

                $columns['country']
                    . ' as trade_country',
            ])
            ->selectRaw(
                'SUM(' .
                $columns['trade_value'] .
                ') AS trade_value'
            )
            ->selectRaw(
                'SUM(' .
                $columns['trade_volume'] .
                ') AS trade_volume'
            )
            ->groupBy([
                $columns['year'],
                $columns['flow'],
                $columns['country'],
            ])
            ->orderBy(
                $columns['year']
            )
            ->orderBy(
                $columns['flow']
            )
            ->orderBy(
                $columns['country']
            )
            ->get();

    /*
|--------------------------------------------------------------------------
| 6. Build Country Aggregation
|--------------------------------------------------------------------------
|
| Resolve canonical country identity before the data is exposed
| to the historical intelligence layer.
|
*/

$majorExportDestinations = [];

$majorImportSources = [];

foreach (
    $historicalYearlyCountry as $row
) {

    $year =
        (int) $row->trade_year;

    $flow =
        strtolower(
            trim(
                (string) $row->trade_flow
            )
        );

    $countryRaw =
        trim(
            (string) $row->trade_country
        );

    if ($countryRaw === '') {
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Country
    |--------------------------------------------------------------------------
    */

    $resolvedCountry =
        $this->countryResolver->resolve(
            $countryRaw,
            'KEMENDAG'
        );

    /*
    |--------------------------------------------------------------------------
    | Country Intelligence Item
    |--------------------------------------------------------------------------
    */

    $item = [

        'country' =>
            $resolvedCountry?->country_name_id
            ?? $countryRaw,

        'country_name_en' =>
            $resolvedCountry?->country_name_en
            ?? $countryRaw,

        'country_name_id' =>
            $resolvedCountry?->country_name_id
            ?? $countryRaw,

        'country_code' =>
            $resolvedCountry?->country_code,

        'iso3' =>
            $resolvedCountry?->iso3,

        'flag_emoji' =>
            $resolvedCountry?->flag_emoji,

        'trade_value' =>
            (float) $row->trade_value,

        'trade_volume' =>
            (float) $row->trade_volume,
    ];

    /*
    |--------------------------------------------------------------------------
    | Store by Trade Flow
    |--------------------------------------------------------------------------
    */

    if ($flow === 'export') {

        $majorExportDestinations[$year][] =
            $item;

    } elseif ($flow === 'import') {

        $majorImportSources[$year][] =
            $item;
    }
}        

/*
|--------------------------------------------------------------------------
| 7. Historical Yearly Subsector Query
|--------------------------------------------------------------------------
|
| One row per:
|
|     year + trade_flow + HS-8
|
| HS-8 is subsequently resolved through the canonical
| classification map into the garment subsector.
|
*/

$historicalYearlySubsector =
    (clone $historicalBaseQuery)
        ->select([
            $columns['year']
                . ' as trade_year',

            $columns['flow']
                . ' as trade_flow',

            $columns['hs_code']
                . ' as hs_code',
        ])
        ->selectRaw(
            'SUM(' .
            $columns['trade_value'] .
            ') AS trade_value'
        )
        ->selectRaw(
            'SUM(' .
            $columns['trade_volume'] .
            ') AS trade_volume'
        )
        ->groupBy([
            $columns['year'],
            $columns['flow'],
            $columns['hs_code'],
        ])
        ->get();


        /*
|--------------------------------------------------------------------------
| Build Historical Subsector Aggregation
|--------------------------------------------------------------------------
*/

$historicalSubsector = [];

foreach (
    $historicalYearlySubsector as $row
) {

    $year =
        (int) $row->trade_year;

    $flow =
        strtolower(
            trim(
                (string) $row->trade_flow
            )
        );

    if (
        !in_array(
            $flow,
            [
                'import',
                'export',
            ],
            true
        )
    ) {
        continue;
    }

    $hsCode =
        trim(
            (string) $row->hs_code
        );

    $classification =
        $classificationMap[$hsCode]
        ?? null;

    if ($classification === null) {
        continue;
    }

    $subsector =
        $classification['subsector']
        ?? null;

    if (
        $subsector === null ||
        $subsector === ''
    ) {
        continue;
    }

    if (
        !isset(
            $historicalSubsector[$year][$subsector]
        )
    ) {

        $historicalSubsector[$year][$subsector] = [

            'subsector' =>
                $subsector,

            'label_en' =>
                $classification['label_en']
                ?? null,

            'label_id' =>
                $classification['label_id']
                ?? null,

            'import_value' =>
                0,

            'export_value' =>
                0,

            'import_volume' =>
                0,

            'export_volume' =>
                0,
        ];
    }

    $historicalSubsector[$year][$subsector][
        $flow . '_value'
    ] +=
        (float) $row->trade_value;

    $historicalSubsector[$year][$subsector][
        $flow . '_volume'
    ] +=
        (float) $row->trade_volume;
}

/*
|--------------------------------------------------------------------------
| Build Current Historical Subsector Performance
|--------------------------------------------------------------------------
*/

$currentYear =
    (int) $period->publicThroughYear;

$comparisonYear =
    (int) $period->comparisonYear;

$currentSubsectors =
    $historicalSubsector[$currentYear]
    ?? [];

$previousSubsectors =
    $historicalSubsector[$comparisonYear]
    ?? [];

$historicalBySubsector = [];

$subsectorKeys =
    array_unique(
        array_merge(
            array_keys($currentSubsectors),
            array_keys($previousSubsectors)
        )
    );

foreach (
    $subsectorKeys as $subsector
) {

    $current =
        $currentSubsectors[$subsector]
        ?? null;

    $previous =
        $previousSubsectors[$subsector]
        ?? null;

    $currentImport =
        (float) (
            $current['import_value']
            ?? 0
        );

    $previousImport =
        (float) (
            $previous['import_value']
            ?? 0
        );

    $currentExport =
        (float) (
            $current['export_value']
            ?? 0
        );

    $previousExport =
        (float) (
            $previous['export_value']
            ?? 0
        );

    $historicalBySubsector[] = [

        'subsector' =>
            $subsector,

        'label_en' =>
            $current['label_en']
            ?? $previous['label_en']
            ?? null,

        'label_id' =>
            $current['label_id']
            ?? $previous['label_id']
            ?? null,

        'import_value' =>
            $currentImport,

        'export_value' =>
            $currentExport,

        'import_previous_value' =>
            $previousImport,

        'export_previous_value' =>
            $previousExport,

        'import_growth_percent' =>
            $previousImport != 0
                ? (
                    (
                        $currentImport -
                        $previousImport
                    )
                    / $previousImport
                ) * 100
                : 0,

        'export_growth_percent' =>
            $previousExport != 0
                ? (
                    (
                        $currentExport -
                        $previousExport
                    )
                    / $previousExport
                ) * 100
                : 0,
    ];
}


    /*
    |--------------------------------------------------------------------------
    | 4. Execute Historical Yearly Queries
    |--------------------------------------------------------------------------
    |
    | The database has already aggregated:
    |
    |   - year
    |   - trade flow
    |   - country
    |
    | Month and HS-8 are intentionally not part of these result sets.
    |
    */

    // $historicalYearlySummary =
    //     $historicalYearlySummaryQuery
    //         ->get();

    // $historicalYearlyCountry =
    //     $historicalYearlyCountryQuery
    //         ->get();


    /*
    |--------------------------------------------------------------------------
    | 5. Build Yearly Trend
    |--------------------------------------------------------------------------
    |
    | Structure:
    |
    | [
    |     2019 => [
    |         'year' => 2019,
    |         'import' => [
    |             'trade_value' => ...,
    |             'trade_volume' => ...,
    |         ],
    |         'export' => [
    |             'trade_value' => ...,
    |             'trade_volume' => ...,
    |         ],
    |     ],
    | ]
    |
    */

    $historicalYearlyTrend = [];

    foreach (
        $historicalYearlySummary
        as $row
    ) {

        $year =
            (int) $row->trade_year;

        $flow =
            strtolower(
                trim(
                    (string) $row->trade_flow
                )
            );

        /*
        |--------------------------------------------------------------------------
        | Ignore unexpected flow values
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $flow,
                [
                    'export',
                    'import',
                ],
                true
            )
        ) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Initialize Year
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
                $historicalYearlyTrend[$year]
            )
        ) {

            $historicalYearlyTrend[$year] = [

                'year' =>
                    $year,

                'import' => [

                    'trade_value' =>
                        0,

                    'trade_volume' =>
                        0,
                ],

                'export' => [

                    'trade_value' =>
                        0,

                    'trade_volume' =>
                        0,
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Assign Year + Flow
        |--------------------------------------------------------------------------
        */

        $historicalYearlyTrend[$year][$flow] = [

            'trade_value' =>
                (float) $row->trade_value,

            'trade_volume' =>
                (float) $row->trade_volume,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize Numeric Year Keys
    |--------------------------------------------------------------------------
    */

    ksort(
        $historicalYearlyTrend,
        SORT_NUMERIC
    );

    $historicalYearlyTrend =
        array_values(
            $historicalYearlyTrend
        );


/*
|--------------------------------------------------------------------------
| Sort Country Results
|--------------------------------------------------------------------------
|
| Highest trade value first.
|
*/

foreach (
    $majorExportDestinations as $year => &$countries
) {

    usort(
        $countries,
        static function (
            $a,
            $b
        ) {

            return
                $b['trade_value']
                <=>
                $a['trade_value'];
        }
    );
}

unset($countries);


foreach (
    $majorImportSources as $year => &$countries
) {

    usort(
        $countries,
        static function (
            $a,
            $b
        ) {

            return
                $b['trade_value']
                <=>
                $a['trade_value'];
        }
    );
}

unset($countries);


/*
|--------------------------------------------------------------------------
| Normalize Year Ordering
|--------------------------------------------------------------------------
*/

ksort(
    $majorExportDestinations,
    SORT_NUMERIC
);

ksort(
    $majorImportSources,
    SORT_NUMERIC
);

     /*
    |--------------------------------------------------------------------------
    | 8. Historical Validation
    |--------------------------------------------------------------------------
    |
    | Verify that:
    |
    |     yearly summary
    |          ==
    |     sum of all countries
    |
    | for every:
    |
    |     year + trade_flow
    |
    | This is a diagnostic/quality-control dataset.
    |
    */

    $historicalValidation = [];

    foreach (
        $historicalYearlyTrend
        as $yearData
    ) {

        $year =
            (int) $yearData['year'];

        foreach (
            [
                'export',
                'import',
            ]
            as $flow
        ) {

            $expectedValue =
                (float) (
                    $yearData[$flow]['trade_value']
                    ?? 0
                );

            $expectedVolume =
                (float) (
                    $yearData[$flow]['trade_volume']
                    ?? 0
                );

            /*
            |--------------------------------------------------------------------------
            | Select Country Dataset
            |--------------------------------------------------------------------------
            */

            $countryData =
                $flow === 'export'
                    ? (
                        $majorExportDestinations[
                            $year
                        ] ?? []
                    )
                    : (
                        $majorImportSources[
                            $year
                        ] ?? []
                    );

            /*
            |--------------------------------------------------------------------------
            | Sum Country Trade Value
            |--------------------------------------------------------------------------
            */

            $countryValue =
                collect($countryData)
                    ->sum(
                        static function (
                            $country
                        ) {

                            return (float) (
                                $country[
                                    'trade_value'
                                ] ?? 0
                            );
                        }
                    );

            /*
            |--------------------------------------------------------------------------
            | Sum Country Trade Volume
            |--------------------------------------------------------------------------
            */

            $countryVolume =
                collect($countryData)
                    ->sum(
                        static function (
                            $country
                        ) {

                            return (float) (
                                $country[
                                    'trade_volume'
                                ] ?? 0
                            );
                        }
                    );

            /*
            |--------------------------------------------------------------------------
            | Floating Point Tolerance
            |--------------------------------------------------------------------------
            |
            | SQL SUM() and PHP aggregation may produce extremely small
            | floating-point differences.
            |
            */

            $valueTolerance =
                max(
                    0.00001,
                    abs($expectedValue) *
                        0.000000000001
                );

            $volumeTolerance =
                max(
                    0.00001,
                    abs($expectedVolume) *
                        0.000000000001
                );

            /*
            |--------------------------------------------------------------------------
            | Differences
            |--------------------------------------------------------------------------
            */

            $valueDifference =
                $expectedValue -
                $countryValue;

            $volumeDifference =
                $expectedVolume -
                $countryVolume;

            /*
            |--------------------------------------------------------------------------
            | Validation Result
            |--------------------------------------------------------------------------
            */

            $valid =
                abs($valueDifference)
                    <= $valueTolerance
                &&
                abs($volumeDifference)
                    <= $volumeTolerance;

            $historicalValidation[] = [

                'year' =>
                    $year,

                'trade_flow' =>
                    $flow,

                'yearly_trade_value' =>
                    round(
                        $expectedValue,
                        6
                    ),

                'country_trade_value' =>
                    round(
                        $countryValue,
                        6
                    ),

                'value_difference' =>
                    round(
                        $valueDifference,
                        6
                    ),

                'yearly_trade_volume' =>
                    round(
                        $expectedVolume,
                        6
                    ),

                'country_trade_volume' =>
                    round(
                        $countryVolume,
                        6
                    ),

                'volume_difference' =>
                    round(
                        $volumeDifference,
                        6
                    ),

                'valid' =>
                    $valid,
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 9. Build Historical Dataset Metadata
    |--------------------------------------------------------------------------
    */

    $historicalRecordCount =
        $historicalYearlyCountry->count();


    /*
    |--------------------------------------------------------------------------
    | 10. Return Historical Yearly Dataset
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | This return is intentionally isolated from the existing
    | current/YTD/monthly dataset path.
    |
    | The caller buildPeriodDataset() already detects historical
    | full-year periods and returns this function directly.
    |
    */

    return [

        /*
        |--------------------------------------------------------------------------
        | Meta
        |--------------------------------------------------------------------------
        */

        'meta' => [

            'sector' =>
                self::SECTOR,

            'snapshot_key' =>
                self::CACHE_KEY,

            'snapshot_type' =>
                self::SNAPSHOT_TYPE,

            'period' =>
                $period->periodLabel(),

            'period_label_en' =>
                $period->periodLabel(),

            'period_label_id' =>
                $period->periodLabel(),

            'display_period_label_en' =>
                $period->displayPeriodLabelEn(),

            'display_period_label_id' =>
                $period->displayPeriodLabelId(),

            'comparison_period_label_en' =>
                $period->comparisonPeriodLabelEn(),

            'comparison_period_label_id' =>
                $period->comparisonPeriodLabelId(),

            'current_period' =>
                $period->currentPeriod(),

            'comparison_period' =>
                $period->comparisonPeriod(),

            'current_year' =>
                $period->publicThroughYear,

            'comparison_year' =>
                $period->comparisonYear,

            'through_month' =>
                $period->publicThroughMonth,

            'comparison_through_month' =>
                $period->comparisonThroughMonth,

            'buffer_period' =>
                $period->bufferPeriod(),

            'buffer_status' =>
                $period->status,

            'data_status' =>
                $this->dataStatus($period),

            'generated_at' =>
                now(),

            'record_count' =>
                $historicalRecordCount,

            'snapshot_period_key' =>
                $period->snapshotKey(),

            'hs_codes' =>
                $sectorHsCodes,
        ],


        /*
        |--------------------------------------------------------------------------
        | Historical Yearly Trend
        |--------------------------------------------------------------------------
        */

        'yearly_trend' =>
            $historicalYearlyTrend,


        /*
        |--------------------------------------------------------------------------
        | Major Export Destinations
        |--------------------------------------------------------------------------
        */

        'major_export_destinations' =>
            $majorExportDestinations,


        /*
        |--------------------------------------------------------------------------
        | Major Import Sources
        |--------------------------------------------------------------------------
        */

        'major_import_sources' =>
            $majorImportSources,


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        'historical_validation' =>
            $historicalValidation,


        /*
        |--------------------------------------------------------------------------
        | Dataset
        |--------------------------------------------------------------------------
        |
        | Historical yearly intelligence does not use the old monthly
        | detail dataset. Keep this explicitly empty so consumers that
        | expect the key do not receive an undefined index.
        |
        */

        'dataset' =>
            [],

        'current' =>
            [],

        'previous' =>
            [],


        /*
        |--------------------------------------------------------------------------
        | Other Historical Intelligence Placeholders
        |--------------------------------------------------------------------------
        |
        | These are intentionally empty until their historical SQL
        | aggregation is implemented separately.
        |
        */

        'overview' =>
            [],

        'by_subsector' =>
            [],

        'by_flow' =>
            [],

        'top_import_products' =>
            [],

        'top_export_products' =>
            [],

        'top_import_origins' =>
            $majorImportSources[
                $period->publicThroughYear
            ] ?? [],

        'top_export_destinations' =>
            $majorExportDestinations[
                $period->publicThroughYear
            ] ?? [],

        'import_market_share' =>
            [],

        'export_market_share' =>
            [],

        'monthly_trend' =>
            [],

        'hs8_products' =>
            [],
    ];
}


    /*
    |--------------------------------------------------------------------------
    | Persisted Period Dataset Identity and Response Assembly
    |--------------------------------------------------------------------------
    */

    protected function periodDatasetRequests(
        TradeReportingPeriod $period
    ): array {
        $current = $this->periodDatasetPeriod(
            $period->publicThroughYear,
            $period->publicThroughMonth,
            $period->mode,
        );

        $comparison = $this->periodDatasetPeriod(
            $period->comparisonYear,
            $period->comparisonThroughMonth,
            $period->mode,
        );

        return [
            $this->periodDatasetDescriptor($current) => $current,
            $this->periodDatasetDescriptor($comparison) => $comparison,
        ];
    }

    protected function periodDatasetPeriod(
        int $year,
        int $throughMonth,
        string $mode,
    ): TradeReportingPeriod {
        return TradeReportingPeriod::forSelection(
            currentYear: $year,
            currentMonth: $throughMonth,
            comparisonYear: $year,
            comparisonMonth: $throughMonth,
            mode: $mode,
        );
    }

    protected function periodDatasetDescriptor(
        TradeReportingPeriod $period
    ): string {
        $startMonth = $period->isMonthly()
            ? $period->publicThroughMonth
            : 1;

        $endMonth = $period->isFullYear()
            ? 12
            : $period->publicThroughMonth;

        return sprintf(
            '%04d-%02d..%02d:%s',
            $period->publicThroughYear,
            $startMonth,
            $endMonth,
            $period->mode,
        );
    }

    protected function hasPeriodDatasets(array $snapshot): bool
    {
        return is_array($snapshot['period_datasets'] ?? null)
            && !empty($snapshot['period_datasets']);
    }

    protected function snapshotMeta(
        TradeReportingPeriod $period,
        array $periodDatasets,
    ): array {
        return [
            'sector' => self::SECTOR,
            'snapshot_key' => self::CACHE_KEY,
            'snapshot_type' => self::SNAPSHOT_TYPE,
            'dataset_format' => 'period_datasets_v1',
            'period' => $period->periodLabel(),
            'period_label_en' => $period->periodLabel(),
            'period_label_id' => $period->periodLabel(),
            'display_period_label_en' => $period->displayPeriodLabelEn(),
            'display_period_label_id' => $period->displayPeriodLabelId(),
            'comparison_period_label_en' => $period->comparisonPeriodLabelEn(),
            'comparison_period_label_id' => $period->comparisonPeriodLabelId(),
            'current_period' => $period->currentPeriod(),
            'comparison_period' => $period->comparisonPeriod(),
            'current_year' => $period->publicThroughYear,
            'comparison_year' => $period->comparisonYear,
            'through_month' => $period->publicThroughMonth,
            'comparison_through_month' => $period->comparisonThroughMonth,
            'mode' => $period->mode,
            'buffer_period' => $period->bufferPeriod(),
            'buffer_status' => $period->status,
            'data_status' => $this->dataStatus($period),
           'generated_at' => now(),
            'record_count' => array_sum(array_map('count', $periodDatasets)),
            'snapshot_period_key' => $period->snapshotKey(),
            'period_dataset_descriptors' => array_keys($periodDatasets),
            'hs_codes' => [],
        ];
    }

 protected function assembleSnapshotForPeriod(
    array $snapshot,
    TradeReportingPeriod $period,
): array {

    $currentDescriptor = $this->periodDatasetDescriptor(
        $this->periodDatasetPeriod(
            $period->publicThroughYear,
            $period->publicThroughMonth,
            $period->mode,
        )
    );

    $previousDescriptor = $this->periodDatasetDescriptor(
        $this->periodDatasetPeriod(
            $period->comparisonYear,
            $period->comparisonThroughMonth,
            $period->mode,
        )
    );

    $datasets = $snapshot['period_datasets'];

    $current = $datasets[$currentDescriptor];
    $previous = $datasets[$previousDescriptor];

    /*
    |--------------------------------------------------------------------------
    | Detect Historical Yearly
    |--------------------------------------------------------------------------
    |
    | Historical 2019–2024 uses the dedicated yearly aggregation path.
    |
    */

    $isHistoricalYearly =
        $period->isFullYear()
        && $period->publicThroughYear >= 2019
        && $period->publicThroughYear <= 2024;


    /*
    |--------------------------------------------------------------------------
    | HISTORICAL YEARLY
    |--------------------------------------------------------------------------
    |
    | Historical yearly data already contains:
    |
    | - yearly_trend
    | - major_export_destinations
    | - major_import_sources
    |
    | Do NOT pass it through the legacy row-based builders.
    |
    | Do NOT build monthly_trend.
    |
    */

    if ($isHistoricalYearly) {

        $meta = $this->snapshotMeta(
            $period,
            $datasets
        );

        $meta['generated_at'] =
            $snapshot['meta']['generated_at']
            ?? $meta['generated_at'];

/*
|--------------------------------------------------------------------------
| Historical Yearly Overview
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Historical yearly dataset is already aggregated by:
|
|     year + trade_flow
|
| Therefore Executive Overview MUST be derived directly from
| yearly_trend and must NOT call the legacy buildOverview().
|
*/

$yearlyTrend =
    $current['yearly_trend'] ?? [];

$currentYear =
    (int) $period->publicThroughYear;

$comparisonYear =
    (int) $period->comparisonYear;


/*
|--------------------------------------------------------------------------
| Resolve Current / Comparison Year
|--------------------------------------------------------------------------
*/

$currentYearData = null;
$comparisonYearData = null;

foreach (
    $yearlyTrend as $yearData
) {

    $year =
        (int) (
            $yearData['year']
            ?? $yearData['trade_year']
            ?? 0
        );

    if ($year === $currentYear) {

        $currentYearData =
            $yearData;
    }

    if ($year === $comparisonYear) {

        $comparisonYearData =
            $yearData;
    }
}


/*
|--------------------------------------------------------------------------
| Safe Defaults
|--------------------------------------------------------------------------
*/

$currentYearData ??= [
    'year' => $currentYear,

    'import' => [
        'trade_value' => 0,
        'trade_volume' => 0,
    ],

    'export' => [
        'trade_value' => 0,
        'trade_volume' => 0,
    ],
];

$comparisonYearData ??= [
    'year' => $comparisonYear,

    'import' => [
        'trade_value' => 0,
        'trade_volume' => 0,
    ],

    'export' => [
        'trade_value' => 0,
        'trade_volume' => 0,
    ],
];


/*
|--------------------------------------------------------------------------
| Import
|--------------------------------------------------------------------------
*/

$currentImportValue =
    (float) (
        $currentYearData['import']['trade_value']
        ?? 0
    );

$previousImportValue =
    (float) (
        $comparisonYearData['import']['trade_value']
        ?? 0
    );

$currentImportVolume =
    (float) (
        $currentYearData['import']['trade_volume']
        ?? 0
    );

$previousImportVolume =
    (float) (
        $comparisonYearData['import']['trade_volume']
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| Export
|--------------------------------------------------------------------------
*/

$currentExportValue =
    (float) (
        $currentYearData['export']['trade_value']
        ?? 0
    );

$previousExportValue =
    (float) (
        $comparisonYearData['export']['trade_value']
        ?? 0
    );

$currentExportVolume =
    (float) (
        $currentYearData['export']['trade_volume']
        ?? 0
    );

$previousExportVolume =
    (float) (
        $comparisonYearData['export']['trade_volume']
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| Growth Helper
|--------------------------------------------------------------------------
*/

$calculateGrowth =
    static function (
        float $current,
        float $previous
    ): float {

        if ($previous == 0.0) {
            return 0.0;
        }

        return round(
            (
                ($current - $previous)
                / $previous
            ) * 100,
            6
        );
    };


$importGrowthPercent =
    $calculateGrowth(
        $currentImportValue,
        $previousImportValue
    );

$exportGrowthPercent =
    $calculateGrowth(
        $currentExportValue,
        $previousExportValue
    );

$importVolumeGrowthPercent =
    $calculateGrowth(
        $currentImportVolume,
        $previousImportVolume
    );

$exportVolumeGrowthPercent =
    $calculateGrowth(
        $currentExportVolume,
        $previousExportVolume
    );

/*
|--------------------------------------------------------------------------
| Executive Overview
|--------------------------------------------------------------------------
*/

$overview = [

    'import' => [

        /*
        |--------------------------------------------------------------------------
        | Current Trade Value
        |--------------------------------------------------------------------------
        */

        'current' =>
            $currentImportValue,

        /*
        |--------------------------------------------------------------------------
        | Previous Trade Value
        |--------------------------------------------------------------------------
        */

        'previous' =>
            $previousImportValue,

        /*
        |--------------------------------------------------------------------------
        | Growth
        |--------------------------------------------------------------------------
        */

        'growth_percent' =>
            $importGrowthPercent,

        /*
        |--------------------------------------------------------------------------
        | Current Physical Volume
        |--------------------------------------------------------------------------
        */

        'physical_volume_kg' =>
            $currentImportVolume,

        /*
        |--------------------------------------------------------------------------
        | Previous Physical Volume
        |--------------------------------------------------------------------------
        */

        'previous_physical_volume_kg' =>
            $previousImportVolume,

        /*
        |--------------------------------------------------------------------------
        | Physical Volume Growth
        |--------------------------------------------------------------------------
        */

        'physical_volume_growth_percent' =>
            $importVolumeGrowthPercent,
    ],


    'export' => [

        /*
        |--------------------------------------------------------------------------
        | Current Trade Value
        |--------------------------------------------------------------------------
        */

        'current' =>
            $currentExportValue,

        /*
        |--------------------------------------------------------------------------
        | Previous Trade Value
        |--------------------------------------------------------------------------
        */

        'previous' =>
            $previousExportValue,

        /*
        |--------------------------------------------------------------------------
        | Growth
        |--------------------------------------------------------------------------
        */

        'growth_percent' =>
            $exportGrowthPercent,

        /*
        |--------------------------------------------------------------------------
        | Current Physical Volume
        |--------------------------------------------------------------------------
        */

        'physical_volume_kg' =>
            $currentExportVolume,

        /*
        |--------------------------------------------------------------------------
        | Previous Physical Volume
        |--------------------------------------------------------------------------
        */

        'previous_physical_volume_kg' =>
            $previousExportVolume,

        /*
        |--------------------------------------------------------------------------
        | Physical Volume Growth
        |--------------------------------------------------------------------------
        */

        'physical_volume_growth_percent' =>
            $exportVolumeGrowthPercent,
    ],
];


/*
|--------------------------------------------------------------------------
| Historical Yearly Market Signal
|--------------------------------------------------------------------------
|
| Use the already aggregated country intelligence.
| Do NOT use the legacy buildTopCountries() pipeline here.
|
*/

$majorExportDestinations =
    $current['major_export_destinations']
    ?? [];

$majorImportSources =
    $current['major_import_sources']
    ?? [];

$currentYear =
    (int) $period->publicThroughYear;


/*
|--------------------------------------------------------------------------
| Leading Import Origin
|--------------------------------------------------------------------------
*/

$leadingImportOrigin =
    $majorImportSources[$currentYear][0]
    ?? null;


/*
|--------------------------------------------------------------------------
| Leading Export Destination
|--------------------------------------------------------------------------
*/

$leadingExportDestination =
    $majorExportDestinations[$currentYear][0]
    ?? null;


/*
|--------------------------------------------------------------------------
| Market Signal
|--------------------------------------------------------------------------
*/

$marketSignal = [

    'import_growth_percent' =>
        $overview['import']['growth_percent']
        ?? 0,

    'export_growth_percent' =>
        $overview['export']['growth_percent']
        ?? 0,

    'leading_origin' =>
        $leadingImportOrigin
            ? [
                'country' =>
                    $leadingImportOrigin['country']
                    ?? null,

                'trade_value' =>
                    (float) (
                        $leadingImportOrigin['trade_value']
                        ?? 0
                    ),

                'trade_volume' =>
                    (float) (
                        $leadingImportOrigin['trade_volume']
                        ?? 0
                    ),
            ]
            : null,

    'leading_destination' =>
        $leadingExportDestination
            ? [
                'country' =>
                    $leadingExportDestination['country']
                    ?? null,

                'trade_value' =>
                    (float) (
                        $leadingExportDestination['trade_value']
                        ?? 0
                    ),

                'trade_volume' =>
                    (float) (
                        $leadingExportDestination['trade_volume']
                        ?? 0
                    ),
            ]
            : null,
];


/*
|--------------------------------------------------------------------------
| Return Historical Yearly Dataset
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Historical Country Intelligence
|--------------------------------------------------------------------------
|
| Adapt historical yearly country aggregation to the
| frontend country-card structure.
|
*/

$topImportOrigins =
    collect(
        $majorImportSources[$currentYear] ?? []
    )
        ->map(
            static function (array $country): array {

                $countryNameEn =
                    trim(
                        (string) (
                            $country['country_name_en']
                            ?? ''
                        )
                    );

                $countryNameId =
                    trim(
                        (string) (
                            $country['country_name_id']
                            ?? ''
                        )
                    );

                $countryName =
                    trim(
                        (string) (
                            $country['country']
                            ?? $country['trade_country']
                            ?? $country['name']
                            ?? ''
                        )
                    );

                $value =
                    (float) (
                        $country['trade_value']
                        ?? $country['value']
                        ?? 0
                    );

                $volume =
                    (float) (
                        $country['trade_volume']
                        ?? $country['volume']
                        ?? 0
                    );

                return [

                    'country' =>
                        $countryName,

                    'name' =>
                        $countryName,

                    'trade_country' =>
                        $countryName,

                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Country Identity
                    |--------------------------------------------------------------------------
                    */

                    'country_id' =>
                        $country['country_id']
                        ?? null,

                    'country_code' =>
                        $country['country_code']
                        ?? null,

                    'iso3' =>
                        $country['iso3']
                        ?? null,

                    'country_name_en' =>
                        $countryNameEn,

                    'country_name_id' =>
                        $countryNameId,

                    'flag_emoji' =>
                        $country['flag_emoji']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Trade Metrics
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        $value,

                    'trade_value' =>
                        $value,

                    'volume' =>
                        $volume,

                    'trade_volume' =>
                        $volume,
                ];
            }
        )
        ->values()
        ->all();

$topExportDestinations =
    collect(
        $majorExportDestinations[$currentYear] ?? []
    )
        ->map(
            static function (array $country): array {

                $countryNameEn =
                    trim(
                        (string) (
                            $country['country_name_en']
                            ?? ''
                        )
                    );

                $countryNameId =
                    trim(
                        (string) (
                            $country['country_name_id']
                            ?? ''
                        )
                    );

                $countryName =
                    trim(
                        (string) (
                            $country['country']
                            ?? $country['trade_country']
                            ?? $country['name']
                            ?? ''
                        )
                    );

                $value =
                    (float) (
                        $country['trade_value']
                        ?? $country['value']
                        ?? 0
                    );

                $volume =
                    (float) (
                        $country['trade_volume']
                        ?? $country['volume']
                        ?? 0
                    );

                return [

                    'country' =>
                        $countryName,

                    'name' =>
                        $countryName,

                    'destination' =>
                        $countryName,

                    'trade_country' =>
                        $countryName,

                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Country Identity
                    |--------------------------------------------------------------------------
                    */

                    'country_id' =>
                        $country['country_id']
                        ?? null,

                    'country_code' =>
                        $country['country_code']
                        ?? null,

                    'iso3' =>
                        $country['iso3']
                        ?? null,

                    'country_name_en' =>
                        $countryNameEn,

                    'country_name_id' =>
                        $countryNameId,

                    'flag_emoji' =>
                        $country['flag_emoji']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Trade Metrics
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        $value,

                    'trade_value' =>
                        $value,

                    'volume' =>
                        $volume,

                    'trade_volume' =>
                        $volume,
                ];
            }
        )
        ->values()
        ->all();

return [

    'meta' =>
        $meta,


    /*
    |--------------------------------------------------------------------------
    | Historical Yearly Intelligence
    |--------------------------------------------------------------------------
    */

    'yearly_trend' =>
        $current['yearly_trend']
        ?? [],

    'major_export_destinations' =>
        $majorExportDestinations,

    'major_import_sources' =>
        $majorImportSources,


    /*
    |--------------------------------------------------------------------------
    | Executive Overview
    |--------------------------------------------------------------------------
    */

    'overview' =>
        $overview,


    /*
    |--------------------------------------------------------------------------
    | Historical Country Intelligence
    |--------------------------------------------------------------------------
    |
    | Frontend legacy cards expect:
    |
    |   top_import_origins
    |   top_export_destinations
    |
    | Historical yearly aggregation already provides the same
    | information through:
    |
    |   major_import_sources
    |   major_export_destinations
    |
    */
/*
|--------------------------------------------------------------------------
| Historical Country Intelligence
|--------------------------------------------------------------------------
|
| Adapt historical yearly country aggregation to the
| frontend country-card structure.
|
*/
'top_import_origins' =>
    $topImportOrigins,

'top_export_destinations' =>
    $topExportDestinations,


    /*
    |--------------------------------------------------------------------------
    | Legacy / Detail Sections
    |--------------------------------------------------------------------------
    |
    | These remain empty until their dedicated historical
    | aggregations are implemented.
    |
    */

    'dataset' => [],

    'current' => [],

    'previous' => [],

    'by_subsector' => [],

    'by_flow' => [],

    'top_import_products' => [],

    'top_export_products' => [],

    'import_market_share' => [],

    'export_market_share' => [],

    'monthly_trend' => [],

    'hs8_products' => [],
];
    }


    /*
    |--------------------------------------------------------------------------
    | EXISTING CURRENT / YTD / MONTHLY PATH
    |--------------------------------------------------------------------------
    |
    | This remains the existing production logic.
    |
    */

    $current =
        collect($current);

    $previous =
        collect($previous);


    $trendRows =
        $currentDescriptor === $previousDescriptor
            ? $current
            : $current
                ->concat($previous)
                ->values();


    $meta =
        $this->snapshotMeta(
            $period,
            $datasets
        );

    $meta['generated_at'] =
        $snapshot['meta']['generated_at']
        ?? $meta['generated_at'];


    return [

        'meta' =>
            $meta,

        'overview' =>
            $this->buildOverview(
                $current,
                $previous
            ),

        'by_subsector' =>
            $this->buildSubsectorPerformance(
                $current,
                $previous
            ),

        'by_flow' =>
            $this->buildFlowPerformance(
                $current,
                $previous
            ),

        'top_import_products' =>
            $this->buildTopProducts(
                $current,
                'import'
            ),

        'top_export_products' =>
            $this->buildTopProducts(
                $current,
                'export'
            ),

        'top_import_origins' =>
            $this->buildTopCountries(
                $current,
                'import'
            ),

        'top_export_destinations' =>
            $this->buildTopCountries(
                $current,
                'export'
            ),

        'import_market_share' =>
            $this->buildCountryMarketShare(
                $current,
                'import'
            ),

        'export_market_share' =>
            $this->buildCountryMarketShare(
                $current,
                'export'
            ),


        /*
        |--------------------------------------------------------------------------
        | Monthly Trend
        |--------------------------------------------------------------------------
        |
        | Only current / YTD path uses monthly trend.
        |
        */

        'monthly_trend' =>
            $this->buildMonthlyTrend(
                $trendRows
            ),


        /*
        |--------------------------------------------------------------------------
        | Yearly Trend
        |--------------------------------------------------------------------------
        |
        | Existing current/YTD behavior remains unchanged.
        |
        */

        'yearly_trend' =>
            $this->buildYearlyTrend(
                $trendRows
            ),

        'hs8_products' =>
            $this->buildHs8Products(
                $current
            ),
    ];
}

    protected function snapshotPeriod(array $snapshot): TradeReportingPeriod
    {
        $meta = $snapshot['meta'];

        return TradeReportingPeriod::forSelection(
            currentYear: (int) $meta['current_year'],
            currentMonth: (int) $meta['through_month'],
            comparisonYear: (int) $meta['comparison_year'],
            comparisonMonth: (int) $meta['comparison_through_month'],
            mode: (string) ($meta['mode'] ?? 'ytd'),
        );
    }

protected function sumVolumeKgByFlow(
    $rows,
    string $flow
): float {
    return (float) $rows
        ->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
        )
        ->sum(
            fn ($row) =>
                (float) ($row['volume'] ?? 0)
        );
}

protected function sumDerivedPcsByFlow(
    $rows,
    string $flow
): array {
    /*
    |--------------------------------------------------------------------------
    | Filter Flow
    |--------------------------------------------------------------------------
    */

    $flowRows =
        $rows->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
        );


    /*
    |--------------------------------------------------------------------------
    | Only Validated / Converted PCS
    |--------------------------------------------------------------------------
    |
    | Never derive PCS here.
    | This method only aggregates PCS that have already
    | been calculated by the HS-8 conversion engine.
    |
    */

    $convertedRows =
        $flowRows->filter(
            fn ($row) =>
                ($row['conversion_status'] ?? null)
                    === 'CONVERTED'
                &&
                ($row['derived_pcs'] ?? null)
                    !== null
        );


    /*
    |--------------------------------------------------------------------------
    | Coverage
    |--------------------------------------------------------------------------
    */

    $totalRows =
        $flowRows->count();

    $convertedCount =
        $convertedRows->count();


    /*
    |--------------------------------------------------------------------------
    | Result
    |--------------------------------------------------------------------------
    */

    return [

        'pcs' =>
            $convertedCount > 0
                ? (float) $convertedRows->sum(
                    fn ($row) =>
                        (float) $row['derived_pcs']
                )
                : null,

        'converted_rows' =>
            $convertedCount,

        'total_rows' =>
            $totalRows,

        'coverage_percent' =>
            $totalRows > 0
                ? round(
                    (
                        $convertedCount
                        /
                        $totalRows
                    ) * 100,
                    2
                )
                : 0.0,
    ];
}


    /*
    |--------------------------------------------------------------------------
    | Executive Overview
    |--------------------------------------------------------------------------
    */

    protected function buildOverview(
    $current,
    $previous
): array {
    /*
    |--------------------------------------------------------------------------
    | Trade Value
    |--------------------------------------------------------------------------
    */

    $currentImport =
        $this->sumByFlow(
            $current,
            'import'
        );

    $previousImport =
        $this->sumByFlow(
            $previous,
            'import'
        );

    $currentExport =
        $this->sumByFlow(
            $current,
            'export'
        );

    $previousExport =
        $this->sumByFlow(
            $previous,
            'export'
        );


    /*
    |--------------------------------------------------------------------------
    | Official Physical Volume — KG
    |--------------------------------------------------------------------------
    */

    $currentImportKg =
        $this->sumVolumeKgByFlow(
            $current,
            'import'
        );
        
    $previousImportKg =
        $this->sumVolumeKgByFlow(
            $previous,
            'import'
        );

    $currentExportKg =
        $this->sumVolumeKgByFlow(
            $current,
            'export'
        );

    $previousExportKg =
        $this->sumVolumeKgByFlow(
            $previous,
            'export'
        );


    /*
    |--------------------------------------------------------------------------
    | Derived Physical Volume — PCS
    |--------------------------------------------------------------------------
    */

    $currentImportPcs =
        $this->sumDerivedPcsByFlow(
            $current,
            'import'
        );

    $previousImportPcs =
        $this->sumDerivedPcsByFlow(
            $previous,
            'import'
        );

    $currentExportPcs =
        $this->sumDerivedPcsByFlow(
            $current,
            'export'
        );

    $previousExportPcs =
        $this->sumDerivedPcsByFlow(
            $previous,
            'export'
        );


    /*
    |--------------------------------------------------------------------------
    | Return Executive Overview
    |--------------------------------------------------------------------------
    */

    return [

        'import' => [

            'current' =>
                $currentImport,

            'previous' =>
                $previousImport,

            'growth_percent' =>
                $this->growthPercent(
                    $currentImport,
                    $previousImport
                ),

            /*
            |--------------------------------------------------------------------------
            | Official KG
            |--------------------------------------------------------------------------
            */

            'physical_volume_kg' =>
                $currentImportKg,

            'previous_physical_volume_kg' =>
                $previousImportKg,
            
             'physical_volume_growth_percent' =>
                $this->growthPercent(
                    $currentImportKg,
                    $previousImportKg
                ),

            /*
            |--------------------------------------------------------------------------
            | PCS Intelligence
            |--------------------------------------------------------------------------
            */

            'physical_volume_pcs' =>
                $currentImportPcs['pcs'],

            'previous_physical_volume_pcs' =>
                $previousImportPcs['pcs'],

            'physical_volume_coverage_percent' =>
                $currentImportPcs['coverage_percent'],

            'physical_volume_converted_rows' =>
                $currentImportPcs['converted_rows'],

            'physical_volume_total_rows' =>
                $currentImportPcs['total_rows'],
        ],


        'export' => [

            'current' =>
                $currentExport,

            'previous' =>
                $previousExport,

            'growth_percent' =>
                $this->growthPercent(
                    $currentExport,
                    $previousExport
                ),

            /*
            |--------------------------------------------------------------------------
            | Official KG
            |--------------------------------------------------------------------------
            */

            'physical_volume_kg' =>
                $currentExportKg,

            'previous_physical_volume_kg' =>
                $previousExportKg,
            
            'physical_volume_growth_percent' =>
            $this->growthPercent(
                $currentExportKg,
                $previousExportKg
            ),

            /*
            |--------------------------------------------------------------------------
            | PCS Intelligence
            |--------------------------------------------------------------------------
            */

            'physical_volume_pcs' =>
                $currentExportPcs['pcs'],

            'previous_physical_volume_pcs' =>
                $previousExportPcs['pcs'],

            'physical_volume_coverage_percent' =>
                $currentExportPcs['coverage_percent'],

            'physical_volume_converted_rows' =>
                $currentExportPcs['converted_rows'],

            'physical_volume_total_rows' =>
                $currentExportPcs['total_rows'],
        ],
    ];
}

    /*
    |--------------------------------------------------------------------------
    | Subsector Performance
    |--------------------------------------------------------------------------
    */

    protected function buildSubsectorPerformance(
        $current,
        $previous
    ): array {
        return $current
            ->groupBy('subsector')
            ->map(
                function (
                    $items,
                    $subsector
                ) use (
                    $previous
                ) {
                    $previousItems =
                        $previous->where(
                            'subsector',
                            $subsector
                        );

                    $currentImport =
                        $this->sumByFlow(
                            $items,
                            'import'
                        );

                    $currentExport =
                        $this->sumByFlow(
                            $items,
                            'export'
                        );

                    $previousImport =
                        $this->sumByFlow(
                            $previousItems,
                            'import'
                        );

                    $previousExport =
                        $this->sumByFlow(
                            $previousItems,
                            'export'
                        );

                    $first =
                        $items->first();

                    return [

                        'subsector' =>
                            $subsector,

                        'label_en' =>
                            $first['label_en']
                                ?? null,

                        'label_id' =>
                            $first['label_id']
                                ?? null,

                        'import_value' =>
                            $currentImport,

                        'export_value' =>
                            $currentExport,

                        'import_previous_value' =>
                            $previousImport,

                        'export_previous_value' =>
                            $previousExport,

                        'import_growth_percent' =>
                            $this->growthPercent(
                                $currentImport,
                                $previousImport
                            ),

                        'export_growth_percent' =>
                            $this->growthPercent(
                                $currentExport,
                                $previousExport
                            ),
                    ];
                }
            )
            ->sortByDesc(
                'import_value'
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Flow Performance
    |--------------------------------------------------------------------------
    */

    protected function buildFlowPerformance(
        $current,
        $previous
    ): array {
        $result = [];

        foreach (
            [
                'import',
                'export',
            ] as $flow
        ) {
            $currentValue =
                $this->sumByFlow(
                    $current,
                    $flow
                );

            $previousValue =
                $this->sumByFlow(
                    $previous,
                    $flow
                );

            $result[] = [

                'flow' =>
                    $flow,

                'value' =>
                    $currentValue,

                'previous_value' =>
                    $previousValue,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentValue,
                        $previousValue
                    ),
            ];
        }

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | Top Products
    |--------------------------------------------------------------------------
    */

    protected function buildTopProducts(
    $rows,
    string $flow,
    int $limit = 10
): array {
    return $rows
        ->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
        )
        ->groupBy('hs4')
        ->map(
            function (
                $items,
                $hs4
            ) use (
                $flow
            ) {
                $first =
                    $items->first();

                $conversionComplete =
                    $items->every(
                        fn ($item) =>
                            ($item['conversion_status'] ?? null)
                                === 'CONVERTED'
                            &&
                            ($item['derived_pcs'] ?? null)
                                !== null
                    );

                $convertedRows =
                    $items->filter(
                        fn ($item) =>
                            ($item['conversion_status'] ?? null)
                                === 'CONVERTED'
                            &&
                            ($item['derived_pcs'] ?? null)
                                !== null
                    );

                return [

                    'hs4' =>
                        $hs4,

                    'description' =>
                        $first['description']
                            ?? null,

                    'subsector' =>
                        $first['subsector']
                            ?? null,

                    'flow' =>
                        $flow,

                    'value' =>
                        (float) $items->sum(
                            'value'
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | Official KG
                    |--------------------------------------------------------------------------
                    |
                    | Kept in backend.
                    | Tier A UI does not need to display it.
                    |
                    */

                    'volume' =>
                        (float) $items->sum(
                            'volume'
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | HS-8 Derived PCS
                    |--------------------------------------------------------------------------
                    */

                    'derived_pcs' =>
                        $conversionComplete
                            ? (float) $convertedRows->sum(
                                'derived_pcs'
                            )
                            : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Conversion Coverage
                    |--------------------------------------------------------------------------
                    */

                    'conversion_complete' =>
                        $conversionComplete,

                    'conversion_rows' =>
                        $convertedRows->count(),

                    'total_rows' =>
                        $items->count(),

                    'conversion_coverage_percent' =>
                        $items->count() > 0
                            ? round(
                                (
                                    $convertedRows->count()
                                    /
                                    $items->count()
                                ) * 100,
                                2
                            )
                            : 0.0,
                ];
            }
        )
        ->sortByDesc(
            'value'
        )
        ->take(
            $limit
        )
        ->values()
        ->all();
}

    /*
    |--------------------------------------------------------------------------
    | Top Countries
    |--------------------------------------------------------------------------
    */

    protected function buildTopCountries(
    $rows,
    string $flow,
    int $limit = 10
): array {
    $items =
        $rows->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
                && filled(
                    $row['country']
                    ?? null
                )
        );

    return $items
        ->groupBy(
            fn ($row) =>
                $row['country_code']
                ?? $row['country']
        )
        ->map(
            function (
                $group,
                $countryKey
            ) {
                $first =
                    $group->first();

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Country Identity
                    |--------------------------------------------------------------------------
                    */

                    'country_id' =>
                        $first['country_id']
                        ?? null,

                    'country_code' =>
                        $first['country_code']
                        ?? null,

                    'iso3' =>
                        $first['iso3']
                        ?? null,

                    'country_name_en' =>
                        $first['country_name_en']
                        ?? null,

                    'country_name_id' =>
                        $first['country_name_id']
                        ?? null,
                    
                    'flag_emoji' =>
                        $first['flag_emoji']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Legacy Country Name
                    |--------------------------------------------------------------------------
                    |
                    | Keep this for backward compatibility.
                    |
                    */

                    'country' =>
                        $first['country']
                        ?? $countryKey,

                    /*
                    |--------------------------------------------------------------------------
                    | Trade Metrics
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        (float) $group->sum(
                            'value'
                        ),

                    'volume' =>
                        (float) $group->sum(
                            'volume'
                        ),
                ];
            }
        )
        ->sortByDesc(
            'value'
        )
        ->take(
            $limit
        )
        ->values()
        ->all();
}

    /*
    |--------------------------------------------------------------------------
    | Country Market Share
    |--------------------------------------------------------------------------
    */

    protected function buildCountryMarketShare(
    $rows,
    string $flow,
    int $limit = 10
): array {
    $items =
        $rows->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
                && filled(
                    $row['country']
                    ?? null
                )
        );

    $total =
        (float) $items->sum(
            'value'
        );

    if (
        $total <= 0.0
    ) {
        return [];
    }

    return $items
        ->groupBy(
            fn ($row) =>
                $row['country_code']
                ?? $row['country']
        )
        ->map(
            function (
                $group,
                $countryKey
            ) use (
                $total
            ) {
                $first =
                    $group->first();

                $value =
                    (float) $group->sum(
                        'value'
                    );

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Country Identity
                    |--------------------------------------------------------------------------
                    */

                    'country_id' =>
                        $first['country_id']
                        ?? null,

                    'country_code' =>
                        $first['country_code']
                        ?? null,

                    'iso3' =>
                        $first['iso3']
                        ?? null,

                    'country_name_en' =>
                        $first['country_name_en']
                        ?? null,

                    'country_name_id' =>
                        $first['country_name_id']
                        ?? null,
                    
                    'flag_emoji' =>
                        $first['flag_emoji']
                        ?? null,


                    /*
                    |--------------------------------------------------------------------------
                    | Legacy Country Name
                    |--------------------------------------------------------------------------
                    |
                    | Keep the original Kemendag country value
                    | for backward compatibility.
                    |
                    */

                    'country' =>
                        $first['country']
                        ?? $countryKey,

                    /*
                    |--------------------------------------------------------------------------
                    | Trade Metrics
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        $value,

                    'volume' =>
                        (float) $group->sum(
                            'volume'
                        ),

                    'market_share_percent' =>
                        round(
                            (
                                $value
                                / $total
                            ) * 100,
                            2
                        ),
                ];
            }
        )
        ->sortByDesc(
            'value'
        )
        ->take(
            $limit
        )
        ->values()
        ->all();
}

    /*
    |--------------------------------------------------------------------------
    | Monthly Trend
    |--------------------------------------------------------------------------
    */

    protected function buildMonthlyTrend(
        $rows
    ): array {
        return $rows
            ->filter(
                fn ($row) =>
                    !empty(
                        $row['month']
                    )
                    && (int) $row['month'] >= 1
                    && (int) $row['month'] <= 12
            )
            ->groupBy(
                function ($row) {
                    return sprintf(
                        '%04d-%02d',
                        $row['year'],
                        $row['month']
                    );
                }
            )
            ->map(
                function (
                    $items,
                    $period
                ) {
                    return [

                        'period' =>
                            $period,

                        'import' =>
                            $this->sumByFlow(
                                $items,
                                'import'
                            ),

                        'export' =>
                            $this->sumByFlow(
                                $items,
                                'export'
                            ),
                    ];
                }
            )
            ->sortKeys()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Yearly Trend
    |--------------------------------------------------------------------------
    */

    protected function buildYearlyTrend(
        $rows
    ): array {
        return $rows
            ->groupBy('year')
            ->map(
                function (
                    $items,
                    $year
                ) {
                    return [

                        'year' =>
                            (int) $year,

                        'import' =>
                            $this->sumByFlow(
                                $items,
                                'import'
                            ),

                        'export' =>
                            $this->sumByFlow(
                                $items,
                                'export'
                            ),
                    ];
                }
            )
            ->sortKeys()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | HS-8 Products
    |--------------------------------------------------------------------------
    */

    protected function buildHs8Products(
    $rows,
    int $limit = 50
): array {
    return $rows
        ->groupBy('hs_code')
        ->map(
            function (
                $items,
                $hsCode
            ) {
                $first =
                    $items->first();

                /*
                |--------------------------------------------------------------------------
                | OFFICIAL TRADE DATA
                |--------------------------------------------------------------------------
                |
                | These values remain the official trade statistics.
                | Conversion must NEVER replace official volume.
                |
                */

                $value =
                    (float) $items->sum(
                        'value'
                    );

                $volume =
                    (float) $items->sum(
                        'volume'
                    );


                /*
                |--------------------------------------------------------------------------
                | DERIVED PCS
                |--------------------------------------------------------------------------
                |
                | Only rows successfully converted by the
                | HS-specific ACTIVE factor are included.
                |
                | No fallback factor is applied.
                |
                */

                $convertedItems =
                    $items->filter(
                        fn ($item) =>
                            ($item['conversion_status'] ?? null)
                                === 'CONVERTED'
                            &&
                            ($item['derived_pcs'] ?? null)
                                !== null
                    );


                $derivedPcs =
                    $convertedItems->isNotEmpty()
                        ? (float) $convertedItems->sum(
                            'derived_pcs'
                        )
                        : null;


                /*
                |--------------------------------------------------------------------------
                | CONVERSION PROVENANCE
                |--------------------------------------------------------------------------
                */

                $conversionStatuses =
                    $items
                        ->pluck(
                            'conversion_status'
                        )
                        ->filter()
                        ->unique()
                        ->values();


                /*
                |--------------------------------------------------------------------------
                | Determine HS-level conversion status
                |--------------------------------------------------------------------------
                |
                | CONVERTED
                |     At least one trade row has been converted.
                |
                | NOT_CONVERTIBLE
                |     No ACTIVE HS-specific factor exists.
                |
                | MIXED
                |     Normally relevant when data contains different
                |     conversion outcomes within the same HS.
                |
                */

                if (
                    $conversionStatuses->count() === 1
                ) {
                    $conversionStatus =
                        $conversionStatuses->first();
                } elseif (
                    $conversionStatuses->count() > 1
                ) {
                    $conversionStatus =
                        'MIXED';
                } else {
                    $conversionStatus =
                        'NOT_CONVERTIBLE';
                }


                /*
                |--------------------------------------------------------------------------
                | Factor Provenance
                |--------------------------------------------------------------------------
                |
                | We only expose the factor if the HS-specific
                | conversion actually resolved successfully.
                |
                */

                $conversionRow =
                    $items->first(
                        fn ($item) =>
                            ($item['conversion_status'] ?? null)
                                === 'CONVERTED'
                            &&
                            ($item['conversion_factor_id'] ?? null)
                                !== null
                    );


                /*
                |--------------------------------------------------------------------------
                | HS-8 PRODUCT RESULT
                |--------------------------------------------------------------------------
                */

                return [

                    'hs_code' =>
                        $hsCode,

                    'hs4' =>
                        $first['hs4'],

                    'chapter' =>
                        $first['chapter'],

                    'description' =>
                        $first['description'],

                    'subsector' =>
                        $first['subsector'],

                    'label_en' =>
                        $first['label_en'],

                    'label_id' =>
                        $first['label_id'],

                    'flow' =>
                        $first['flow'],


                    /*
                    |--------------------------------------------------------------------------
                    | OFFICIAL TRADE DATA
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        $value,

                    'volume' =>
                        $volume,

                    'volume_unit' =>
                        'KG',


                    /*
                    |--------------------------------------------------------------------------
                    | DERIVED INTELLIGENCE
                    |--------------------------------------------------------------------------
                    */

                    'derived_pcs' =>
                        $derivedPcs,


                    /*
                    |--------------------------------------------------------------------------
                    | CONVERSION PROVENANCE
                    |--------------------------------------------------------------------------
                    */

                    'conversion_status' =>
                        $conversionStatus,

                    'conversion_code' =>
                        $conversionRow[
                            'conversion_code'
                        ]
                        ?? (
                            $conversionStatus ===
                                'NOT_CONVERTIBLE'
                                ? 'NO_ACTIVE_FACTOR'
                                : null
                        ),

                    'conversion_factor_id' =>
                        $conversionRow[
                            'conversion_factor_id'
                        ]
                        ?? null,

                    'conversion_factor' =>
                        $conversionRow[
                            'conversion_factor'
                        ]
                        ?? null,

                    'conversion_methodology' =>
                        $conversionRow[
                            'conversion_methodology'
                        ]
                        ?? 'KG_PER_PCS',

                    'conversion_factor_status' =>
                        $conversionRow[
                            'conversion_factor_status'
                        ]
                        ?? null,

                    'conversion_evidence_count' =>
                        $conversionRow[
                            'conversion_evidence_count'
                        ]
                        ?? null,

                    'conversion_total_sample_size' =>
                        $conversionRow[
                            'conversion_total_sample_size'
                        ]
                        ?? null,

                    'conversion_calculation_method' =>
                        $conversionRow[
                            'conversion_calculation_method'
                        ]
                        ?? null,
                ];
            }
        )
        ->sortByDesc(
            'value'
        )
        ->take(
            $limit
        )
        ->values()
        ->all();
}


    /*
    |--------------------------------------------------------------------------
    | Flow Normalization
    |--------------------------------------------------------------------------
    */

    protected function normalizeFlow(
        mixed $flow
    ): ?string {
        if (
            $flow === null
        ) {
            return null;
        }

        $value =
            strtolower(
                trim(
                    (string) $flow
                )
            );

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

        return $value !== ''
            ? $value
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | Sum by Flow
    |--------------------------------------------------------------------------
    */

    protected function sumByFlow(
        $rows,
        string $flow
    ): float {
        return (float)
            $rows
                ->filter(
                    fn ($row) =>
                        $row['flow'] === $flow
                )
                ->sum(
                    'value'
                );
    }


    /*
    |--------------------------------------------------------------------------
    | Growth
    |--------------------------------------------------------------------------
    */

    protected function growthPercent(
        float $current,
        float $previous
    ): float {
        if (
            $previous == 0.0
        ) {
            return $current > 0.0
                ? 100.0
                : 0.0;
        }

        return round(
            (
                (
                    $current
                    - $previous
                )
                / $previous
            ) * 100,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Float Normalization
    |--------------------------------------------------------------------------
    */

    protected function toFloat(
        mixed $value
    ): float {
        if (
            $value === null
        ) {
            return 0.0;
        }

        if (
            is_numeric($value)
        ) {
            return (float) $value;
        }

        $value =
            str_replace(
                ',',
                '',
                (string) $value
            );

        return is_numeric($value)
            ? (float) $value
            : 0.0;
    }


    /*
    |--------------------------------------------------------------------------
    | Database Columns
    |--------------------------------------------------------------------------
    */

    protected function resolveColumns(): array
    {
        return [

            'year' =>
                'year',

            'month' =>
                'month',

            'hs_code' =>
                'hs_code',

            'hs_description' =>
                'hs_description',

            'trade_value' =>
                'trade_value',

            'trade_volume' =>
                'trade_volume',

            'flow' =>
                'trade_flow',

            'country' =>
                'country_name',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Runtime Cache Key
    |--------------------------------------------------------------------------
    */

    protected function runtimeCacheKey(
        TradeReportingPeriod $period
    ): string {
        return self::CACHE_KEY
            . '.'
            . $period->snapshotKey();
    }


protected function isValidSnapshot(
    array $snapshot
): bool {
    if ($this->hasPeriodDatasets($snapshot)) {
        return (int) ($snapshot['meta']['record_count'] ?? 0) > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Basic Structure
    |--------------------------------------------------------------------------
    */

    if (
        !isset($snapshot['meta'])
        || !isset($snapshot['overview'])
    ) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Import / Export Structure
    |--------------------------------------------------------------------------
    */

    $import =
        $snapshot['overview']['import']
        ?? null;

    $export =
        $snapshot['overview']['export']
        ?? null;

    if (
        !is_array($import)
        || !is_array($export)
    ) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Minimum Trade Value Validation
    |--------------------------------------------------------------------------
    |
    | A valid snapshot must contain actual trade
    | values. Zero is acceptable only when the
    | snapshot itself genuinely contains zero data.
    |
    */

    $hasTradeData =
        array_key_exists(
            'current',
            $import
        )
        &&
        array_key_exists(
            'current',
            $export
        );

    if (!$hasTradeData) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Record Count
    |--------------------------------------------------------------------------
    */

    $recordCount =
        (int) (
            $snapshot['meta']['record_count']
            ?? 0
        );

    if ($recordCount <= 0) {
        return false;
    }

    return true;
}

protected function markSnapshotAsFallback(
    array $snapshot,
    TradeReportingPeriod $period
): array {
    $snapshot['meta']['is_fallback'] = true;

    $snapshot['meta']['fallback_reason'] =
        'LATEST_SNAPSHOT_NOT_YET_AVAILABLE';

    $snapshot['meta']['requested_snapshot_period_key'] =
        $period->snapshotKey();

    $snapshot['meta']['requested_period'] =
        $period->currentPeriod();

    return $snapshot;
}

    /*
    |--------------------------------------------------------------------------
    | Snapshot Period Validation
    |--------------------------------------------------------------------------
    */

    protected function snapshotMatchesPeriod(
        array $snapshot,
        TradeReportingPeriod $period
    ): bool {
        if ($this->hasPeriodDatasets($snapshot)) {
            foreach ($this->periodDatasetRequests($period) as $descriptor => $_) {
                if (!array_key_exists($descriptor, $snapshot['period_datasets'])) {
                    return false;
                }
            }

            return true;
        }

        $snapshotPeriodKey =
            data_get(
                $snapshot,
                'meta.snapshot_period_key'
            );

        if (
            $snapshotPeriodKey ===
            $period->snapshotKey()
        ) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Backward Compatibility
        |--------------------------------------------------------------------------
        */

        $currentPeriod =
            data_get(
                $snapshot,
                'meta.current_period'
            );

        $comparisonPeriod =
            data_get(
                $snapshot,
                'meta.comparison_period'
            );

        return
            $currentPeriod ===
                $period->currentPeriod()
            &&
            $comparisonPeriod ===
                $period->comparisonPeriod();
    }


    /*
    |--------------------------------------------------------------------------
    | Data Status
    |--------------------------------------------------------------------------
    */

    protected function dataStatus(
        TradeReportingPeriod $period
    ): string {
        return match (
            $period->status
        ) {
            'buffer_promoted' =>
                'awaiting_latest_data',

            default =>
                'available',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Snapshot
    |--------------------------------------------------------------------------
    */

    protected function emptySnapshot(
        TradeReportingPeriod $period
    ): array {
        return [

            'meta' => [

                'sector' =>
                    self::SECTOR,

                'snapshot_key' =>
                    self::CACHE_KEY,

                'snapshot_type' =>
                    self::SNAPSHOT_TYPE,

                'period' =>
                    $period->periodLabel(),

                'period_label_en' =>
                    $period->periodLabel(),

                'period_label_id' =>
                    $period->periodLabel(),

                'display_period_label_en' =>
                    $period
                        ->displayPeriodLabelEn(),

                'display_period_label_id' =>
                    $period
                        ->displayPeriodLabelId(),

                'comparison_period_label_en' =>
                    $period
                        ->comparisonPeriodLabelEn(),

                'comparison_period_label_id' =>
                    $period
                        ->comparisonPeriodLabelId(),

                'current_period' =>
                    $period->currentPeriod(),

                'comparison_period' =>
                    $period->comparisonPeriod(),

                'current_year' =>
                    $period->publicThroughYear,

                'comparison_year' =>
                    $period->comparisonYear,

                'through_month' =>
                    $period->publicThroughMonth,

                'comparison_through_month' =>
                    $period->comparisonThroughMonth,

                'buffer_period' =>
                    $period->bufferPeriod(),

                'buffer_status' =>
                    $period->status,

                'data_status' =>
                    $this->dataStatus(
                        $period
                    ),

                'generated_at' =>
                    null,

                'record_count' =>
                    0,

                'snapshot_period_key' =>
                    $period->snapshotKey(),

                'hs_codes' => [],
            ],

            'current' => [],
            'previous' => [],

            'overview' => [

                'import' => [
                    'current' =>
                        0,

                    'previous' =>
                        0,

                    'growth_percent' =>
                        0,
                ],

                'export' => [
                    'current' =>
                        0,

                    'previous' =>
                        0,

                    'growth_percent' =>
                        0,
                ],
            ],


            'by_subsector' => [],

            'by_flow' => [],

            'top_import_products' => [],

            'top_export_products' => [],

            'top_import_origins' => [],

            'top_export_destinations' => [],

            'import_market_share' => [],

            'export_market_share' => [],

            'monthly_trend' => [],

            'yearly_trend' => [],

            'hs8_products' => [],
        ];
    }

 
protected function getComparisonSummary(
    TradeReportingPeriod $period
): array {
    $cacheKey = sprintf(
        'trade:intelligence:%s:comparison:%s',
        self::SECTOR,
        $period->snapshotKey()
    );

    return Cache::remember(
        $cacheKey,
        now()->addHours(12),
        function () use ($period) {

            $columns = $this->resolveColumns();

            /*
            |--------------------------------------------------------------------------
            | Canonical HS-8
            |--------------------------------------------------------------------------
            |
            | Garment universe comes exclusively from the
            | Canonical HS-8 Master through TextileTaxonomyService.
            |
            */

            $sectorHsCodes =
                $this->taxonomy->hsCodesForSector(
                    self::SECTOR
                );

            /*
            |--------------------------------------------------------------------------
            | Safety Check
            |--------------------------------------------------------------------------
            */

            if (empty($sectorHsCodes)) {
                return [];
            }

            /*
            |--------------------------------------------------------------------------
            | Comparison Period Filter
            |--------------------------------------------------------------------------
            */

            $rows = DB::table('trade_statistics')
                ->whereIn(
                    $columns['hs_code'],
                    $sectorHsCodes
                )
                ->where(
                    function ($query) use (
                        $columns,
                        $period
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | CURRENT PERIOD
                        |--------------------------------------------------------------------------
                        */

                        $query->where(
                            function ($q) use (
                                $columns,
                                $period
                            ) {
                                $q->where(
                                    $columns['year'],
                                    $period->publicThroughYear
                                );

                                if ($period->isMonthly()) {
                                    $q->where(
                                        $columns['month'],
                                        $period->publicThroughMonth
                                    );
                                } else {
                                    $q->whereBetween(
                                        $columns['month'],
                                        [
                                            1,
                                            $period->isFullYear()
                                                ? 12
                                                : $period->publicThroughMonth,
                                        ]
                                    );
                                }
                            }
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | COMPARISON PERIOD
                        |--------------------------------------------------------------------------
                        */

                        $query->orWhere(
                            function ($q) use (
                                $columns,
                                $period
                            ) {
                                $q->where(
                                    $columns['year'],
                                    $period->comparisonYear
                                );

                                if ($period->isMonthly()) {
                                    $q->where(
                                        $columns['month'],
                                        $period->comparisonThroughMonth
                                    );
                                } else {
                                    $q->whereBetween(
                                        $columns['month'],
                                        [
                                            1,
                                            $period->isFullYear()
                                                ? 12
                                                : $period->comparisonThroughMonth,
                                        ]
                                    );
                                }
                            }
                        );
                    }
                )
                ->select([
                    $columns['year']
                        . ' as trade_year',

                    $columns['flow']
                        . ' as trade_flow',
                ])
                ->selectRaw(
                    'SUM(' .
                    $columns['trade_value'] .
                    ') AS trade_value'
                )
                ->selectRaw(
                    'SUM(' .
                    $columns['trade_volume'] .
                    ') AS trade_volume'
                )
                ->groupBy([
                    $columns['year'],
                    $columns['flow'],
                ])
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Normalize Result
            |--------------------------------------------------------------------------
            */

            $result = [];

            foreach ($rows as $row) {
                $result[(int) $row->trade_year]
                    [$row->trade_flow] = [
                        'value' =>
                            (float) $row->trade_value,

                        'volume_kg' =>
                            (float) $row->trade_volume,
                    ];
            }

            return $result;
        }
    );
} 
}