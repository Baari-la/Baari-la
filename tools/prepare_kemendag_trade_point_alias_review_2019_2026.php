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
echo "DIGESTEX KEMENDAG TRADE POINT ALIAS REVIEW\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  TOP N       : 20\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function normalizeTradePointReview(?string $value): string
{
    $value = trim((string) $value);

    if ($value === '') {
        return '';
    }

    $value = mb_strtoupper($value);

    $value = str_replace(
        [
            '(U)',
            '(PTT)',
            '(SKA)',
            '(SV)',
            '(AIR)',
            '(TERMINAL)',
        ],
        ' ',
        $value
    );

    $value = preg_replace(
        '/[^A-Z0-9]+/u',
        ' ',
        $value
    ) ?? '';

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return trim($value);
}

function tokensTradePointReview(?string $value): array
{
    $normalized =
        normalizeTradePointReview($value);

    if ($normalized === '') {
        return [];
    }

    return array_values(
        array_unique(
            array_filter(
                explode(' ', $normalized)
            )
        )
    );
}

/*
|--------------------------------------------------------------------------
| Verify source baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

if ($currentNull !== 51525) {
    throw new RuntimeException(
        "Expected 51525 unresolved Trade Point records, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Trade point master
|--------------------------------------------------------------------------
*/

$masters =
    DB::table('trade_points')
        ->where('is_active', 1)
        ->get([
            'id',
            'code',
            'name',
            'name_en',
            'trade_point_type_id',
            'province_id',
            'city',
        ]);

if ($masters->count() !== 55) {
    throw new RuntimeException(
        "Expected 55 active trade points, got {$masters->count()}."
    );
}

/*
|--------------------------------------------------------------------------
| Get TOP 20 unresolved source names
|--------------------------------------------------------------------------
*/

$sources =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->selectRaw(
            '
            port_name,
            COUNT(*) AS records,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume
            '
        )
        ->groupBy('port_name')
        ->orderByDesc('records')
        ->limit(20)
        ->get();

/*
|--------------------------------------------------------------------------
| Function to score candidate
|--------------------------------------------------------------------------
*/

