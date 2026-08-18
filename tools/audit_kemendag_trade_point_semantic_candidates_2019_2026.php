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
echo "DIGESTEX KEMENDAG TRADE POINT SEMANTIC AUDIT\n";
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

function normalizeTp(?string $value): string
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

function tpTokens(?string $value): array
{
    $normalized = normalizeTp($value);

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

function tokenOverlap(
    ?string $left,
    ?string $right
): float {
    $a = tpTokens($left);
    $b = tpTokens($right);

    if (
        empty($a)
        ||
        empty($b)
    ) {
        return 0.0;
    }

    $intersection =
        array_intersect($a, $b);

    $union =
        array_unique(
            array_merge($a, $b)
        );

    if (count($union) === 0) {
        return 0.0;
    }

    return count($intersection)
        / count($union);
}

function containsNormalized(
    ?string $left,
    ?string $right
): bool {
    $a = normalizeTp($left);
    $b = normalizeTp($right);

    if ($a === '' || $b === '') {
        return false;
    }

    return
        str_contains($a, $b)
        ||
        str_contains($b, $a);
}

/*
|--------------------------------------------------------------------------
| Detect trade_points schema
|--------------------------------------------------------------------------
*/

$columns =
    DB::getSchemaBuilder()
        ->getColumnListing('trade_points');

if (empty($columns)) {
    throw new RuntimeException(
        'trade_points table not found.'
    );
}

$nameField =
    in_array('name', $columns, true)
        ? 'name'
        : (
            in_array('trade_point_name', $columns, true)
                ? 'trade_point_name'
                : null
        );

$codeField =
    in_array('code', $columns, true)
        ? 'code'
        : (
            in_array('trade_point_code', $columns, true)
                ? 'trade_point_code'
                : null
        );

$cityField =
    in_array('city', $columns, true)
        ? 'city'
        : null;

$provinceIdField =
    in_array('province_id', $columns, true)
        ? 'province_id'
        : null;

$typeIdField =
    in_array('trade_point_type_id', $columns, true)
        ? 'trade_point_type_id'
        : null;

$activeField =
    in_array('is_active', $columns, true)
        ? 'is_active'
        : null;

if ($nameField === null) {
    throw new RuntimeException(
        'Trade point name field not found.'
    );
}

/*
|--------------------------------------------------------------------------
| Baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->count();

if ($currentNull !== 51525) {
    throw new RuntimeException(
        "Expected 51525 unresolved records, got {$currentNull}."
    );
}

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

/*
|--------------------------------------------------------------------------
| Load active masters
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
    $masterQuery->get();

echo "ACTIVE MASTER TRADE POINTS : "
    . $masters->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Optional type inventory
|--------------------------------------------------------------------------
|
| We inspect trade_point_types if the FK exists.
|--------------------------------------------------------------------------
*/

$typeNames = [];

if ($typeIdField !== null) {

    $typeTableExists =
        DB::getSchemaBuilder()
            ->hasTable('trade_point_types');

    if ($typeTableExists) {

        $typeColumns =
            DB::getSchemaBuilder()
                ->getColumnListing(
                    'trade_point_types'
                );

        $typeNameField =
            in_array('name', $typeColumns, true)
                ? 'name'
                : (
                    in_array('type_name', $typeColumns, true)
                        ? 'type_name'
                        : null
                );

        if ($typeNameField !== null) {

            $types =
                DB::table('trade_point_types')
                    ->get();

            foreach ($types as $type) {

                $id =
                    (int) $type->id;

                $typeNames[$id] =
                    (string) $type->{$typeNameField};
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Top 20 source names
|--------------------------------------------------------------------------
*/

$sources =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
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
| Score semantics
|--------------------------------------------------------------------------
*/

function semanticScore(
    string $sourceName,
    ?string $provinceName,
    ?int $provinceId,
    object $master,
    ?string $nameField,
    ?string $codeField,
    ?string $cityField,
    ?string $provinceIdField,
    ?string $typeIdField
): array {

    $score = 0;
    $reasons = [];

    $sourceNormalized =
        normalizeTp($sourceName);

    $masterName =
        (string) $master->{$nameField};

    $masterCity =
        $cityField !== null
            ? (string) ($master->{$cityField} ?? '')
            : '';

    $masterCode =
        $codeField !== null
            ? (string) ($master->{$codeField} ?? '')
            : '';

    $masterProvinceId =
        $provinceIdField !== null
            ? (int) ($master->{$provinceIdField} ?? 0)
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Exact normalized name
    |--------------------------------------------------------------------------
    */

    if (
        $sourceNormalized !== ''
        &&
        $sourceNormalized ===
            normalizeTp($masterName)
    ) {
        $score += 100;
        $reasons[] = 'EXACT_NAME';
    }

    /*
    |--------------------------------------------------------------------------
    | Source name contains master name
    |--------------------------------------------------------------------------
    */

    if (
        containsNormalized(
            $sourceName,
            $masterName
        )
    ) {
        $score += 40;
        $reasons[] = 'NAME_CONTAINMENT';
    }

    /*
    |--------------------------------------------------------------------------
    | City exact / containment
    |--------------------------------------------------------------------------
    */

    if ($masterCity !== '') {

        $sourceTokens =
            tpTokens(
                $sourceName
            );

        $cityNormalized =
            normalizeTp($masterCity);

        if (
            $sourceNormalized !== ''
            &&
            $sourceNormalized ===
                $cityNormalized
        ) {
            $score += 70;
            $reasons[] = 'EXACT_CITY';
        } elseif (
            containsNormalized(
                $sourceName,
                $masterCity
            )
        ) {
            $score += 45;
            $reasons[] = 'CITY_CONTAINMENT';
        } else {

            $overlap =
                tokenOverlap(
                    $sourceName,
                    $masterCity
                );

            if ($overlap >= 0.50) {
                $score += 30;
                $reasons[] = 'CITY_TOKEN_MATCH';
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Province match
    |--------------------------------------------------------------------------
    */

    if (
        $provinceId !== null
        &&
        $provinceId > 0
        &&
        $masterProvinceId > 0
    ) {

        if (
            $provinceId ===
            $masterProvinceId
        ) {
            $score += 15;
            $reasons[] = 'PROVINCE_MATCH';
        } else {
            $score -= 20;
            $reasons[] = 'PROVINCE_MISMATCH';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Port / airport / border semantic hint
    |--------------------------------------------------------------------------
    */

    $sourceUpper =
        mb_strtoupper(
            $sourceName
        );

    $masterUpper =
        mb_strtoupper(
            $masterName
        );

    $sourceTypeHint = null;
    $masterTypeHint = null;

    if (
        str_contains(
            $sourceUpper,
            'AIRPORT'
        )
    ) {
        $sourceTypeHint = 'AIRPORT';
    } elseif (
        str_contains(
            $sourceUpper,
            'PORT'
        )
    ) {
        $sourceTypeHint = 'PORT';
    } elseif (
        str_contains(
            $sourceUpper,
            'BORDER'
        )
    ) {
        $sourceTypeHint = 'BORDER';
    }

    if (
        str_contains(
            $masterUpper,
            'AIRPORT'
        )
    ) {
        $masterTypeHint = 'AIRPORT';
    } elseif (
        str_contains(
            $masterUpper,
            'PORT'
        )
    ) {
        $masterTypeHint = 'PORT';
    } elseif (
        str_contains(
            $masterUpper,
            'BORDER'
        )
    ) {
        $masterTypeHint = 'BORDER';
    }

    if (
        $sourceTypeHint !== null
        &&
        $masterTypeHint !== null
        &&
        $sourceTypeHint ===
            $masterTypeHint
    ) {
        $score += 15;
        $reasons[] = 'TYPE_HINT_MATCH';
    }

    /*
    |--------------------------------------------------------------------------
    | Master code relation
    |--------------------------------------------------------------------------
    */

    if (
        $masterCode !== ''
        &&
        containsNormalized(
            $sourceName,
            $masterCode
        )
    ) {
        $score += 20;
        $reasons[] = 'CODE_RELATION';
    }

    /*
    |--------------------------------------------------------------------------
    | Confidence
    |--------------------------------------------------------------------------
    */

    $confidence = 'LOW';

    if ($score >= 110) {
        $confidence = 'HIGH';
    } elseif ($score >= 70) {
        $confidence = 'MEDIUM';
    }

    return [
        'score' => $score,
        'confidence' => $confidence,
        'reasons' => $reasons,
    ];
}

/*
|--------------------------------------------------------------------------
| Candidate analysis
|--------------------------------------------------------------------------
*/

$review = [];

foreach ($sources as $source) {

    $sourceName =
        trim(
            (string) $source->port_name
        );

    /*
    |--------------------------------------------------------------------------
    | Dominant province
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
        trim(
            (string) (
                $province?->province_name
                ?? ''
            )
        );

    $candidates = [];

    foreach ($masters as $master) {

        $result =
            semanticScore(
                $sourceName,
                $provinceName,
                $provinceId,
                $master,
                $nameField,
                $codeField,
                $cityField,
                $provinceIdField,
                $typeIdField
            );

        if ($result['score'] <= 0) {
            continue;
        }

        $candidates[] = [
            'id' => (int) $master->id,
            'name' =>
                (string) $master->{$nameField},
            'code' =>
                $codeField !== null
                    ? (string) ($master->{$codeField} ?? '')
                    : '',
            'city' =>
                $cityField !== null
                    ? (string) ($master->{$cityField} ?? '')
                    : '',
            'province_id' =>
                $provinceIdField !== null
                    ? (int) ($master->{$provinceIdField} ?? 0)
                    : null,
            'type_id' =>
                $typeIdField !== null
                    ? (int) ($master->{$typeIdField} ?? 0)
                    : null,
            'type_name' =>
                $typeIdField !== null
                    ? (
                        $typeNames[
                            (int) ($master->{$typeIdField} ?? 0)
                        ]
                        ?? null
                    )
                    : null,
            'score' =>
                $result['score'],
            'confidence' =>
                $result['confidence'],
            'reasons' =>
                $result['reasons'],
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

    $scoreGap =
        $topScore - $secondScore;

    $classification = 'NO_CANDIDATE';

    if (
        $topScore >= 110
        &&
        $scoreGap >= 30
    ) {
        $classification =
            'HIGH_CONFIDENCE';
    } elseif (
        $topScore >= 70
        &&
        $scoreGap >= 20
    ) {
        $classification =
            'MEDIUM_CONFIDENCE';
    } elseif (
        $topScore > 0
    ) {
        $classification =
            'MANUAL_REVIEW';
    }

    $review[] = [
        'source' =>
            $sourceName,

        'records' =>
            (int) $source->records,

        'trade_value' =>
            (float) $source->trade_value,

        'trade_volume' =>
            (float) $source->trade_volume,

        'province' =>
            $provinceName,

        'province_id' =>
            $provinceId,

        'classification' =>
            $classification,

        'top_score' =>
            $topScore,

        'score_gap' =>
            $scoreGap,

        'candidates' =>
            $candidates,
    ];
}

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SEMANTIC TRADE POINT REVIEW\n";
echo "========================================\n";

foreach ($review as $index => $item) {

    echo PHP_EOL;

    echo sprintf(
        "%02d. SOURCE       : %s\n",
        $index + 1,
        $item['source']
    );

    echo "    RECORDS      : "
        . number_format($item['records'])
        . PHP_EOL;

    echo "    VALUE        : "
        . number_format(
            $item['trade_value'],
            3,
            '.',
            ''
        )
        . PHP_EOL;

    echo "    PROVINCE     : "
        . (
            $item['province'] !== ''
                ? $item['province']
                : '-'
        )
        . " ["
        . (
            $item['province_id'] !== null
                ? $item['province_id']
                : '-'
        )
        . "]\n";

    echo "    CLASS        : "
        . $item['classification']
        . PHP_EOL;

    echo "    TOP SCORE    : "
        . $item['top_score']
        . PHP_EOL;

    echo "    SCORE GAP    : "
        . $item['score_gap']
        . PHP_EOL;

    if (empty($item['candidates'])) {
        echo "    CANDIDATES   : NONE\n";
        continue;
    }

    foreach (
        $item['candidates']
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

        echo "      CODE       : "
            . (
                $candidate['code'] !== ''
                    ? $candidate['code']
                    : '-'
            )
            . PHP_EOL;

        echo "      CITY       : "
            . (
                $candidate['city'] !== ''
                    ? $candidate['city']
                    : '-'
            )
            . PHP_EOL;

        echo "      PROVINCE ID: "
            . (
                $candidate['province_id']
                    ?? '-'
            )
            . PHP_EOL;

        echo "      TYPE       : "
            . (
                $candidate['type_name']
                    ?? (
                        $candidate['type_id']
                        ?? '-'
                    )
            )
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
                $candidate['reasons']
            )
            . PHP_EOL;
    }
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$highNames = 0;
$mediumNames = 0;
$manualNames = 0;
$noneNames = 0;

$highRecords = 0;
$mediumRecords = 0;
$manualRecords = 0;
$noneRecords = 0;

foreach ($review as $item) {

    switch ($item['classification']) {

        case 'HIGH_CONFIDENCE':
            $highNames++;
            $highRecords += $item['records'];
            break;

        case 'MEDIUM_CONFIDENCE':
            $mediumNames++;
            $mediumRecords += $item['records'];
            break;

        case 'MANUAL_REVIEW':
            $manualNames++;
            $manualRecords += $item['records'];
            break;

        default:
            $noneNames++;
            $noneRecords += $item['records'];
            break;
    }
}

echo PHP_EOL;
echo "========================================\n";
echo "SEMANTIC CLASSIFICATION SUMMARY\n";
echo "========================================\n";

echo "  HIGH CONFIDENCE\n";
echo "    NAMES   : {$highNames}\n";
echo "    RECORDS : {$highRecords}\n";

echo "  MEDIUM CONFIDENCE\n";
echo "    NAMES   : {$mediumNames}\n";
echo "    RECORDS : {$mediumRecords}\n";

echo "  MANUAL REVIEW\n";
echo "    NAMES   : {$manualNames}\n";
echo "    RECORDS : {$manualRecords}\n";

echo "  NO CANDIDATE\n";
echo "    NAMES   : {$noneNames}\n";
echo "    RECORDS : {$noneRecords}\n";

echo PHP_EOL;

echo "TOP 20 RECORDS COVERED : "
    . array_sum(
        array_column(
            $review,
            'records'
        )
    )
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Safety
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
echo "SEMANTIC TRADE POINT AUDIT : COMPLETE\n";
echo "========================================\n";