<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX KEMENDAG TRADE GATEWAY CANDIDATE RANKING\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  METHOD      : PROVINCE + FLOW + COUNTRY + HS PATTERN\n\n";

$residualSources = [
    'JABABEKA',
    'JAKARTA / POS PASAR BARU (PTT)',
    'LOBAM',
    'BUATAN',
];

/*
|--------------------------------------------------------------------------
| Residual source baseline
|--------------------------------------------------------------------------
*/

foreach ($residualSources as $sourceName) {

    $row =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->first();

    echo sprintf(
        "SOURCE %-35s | %6d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        $sourceName,
        $row->records,
        $row->trade_value,
        $row->trade_volume
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate ranking function
|--------------------------------------------------------------------------
*/

function rankCandidates(
    string $sourceName
): void {

    echo "========================================\n";
    echo "GATEWAY CANDIDATES : {$sourceName}\n";
    echo "========================================\n";

    /*
    |--------------------------------------------------------------------------
    | Residual source pattern
    |--------------------------------------------------------------------------
    */

    $residual =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->select(
                'province_id'
            )
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy('province_id')
            ->orderByDesc('records')
            ->get();

    echo "RESIDUAL PROVINCES:\n";

    foreach ($residual as $r) {

        echo sprintf(
            "  province_id=%-4s | %6d records\n",
            $r->province_id ?? 'NULL',
            $r->records
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Collect dominant dimensions
    |--------------------------------------------------------------------------
    */

    $dominantProvince =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->whereNotNull('province_id')
            ->select('province_id')
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy('province_id')
            ->orderByDesc('records')
            ->first();

    if ($dominantProvince === null) {
        echo "NO DOMINANT PROVINCE.\n\n";
        return;
    }

    $provinceId =
        (int) $dominantProvince->province_id;

    /*
    |--------------------------------------------------------------------------
    | Flow distribution
    |--------------------------------------------------------------------------
    */

    $flows =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->where('province_id', $provinceId)
            ->select('trade_flow')
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy('trade_flow')
            ->orderByDesc('records')
            ->get();

    echo PHP_EOL;
    echo "DOMINANT PROVINCE : {$provinceId}\n";

    echo "FLOW DISTRIBUTION:\n";

    foreach ($flows as $flow) {

        echo sprintf(
            "  %-7s | %6d records\n",
            strtoupper(
                (string) $flow->trade_flow
            ),
            $flow->records
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Candidate trade points
    |--------------------------------------------------------------------------
    |
    | Only active trade points with the same province.
    |--------------------------------------------------------------------------
    */

    $candidates =
        DB::table('trade_statistics as ts')
            ->join(
                'trade_points as tp',
                'tp.id',
                '=',
                'ts.trade_point_id'
            )
            ->whereBetween(
                'ts.year',
                [2019, 2026]
            )
            ->whereIn(
                'ts.trade_flow',
                ['export', 'import']
            )
            ->whereNotNull('ts.trade_point_id')
            ->where(
                'ts.province_id',
                $provinceId
            )
            ->where('tp.is_active', 1)
            ->select(
                'tp.id',
                'tp.code',
                'tp.name',
                'tp.name_en',
                'tp.trade_point_type_id',
                'tp.city'
            )
            ->selectRaw(
                'COUNT(*) AS matched_records'
            )
            ->selectRaw(
                'COUNT(DISTINCT ts.country_id) AS country_count'
            )
            ->selectRaw(
                'COUNT(DISTINCT ts.hs_id) AS hs_count'
            )
            ->groupBy(
                'tp.id',
                'tp.code',
                'tp.name',
                'tp.name_en',
                'tp.trade_point_type_id',
                'tp.city'
            )
            ->orderByDesc('matched_records')
            ->limit(15)
            ->get();

    if ($candidates->isEmpty()) {

        echo PHP_EOL;
        echo "NO ACTIVE GATEWAY CANDIDATES FOUND FOR PROVINCE.\n";
        echo PHP_EOL;

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Candidate output
    |--------------------------------------------------------------------------
    */

    echo PHP_EOL;
    echo "TOP PROVINCE GATEWAY CANDIDATES:\n";

    foreach ($candidates as $index => $candidate) {

        echo sprintf(
            "  %02d. ID=%-4d | %-35s | TYPE=%-3s | CITY=%-20s | MATCH=%7d | COUNTRY=%4d | HS=%5d\n",
            $index + 1,
            $candidate->id,
            trim((string) $candidate->name),
            $candidate->trade_point_type_id,
            trim((string) ($candidate->city ?? '-')),
            $candidate->matched_records,
            $candidate->country_count,
            $candidate->hs_count
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Country pattern
    |--------------------------------------------------------------------------
    */

    $countries =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->where('province_id', $provinceId)
            ->whereNotNull('country_id')
            ->select('country_id')
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy('country_id')
            ->orderByDesc('records')
            ->limit(10)
            ->get();

    echo PHP_EOL;
    echo "TOP RESIDUAL COUNTRY PATTERN:\n";

    foreach ($countries as $country) {

        $countryName =
            DB::table('mst_countries')
                ->where('id', $country->country_id)
                ->value('country_name_id');

        echo sprintf(
            "  %-30s | %6d records\n",
            $countryName ?? 'UNKNOWN',
            $country->records
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HS pattern
    |--------------------------------------------------------------------------
    */

    $hs =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->where('province_id', $provinceId)
        ->whereNotNull('hs_code')
        ->select(
            'hs_code',
            'hs_description'
        )
        ->selectRaw('COUNT(*) AS records')
        ->groupBy(
            'hs_code',
            'hs_description'
        )
        ->orderByDesc('records')
        ->limit(10)
        ->get();

echo PHP_EOL;
echo "TOP RESIDUAL HS PATTERN:\n";

foreach ($hs as $item) {

    echo sprintf(
        "  %-10s | %6d records | %s\n",
        $item->hs_code ?? 'UNKNOWN',
        $item->records,
        mb_substr(
            (string) ($item->hs_description ?? ''),
            0,
            80
        )
    );
}

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Execute analysis
|--------------------------------------------------------------------------
*/

foreach ($residualSources as $sourceName) {
    rankCandidates($sourceName);
}

/*
|--------------------------------------------------------------------------
| Safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "TRADE GATEWAY CANDIDATE RANKING : COMPLETE\n";
echo "========================================\n";