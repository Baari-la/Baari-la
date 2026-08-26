<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class TradeAvailablePeriodService
{
    public function __construct(
        protected TextileTaxonomyService $taxonomy,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Available Periods for Sector
    |--------------------------------------------------------------------------
    |
    | Cached because available trade periods do not need to be
    | recalculated on every page request.
    |
    */

    public function forSector(
        string $sector
    ): array {
        $cacheKey =
            'trade.available_periods.'
            . strtolower(trim($sector));

        return Cache::remember(
            $cacheKey,
            now()->addHours(6),
            fn () =>
                $this->buildForSector($sector)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Build Available Periods
    |--------------------------------------------------------------------------
    */

    protected function buildForSector(
    string $sector
): array {
    /*
    |--------------------------------------------------------------------------
    | Canonical HS-8 Scope
    |--------------------------------------------------------------------------
    |
    | The authoritative HS-8 universe comes from
    | TextileTaxonomyService::hsCodesForSector().
    |
    | This ensures that Available Periods use exactly
    | the same HS-8 universe as Garment Trade Intelligence.
    |
    */

    $sectorHsCodes =
        $this->taxonomy->hsCodesForSector(
            $sector
        );

    $sectorHsCodes = collect($sectorHsCodes)
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

    /*
    |--------------------------------------------------------------------------
    | Empty Canonical Scope
    |--------------------------------------------------------------------------
    */

    if (empty($sectorHsCodes)) {
        return [
            'sector' => $sector,
            'years' => [],
            'months' => [],
            'latest' => null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Trade Period Query
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | No Chapter / HS4 prefix filtering here.
    |
    | Canonical HS-8 is the final authority.
    |
    */

    $rows =
        DB::table('trade_statistics')
            ->whereIn(
                'hs_code',
                $sectorHsCodes
            )
            ->select(
                'year',
                'month'
            )
            ->distinct()
            ->orderBy('year')
            ->orderBy('month')
            ->get();

    /*
    |--------------------------------------------------------------------------
    | Build Month Map
    |--------------------------------------------------------------------------
    */

    $months = [];

    foreach ($rows as $row) {
        $year = (int) $row->year;
        $month = (int) $row->month;

        if (!isset($months[$year])) {
            $months[$year] = [];
        }

        $months[$year][] = $month;
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize Months
    |--------------------------------------------------------------------------
    */

    foreach (
        $months
        as $year => $availableMonths
    ) {
        $months[$year] =
            array_values(
                array_unique(
                    array_map(
                        'intval',
                        $availableMonths
                    )
                )
            );

        sort($months[$year]);
    }

    /*
    |--------------------------------------------------------------------------
    | Available Years
    |--------------------------------------------------------------------------
    */

    $years =
        array_map(
            'intval',
            array_keys($months)
        );

    sort($years);

    /*
    |--------------------------------------------------------------------------
    | Latest Available Period
    |--------------------------------------------------------------------------
    */

    $latest = null;

    if (!empty($years)) {
        $latestYear = max($years);

        $latestMonth =
            max(
                $months[$latestYear]
            );

        $latest = [
            'year' => $latestYear,
            'month' => $latestMonth,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Return
    |--------------------------------------------------------------------------
    */

    return [
        'sector' => $sector,
        'years' => $years,
        'months' => $months,
        'latest' => $latest,
    ];
}


    /*
    |--------------------------------------------------------------------------
    | Resolve Sector Scope
    |--------------------------------------------------------------------------
    |
    | Extract HS Chapter and HS4 definitions from the canonical
    | Textile Taxonomy configuration.
    |
    */

    protected function resolveSectorScope(
        string $sector
    ): array {
        $config =
            $this->taxonomy->sectorConfig(
                $sector
            );

        if (
            empty($config)
        ) {
            return [
                'chapters' => [],
                'hs4' => [],
            ];
        }

        $chapters = [];

        $hs4 = [];

        $this->collectScope(
            $config,
            $chapters,
            $hs4
        );

        return [
            'chapters' =>
                array_values(
                    array_unique(
                        array_map(
                            'strval',
                            $chapters
                        )
                    )
                ),

            'hs4' =>
                array_values(
                    array_unique(
                        array_map(
                            'strval',
                            $hs4
                        )
                    )
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Recursive Taxonomy Scope Collector
    |--------------------------------------------------------------------------
    */

    protected function collectScope(
        array $node,
        array &$chapters,
        array &$hs4
    ): void {
        if (
            isset($node['chapters'])
            && is_array(
                $node['chapters']
            )
        ) {
            foreach (
                $node['chapters']
                as $chapter
            ) {
                $chapters[] =
                    str_pad(
                        (string) $chapter,
                        2,
                        '0',
                        STR_PAD_LEFT
                    );
            }
        }

        if (
            isset($node['hs4'])
            && is_array(
                $node['hs4']
            )
        ) {
            foreach (
                $node['hs4']
                as $code
            ) {
                $normalized =
                    $this->taxonomy->normalizeHsCode(
                        $code
                    );

                if (
                    $normalized === null
                ) {
                    continue;
                }

                $hs4[] =
                    substr(
                        $normalized,
                        0,
                        4
                    );
            }
        }

        if (
            isset($node['subsectors'])
            && is_array(
                $node['subsectors']
            )
        ) {
            foreach (
                $node['subsectors']
                as $subsector
            ) {
                if (
                    is_array($subsector)
                ) {
                    $this->collectScope(
                        $subsector,
                        $chapters,
                        $hs4
                    );
                }
            }
        }
    }
}