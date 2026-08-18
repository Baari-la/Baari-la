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
echo "DIGESTEX KEMENDAG TRADE POINT CANDIDATE AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

function normalizeTradePoint(?string $value): string
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

function tokenizeTradePoint(?string $value): array
{
    $normalized =
        normalizeTradePoint($value);

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

function firstExistingField(
    array $columns,
    array $candidates
): ?string {
    foreach ($candidates as $candidate) {
        if (in_array($candidate, $columns, true)) {
            return $candidate;
        }
    }

    return null;
}

function rowValue(
    object $row,
    ?string $field
): ?string {
    if ($field === null) {
        return null;
    }

    return property_exists($row, $field)
        ? (string) $row->{$field}
        : null;
}

/*
|--------------------------------------------------------------------------
| Inspect trade_points structure
|--------------------------------------------------------------------------
*/

$tradePointColumns =
    DB::getSchemaBuilder()
        ->getColumnListing('trade_points');

if (empty($tradePointColumns)) {
    throw new RuntimeException(
        'trade_points table tidak ditemukan atau tidak memiliki columns.'
    );
}

echo "========================================\n";
echo "TRADE POINT MASTER SCHEMA DETECTION\n";
echo "========================================\n";

echo "  COLUMNS:\n";

foreach ($tradePointColumns as $column) {
    echo "    - {$column}\n";
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Detect likely master fields
|--------------------------------------------------------------------------
*/

$nameField = firstExistingField(
    $tradePointColumns,
    [
        'name',
        'trade_point_name',
        'port_name',
        'point_name',
        'nama',
        'nama_pelabuhan',
        'pelabuhan',
    ]
);

$codeField = firstExistingField(
    $tradePointColumns,
    [
        'code',
        'trade_point_code',
        'port_code',
        'point_code',
        'kode',
        'kode_pelabuhan',
    ]
);

$provinceIdField = firstExistingField(
    $tradePointColumns,
    [
        'province_id',
    ]
);

$provinceNameField = firstExistingField(
    $tradePointColumns,
    [
        'province_name',
        'provinsi',
        'nama_provinsi',
    ]
);

$activeField = firstExistingField(
    $tradePointColumns,
    [
        'is_active',
        'active',
    ]
);

echo "DETECTED FIELDS:\n";
echo "  NAME         : "
    . ($nameField ?? 'NOT FOUND')
    . PHP_EOL;

echo "  CODE         : "
    . ($codeField ?? 'NOT FOUND')
    . PHP_EOL;

echo "  PROVINCE ID  : "
    . ($provinceIdField ?? 'NOT FOUND')
    . PHP_EOL;

echo "  PROVINCE NAME: "
    . ($provinceNameField ?? 'NOT FOUND')
    . PHP_EOL;

echo "  ACTIVE       : "
    . ($activeField ?? 'NOT FOUND')
    . PHP_EOL;

if ($nameField === null) {
    throw new RuntimeException(
        'Tidak dapat menemukan field nama pada trade_points.'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Load master trade points
|--------------------------------------------------------------------------
*/

$masterQuery =
    DB::table('trade_points');

if ($activeField !== null) {
    $masterQuery->where(
        $activeField,
        1
    );
}

$masters =
    $masterQuery
        ->get();

echo "========================================\n";
echo "TRADE POINT MASTER INVENTORY\n";
echo "========================================\n";

echo "  ACTIVE MASTER TRADE POINTS : "
    . $masters->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Load unresolved source names with province context
|--------------------------------------------------------------------------
*/

$currentNull =
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
        ->count();

if ($currentNull !== 51525) {
    throw new RuntimeException(
        "Unexpected unresolved Trade Point count. Expected 51525, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Build unresolved source groups
|--------------------------------------------------------------------------
*/

$sourceRows =
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
        ->whereNotNull('port_name')
        ->where(
            'port_name',
            '<>',
            ''
        )
        ->selectRaw(
            '
            port_name,
            province_name,
            province_id,
            COUNT(*) AS records,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume
            '
        )
        ->groupBy(
            'port_name',
            'province_name',
            'province_id'
        )
        ->orderByDesc('records')
        ->get();

/*
|--------------------------------------------------------------------------
| Collapse same port across province contexts
|--------------------------------------------------------------------------
*/

$sources = [];

foreach ($sourceRows as $row) {

    $portName =
        trim((string) $row->port_name);

    if (!isset($sources[$portName])) {

        $sources[$portName] = [
            'records' => 0,
            'trade_value' => 0.0,
            'trade_volume' => 0.0,
            'provinces' => [],
        ];
    }

    $sources[$portName]['records'] +=
        (int) $row->records;

    $sources[$portName]['trade_value'] +=
        (float) $row->trade_value;

    $sources[$portName]['trade_volume'] +=
        (float) $row->trade_volume;

    $provinceKey =
        (string) ($row->province_id ?? '');

    $provinceName =
        trim((string) ($row->province_name ?? ''));

    if (!isset(
        $sources[$portName]['provinces'][$provinceKey]
    )) {
        $sources[$portName]['provinces'][$provinceKey] = [
            'province_id' => $row->province_id,
            'province_name' => $provinceName,
            'records' => 0,
        ];
    }

    $sources[$portName]['provinces'][$provinceKey]['records'] +=
        (int) $row->records;
}

echo "========================================\n";
echo "UNRESOLVED TRADE POINT SOURCE INVENTORY\n";
echo "========================================\n";

echo "  DISTINCT SOURCE NAMES : "
    . count($sources)
    . PHP_EOL;

echo "  TOTAL NULL RECORDS    : "
    . $currentNull
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate scoring
|--------------------------------------------------------------------------
*/

function scoreTradePointCandidate(
    string $sourceName,
    ?string $sourceProvinceName,
    ?int $sourceProvinceId,
    object $master,
    ?string $nameField,
    ?string $codeField,
    ?string $provinceIdField,
    ?string $provinceNameField
): array {

    $sourceNormalized =
        normalizeTradePoint($sourceName);

    $masterName =
        rowValue(
            $master,
            $nameField
        );

    $masterCode =
        rowValue(
            $master,
            $codeField
        );

    $masterProvinceId =
        $provinceIdField !== null
            ? (int) (
                rowValue(
                    $master,
                    $provinceIdField
                ) ?? 0
            )
            : null;

    $masterProvinceName =
        rowValue(
            $master,
            $provinceNameField
        );

    $masterNormalized =
        normalizeTradePoint($masterName);

    $score = 0;
    $reasons = [];

    /*
    |--------------------------------------------------------------------------
    | Exact normalized name
    |--------------------------------------------------------------------------
    */

    if (
        $sourceNormalized !== ''
        &&
        $masterNormalized !== ''
        &&
        $sourceNormalized === $masterNormalized
    ) {
        $score += 100;
        $reasons[] = 'EXACT_NORMALIZED_NAME';
    }

    /*
    |--------------------------------------------------------------------------
    | Containment
    |--------------------------------------------------------------------------
    */

    if (
        $sourceNormalized !== ''
        &&
        $masterNormalized !== ''
        &&
        (
            str_contains(
                $sourceNormalized,
                $masterNormalized
            )
            ||
            str_contains(
                $masterNormalized,
                $sourceNormalized
            )
        )
    ) {
        $score += 60;
        $reasons[] = 'NAME_CONTAINS';
    }

    /*
    |--------------------------------------------------------------------------
    | Token overlap
    |--------------------------------------------------------------------------
    */

    $sourceTokens =
        tokenizeTradePoint(
            $sourceName
        );

    $masterTokens =
        tokenizeTradePoint(
            $masterName
        );

    if (
        !empty($sourceTokens)
        &&
        !empty($masterTokens)
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
                ? count($intersection) / count($union)
                : 0;

        if ($jaccard >= 0.75) {
            $score += 45;
            $reasons[] = 'HIGH_TOKEN_OVERLAP';
        } elseif ($jaccard >= 0.50) {
            $score += 25;
            $reasons[] = 'MEDIUM_TOKEN_OVERLAP';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Code relationship
    |--------------------------------------------------------------------------
    */

    if (
        $masterCode !== null
        &&
        $masterCode !== ''
    ) {

        $masterCodeNormalized =
            normalizeTradePoint(
                $masterCode
            );

        if (
            $masterCodeNormalized !== ''
            &&
            (
                str_contains(
                    $sourceNormalized,
                    $masterCodeNormalized
                )
                ||
                str_contains(
                    $masterCodeNormalized,
                    $sourceNormalized
                )
            )
        ) {
            $score += 30;
            $reasons[] = 'CODE_RELATIONSHIP';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Province constraint
    |--------------------------------------------------------------------------
    */

    if (
        $sourceProvinceId !== null
        &&
        $sourceProvinceId > 0
        &&
        $masterProvinceId !== null
        &&
        $masterProvinceId > 0
    ) {

        if (
            $sourceProvinceId ===
            $masterProvinceId
        ) {
            $score += 35;
            $reasons[] = 'PROVINCE_ID_MATCH';
        } else {
            $score -= 30;
            $reasons[] = 'PROVINCE_ID_MISMATCH';
        }
    }

    if (
        $sourceProvinceName !== null
        &&
        trim($sourceProvinceName) !== ''
        &&
        $masterProvinceName !== null
        &&
        trim($masterProvinceName) !== ''
    ) {

        if (
            normalizeTradePoint(
                $sourceProvinceName
            )
            ===
            normalizeTradePoint(
                $masterProvinceName
            )
        ) {
            $score += 20;
            $reasons[] = 'PROVINCE_NAME_MATCH';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Confidence classification
    |--------------------------------------------------------------------------
    */

    $confidence = 'LOW';

    if ($score >= 100) {
        $confidence = 'HIGH';
    } elseif ($score >= 65) {
        $confidence = 'MEDIUM';
    }

    return [
        'score' => $score,
        'confidence' => $confidence,
        'reasons' => $reasons,
        'master_name' => $masterName,
        'master_code' => $masterCode,
        'master_province_id' => $masterProvinceId,
        'master_province_name' => $masterProvinceName,
    ];
}

/*
|--------------------------------------------------------------------------
| Build candidates for each source
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TRADE POINT CANDIDATE ANALYSIS\n";
echo "========================================\n";

$candidateSummary = [];

foreach ($sources as $sourceName => $source) {

    $bestCandidates = [];

    /*
    |--------------------------------------------------------------------------
    | Use dominant province context
    |--------------------------------------------------------------------------
    */

    uasort(
        $source['provinces'],
        static function (
            array $a,
            array $b
        ): int {
            return
                $b['records']
                <=>
                $a['records'];
        }
    );

    $dominantProvince =
        reset(
            $source['provinces']
        );

    $sourceProvinceId =
        $dominantProvince
            ? (
                $dominantProvince['province_id'] !== null
                    ? (int) $dominantProvince['province_id']
                    : null
            )
            : null;

    $sourceProvinceName =
        $dominantProvince
            ? $dominantProvince['province_name']
            : null;

    foreach ($masters as $master) {

        $result =
            scoreTradePointCandidate(
                $sourceName,
                $sourceProvinceName,
                $sourceProvinceId,
                $master,
                $nameField,
                $codeField,
                $provinceIdField,
                $provinceNameField
            );

        if ($result['score'] <= 0) {
            continue;
        }

        $bestCandidates[] = [
            'master_id' => $master->id,
            'score' => $result['score'],
            'confidence' => $result['confidence'],
            'name' => $result['master_name'],
            'code' => $result['master_code'],
            'province_id' =>
                $result['master_province_id'],
            'province_name' =>
                $result['master_province_name'],
            'reasons' =>
                $result['reasons'],
        ];
    }

    usort(
        $bestCandidates,
        static function (
            array $a,
            array $b
        ): int {
            return $b['score'] <=> $a['score'];
        }
    );

    $bestCandidates =
        array_slice(
            $bestCandidates,
            0,
            3
        );

    $topScore =
        $bestCandidates[0]['score']
        ?? 0;

    $secondScore =
        $bestCandidates[1]['score']
        ?? 0;

    $gap =
        $topScore - $secondScore;

    $classification = 'NO_CANDIDATE';

    if ($topScore >= 100 && $gap >= 20) {
        $classification = 'HIGH_CONFIDENCE';
    } elseif ($topScore >= 65 && $gap >= 15) {
        $classification = 'MEDIUM_CONFIDENCE';
    } elseif ($topScore > 0) {
        $classification = 'AMBIGUOUS';
    }

    $candidateSummary[$sourceName] = [
        'records' =>
            $source['records'],

        'trade_value' =>
            $source['trade_value'],

        'trade_volume' =>
            $source['trade_volume'],

        'province_name' =>
            $sourceProvinceName,

        'province_id' =>
            $sourceProvinceId,

        'classification' =>
            $classification,

        'candidates' =>
            $bestCandidates,

        'top_score' =>
            $topScore,

        'score_gap' =>
            $gap,
    ];
}

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

foreach ($candidateSummary as $sourceName => $data) {

    echo PHP_EOL;

    echo sprintf(
        "SOURCE: %-45s | RECORDS=%d\n",
        $sourceName,
        $data['records']
    );

    echo "  PROVINCE   : "
        . ($data['province_name'] ?? '-')
        . " ["
        . ($data['province_id'] ?? '-')
        . "]\n";

    echo "  CLASS      : "
        . $data['classification']
        . PHP_EOL;

    echo "  TOP SCORE  : "
        . $data['top_score']
        . " | GAP="
        . $data['score_gap']
        . PHP_EOL;

    if (empty($data['candidates'])) {

        echo "  CANDIDATE  : NONE\n";
        continue;
    }

    foreach (
        $data['candidates']
        as $index => $candidate
    ) {

        echo "  #"
            . ($index + 1)
            . " -> ID="
            . $candidate['master_id']
            . " | "
            . ($candidate['name'] ?? '-')
            . " | CODE="
            . ($candidate['code'] ?? '-')
            . " | SCORE="
            . $candidate['score']
            . " | "
            . $candidate['confidence']
            . PHP_EOL;

        echo "       PROVINCE="
            . ($candidate['province_name'] ?? '-')
            . " ["
            . ($candidate['province_id'] ?? '-')
            . "]\n";

        echo "       REASON="
            . implode(
                ',',
                $candidate['reasons']
            )
            . PHP_EOL;
    }
}

/*
|--------------------------------------------------------------------------
| Aggregate classification
|--------------------------------------------------------------------------
*/

$high = 0;
$medium = 0;
$ambiguous = 0;
$none = 0;

$highRecords = 0;
$mediumRecords = 0;
$ambiguousRecords = 0;
$noneRecords = 0;

foreach ($candidateSummary as $data) {

    switch ($data['classification']) {

        case 'HIGH_CONFIDENCE':
            $high++;
            $highRecords += $data['records'];
            break;

        case 'MEDIUM_CONFIDENCE':
            $medium++;
            $mediumRecords += $data['records'];
            break;

        case 'AMBIGUOUS':
            $ambiguous++;
            $ambiguousRecords += $data['records'];
            break;

        default:
            $none++;
            $noneRecords += $data['records'];
            break;
    }
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "CANDIDATE CLASSIFICATION SUMMARY\n";
echo "========================================\n";

echo "  HIGH CONFIDENCE\n";
echo "    NAMES   : {$high}\n";
echo "    RECORDS : {$highRecords}\n";

echo "  MEDIUM CONFIDENCE\n";
echo "    NAMES   : {$medium}\n";
echo "    RECORDS : {$mediumRecords}\n";

echo "  AMBIGUOUS\n";
echo "    NAMES   : {$ambiguous}\n";
echo "    RECORDS : {$ambiguousRecords}\n";

echo "  NO CANDIDATE\n";
echo "    NAMES   : {$none}\n";
echo "    RECORDS : {$noneRecords}\n";

echo PHP_EOL;

echo "TOTAL SOURCE NAMES : "
    . count($candidateSummary)
    . PHP_EOL;

echo "TOTAL RECORDS      : "
    . (
        $highRecords
        + $mediumRecords
        + $ambiguousRecords
        + $noneRecords
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

echo PHP_EOL;

echo "========================================\n";
echo "TRADE POINT CANDIDATE AUDIT : COMPLETE\n";
echo "========================================\n";