function scoreReviewCandidate(
    string $sourceName,
    ?string $sourceProvinceName,
    ?int $sourceProvinceId,
    object $master
): array {

    $source =
        normalizeTradePointReview(
            $sourceName
        );

    $masterName =
        trim((string) $master->name);

    $masterNameEn =
        trim((string) ($master->name_en ?? ''));

    $masterCode =
        trim((string) ($master->code ?? ''));

    $masterNormalized =
        normalizeTradePointReview(
            $masterName
        );

    $masterNormalizedEn =
        normalizeTradePointReview(
            $masterNameEn
        );

    $score = 0;
    $basis = [];

    /*
    |--------------------------------------------------------------------------
    | Exact normalized name
    |--------------------------------------------------------------------------
    */

    if (
        $source !== ''
        &&
        (
            $source === $masterNormalized
            ||
            (
                $masterNormalizedEn !== ''
                &&
                $source === $masterNormalizedEn
            )
        )
    ) {
        $score += 100;
        $basis[] = 'EXACT_NAME';
    }

    /*
    |--------------------------------------------------------------------------
    | Name contains
    |--------------------------------------------------------------------------
    */

    if (
        $source !== ''
        &&
        $masterNormalized !== ''
        &&
        (
            str_contains(
                $source,
                $masterNormalized
            )
            ||
            str_contains(
                $masterNormalized,
                $source
            )
        )
    ) {
        $score += 50;
        $basis[] = 'NAME_CONTAINS';
    }

    /*
    |--------------------------------------------------------------------------
    | Token overlap
    |--------------------------------------------------------------------------
    */

    $sourceTokens =
        tokensTradePointReview(
            $sourceName
        );

    $masterTokens =
        tokensTradePointReview(
            $masterName
        );

    if (
        count($sourceTokens) > 0
        &&
        count($masterTokens) > 0
    ) {

        $intersection =
            array_intersect(
                $sourceTokens,
                $masterTokens
            );

        $union =
            array_unique(
                array_merge(
                    $sourceTokens,
                    $masterTokens
                )
            );

        $jaccard =
            count($union) > 0
                ? count($intersection)
                    / count($union)
                : 0;

        if ($jaccard >= 0.75) {
            $score += 35;
            $basis[] = 'HIGH_TOKEN_OVERLAP';
        } elseif ($jaccard >= 0.50) {
            $score += 20;
            $basis[] = 'MEDIUM_TOKEN_OVERLAP';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Code relation
    |--------------------------------------------------------------------------
    */

    $codeNormalized =
        normalizeTradePointReview(
            $masterCode
        );

    if (
        $codeNormalized !== ''
        &&
        $source !== ''
        &&
        (
            str_contains(
                $source,
                $codeNormalized
            )
            ||
            str_contains(
                $codeNormalized,
                $source
            )
        )
    ) {
        $score += 25;
        $basis[] = 'CODE_RELATION';
    }

    /*
    |--------------------------------------------------------------------------
    | Province context
    |--------------------------------------------------------------------------
    */

    if (
        $sourceProvinceId !== null
        &&
        $sourceProvinceId > 0
        &&
        (int) $master->province_id > 0
    ) {

        if (
            $sourceProvinceId
            ===
            (int) $master->province_id
        ) {
            $score += 20;
            $basis[] = 'PROVINCE_MATCH';
        } else {
            $score -= 20;
            $basis[] = 'PROVINCE_MISMATCH';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Confidence
    |--------------------------------------------------------------------------
    */

    $confidence = 'LOW';

    if ($score >= 100) {
        $confidence = 'HIGH';
    } elseif ($score >= 70) {
        $confidence = 'MEDIUM';
    }

    return [
        'score' => $score,
        'confidence' => $confidence,
        'basis' => $basis,
    ];
}

/*
|--------------------------------------------------------------------------
| Build review rows
|--------------------------------------------------------------------------
*/

$reviewRows = [];

foreach ($sources as $source) {

    $sourceName =
        trim((string) $source->port_name);

    /*
    |--------------------------------------------------------------------------
    | Determine dominant province
    |--------------------------------------------------------------------------
    */

    $province =
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
            ->where(
                'port_name',
                $sourceName
            )
            ->select(
                'province_id',
                'province_name'
            )
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy(
                'province_id',
                'province_name'
            )
            ->orderByDesc('records')
            ->first();

    $provinceId =
        $province?->province_id !== null
            ? (int) $province->province_id
            : null;

    $provinceName =
        trim((string) (
            $province?->province_name
            ?? ''
        ));

    $candidates = [];

    foreach ($masters as $master) {

        $result =
            scoreReviewCandidate(
                $sourceName,
                $provinceName,
                $provinceId,
                $master
            );

        if ($result['score'] <= 0) {
            continue;
        }

        $candidates[] = [
            'id' => (int) $master->id,
            'name' => $master->name,
            'name_en' => $master->name_en,
            'code' => $master->code,
            'province_id' => $master->province_id,
            'city' => $master->city,
            'score' => $result['score'],
            'confidence' => $result['confidence'],
            'basis' => $result['basis'],
        ];
    }

    usort(
        $candidates,
        static function (
            array $a,
            array $b
        ): int {
            return $b['score'] <=> $a['score'];
        }
    );

    $candidates =
        array_slice(
            $candidates,
            0,
            3
        );

    $topScore =
        $candidates[0]['score']
        ?? 0;

    $secondScore =
        $candidates[1]['score']
        ?? 0;

    $gap =
        $topScore - $secondScore;

    /*
    |--------------------------------------------------------------------------
    | Review status
    |--------------------------------------------------------------------------
    |
    | Do NOT auto-approve merely because a candidate has a score.
    |--------------------------------------------------------------------------
    */

    $reviewStatus = 'NO_CANDIDATE';

    if (
        $topScore >= 100
        &&
        $gap >= 30
    ) {
        $reviewStatus = 'HIGH_CANDIDATE';
    } elseif (
        $topScore >= 70
        &&
        $gap >= 20
    ) {
        $reviewStatus = 'MEDIUM_CANDIDATE';
    } elseif (
        $topScore > 0
    ) {
        $reviewStatus = 'MANUAL_REVIEW';
    }

    $reviewRows[] = [
        'source_name' => $sourceName,
        'records' => (int) $source->records,
        'trade_value' => (float) $source->trade_value,
        'trade_volume' => (float) $source->trade_volume,
        'province_name' => $provinceName,
        'province_id' => $provinceId,
        'review_status' => $reviewStatus,
        'top_score' => $topScore,
        'score_gap' => $gap,
        'candidates' => $candidates,
    ];
}

/*
|--------------------------------------------------------------------------
| Output review sheet
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TOP 20 TRADE POINT REVIEW SHEET\n";
echo "========================================\n";

foreach ($reviewRows as $index => $row) {

    echo PHP_EOL;

    echo sprintf(
        "%02d. SOURCE : %s\n",
        $index + 1,
        $row['source_name']
    );

    echo "    RECORDS     : "
        . number_format(
            $row['records']
        )
        . PHP_EOL;

    echo "    VALUE       : "
        . number_format(
            $row['trade_value'],
            3,
            '.',
            ''
        )
        . PHP_EOL;

    echo "    VOLUME      : "
        . number_format(
            $row['trade_volume'],
            3,
            '.',
            ''
        )
        . PHP_EOL;

    echo "    PROVINCE    : "
        . (
            $row['province_name'] !== ''
                ? $row['province_name']
                : '-'
        )
        . " ["
        . (
            $row['province_id'] !== null
                ? $row['province_id']
                : '-'
        )
        . "]\n";

    echo "    REVIEW      : "
        . $row['review_status']
        . PHP_EOL;

    echo "    TOP SCORE   : "
        . $row['top_score']
        . PHP_EOL;

    echo "    SCORE GAP   : "
        . $row['score_gap']
        . PHP_EOL;

    if (empty($row['candidates'])) {

        echo "    CANDIDATES  : NONE\n";

        continue;
    }

    foreach (
        $row['candidates']
        as $candidateIndex => $candidate
    ) {

        echo "    CANDIDATE #"
            . ($candidateIndex + 1)
            . ":\n";

        echo "      ID         : "
            . $candidate['id']
            . PHP_EOL;

        echo "      NAME       : "
            . $candidate['name']
            . PHP_EOL;

        echo "      NAME EN    : "
            . ($candidate['name_en'] ?? '-')
            . PHP_EOL;

        echo "      CODE       : "
            . ($candidate['code'] ?? '-')
            . PHP_EOL;

        echo "      PROVINCE ID: "
            . ($candidate['province_id'] ?? '-')
            . PHP_EOL;

        echo "      CITY       : "
            . ($candidate['city'] ?? '-')
            . PHP_EOL;

        echo "      SCORE      : "
            . $candidate['score']
            . PHP_EOL;

        echo "      CONFIDENCE : "
            . $candidate['confidence']
            . PHP_EOL;

        echo "      BASIS      : "
            . implode(
                ', ',
                $candidate['basis']
            )
            . PHP_EOL;
    }
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$high = 0;
$medium = 0;
$manual = 0;
$none = 0;

$highRecords = 0;
$mediumRecords = 0;
$manualRecords = 0;
$noneRecords = 0;

foreach ($reviewRows as $row) {

    switch ($row['review_status']) {

        case 'HIGH_CANDIDATE':
            $high++;
            $highRecords +=
                $row['records'];
            break;

        case 'MEDIUM_CANDIDATE':
            $medium++;
            $mediumRecords +=
                $row['records'];
            break;

        case 'MANUAL_REVIEW':
            $manual++;
            $manualRecords +=
                $row['records'];
            break;

        default:
            $none++;
            $noneRecords +=
                $row['records'];
            break;
    }
}

echo PHP_EOL;
echo "========================================\n";
echo "TOP 20 REVIEW SUMMARY\n";
echo "========================================\n";

echo "  HIGH CANDIDATE\n";
echo "    NAMES   : {$high}\n";
echo "    RECORDS : {$highRecords}\n";

echo "  MEDIUM CANDIDATE\n";
echo "    NAMES   : {$medium}\n";
echo "    RECORDS : {$mediumRecords}\n";

echo "  MANUAL REVIEW\n";
echo "    NAMES   : {$manual}\n";
echo "    RECORDS : {$manualRecords}\n";

echo "  NO CANDIDATE\n";
echo "    NAMES   : {$none}\n";
echo "    RECORDS : {$noneRecords}\n";

echo PHP_EOL;

echo "TOP 20 RECORDS COVERED : "
    . array_sum(
        array_column(
            $reviewRows,
            'records'
        )
    )
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Database safety
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "TRADE POINT ALIAS REVIEW : COMPLETE\n";
echo "========================================\n";