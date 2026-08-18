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
echo "DIGESTEX KEMENDAG COUNTRY RESOLUTION AUDIT\n";
echo "2024 + 2025 IMPORT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2024-2025\n";
echo "  TRADE FLOW  : IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  PURPOSE     : COUNTRY RESOLUTION GOVERNANCE\n\n";

/*
|--------------------------------------------------------------------------
| Known governance rules
|--------------------------------------------------------------------------
|
| These are classifications already established during the audit.
|--------------------------------------------------------------------------
*/

$specialNomenclature = [
    'INDONESIA (BATAM)',
];

$territoryDependency = [
    'NETHERLANDS ANTILLES',
];

/*
|--------------------------------------------------------------------------
| Source residual labels
|--------------------------------------------------------------------------
*/

$residuals = DB::table('trade_statistics')
    ->whereIn('year', [2024, 2025])
    ->where('trade_flow', 'import')
    ->whereNull('country_id')
    ->whereNotNull('country_name')
    ->select('country_name')
    ->selectRaw('COUNT(*) AS records')
    ->groupBy('country_name')
    ->orderByDesc('records')
    ->get();

$totalResidualRecords = (int) $residuals->sum(
    static function ($row): int {
        return (int) $row->records;
    }
);

echo "========================================\n";
echo "RESIDUAL COUNTRY SUMMARY\n";
echo "========================================\n";

echo "  UNIQUE SOURCE LABELS : "
    . $residuals->count()
    . PHP_EOL;

echo "  TOTAL RESIDUAL      : "
    . $totalResidualRecords
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Active master countries
|--------------------------------------------------------------------------
*/

$masterCountries = DB::table('mst_countries')
    ->where('is_active', 1)
    ->select(
        'id',
        'country_code',
        'iso3',
        'country_name_en',
        'country_name_id',
        'official_name'
    )
    ->get();

echo "  ACTIVE MASTER COUNTRIES : "
    . $masterCountries->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Normalization helpers
|--------------------------------------------------------------------------
*/

$normalize = static function (?string $value): string {

    $value = trim((string) $value);

    $value = strtoupper($value);

    $value = str_replace(
        [
            '.',
            ',',
            '(',
            ')',
            '/',
            '-',
            '&',
        ],
        ' ',
        $value
    );

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    );

    return trim((string) $value);
};

/*
|--------------------------------------------------------------------------
| Canonical master lookup
|--------------------------------------------------------------------------
*/

$normalizedMaster = [];

foreach ($masterCountries as $country) {

    $fields = [
        $country->country_name_en,
        $country->country_name_id,
        $country->official_name,
        $country->country_code,
        $country->iso3,
    ];

    foreach ($fields as $field) {

        $normalized = $normalize($field);

        if ($normalized === '') {
            continue;
        }

        if (!isset($normalizedMaster[$normalized])) {

            $normalizedMaster[$normalized] = $country;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Known explicit source mappings
|--------------------------------------------------------------------------
|
| Only mappings supported by evidence already established in the audit.
|--------------------------------------------------------------------------
*/

$explicitMappings = [
    'KEPULAUAN SALOMON' => 103,
    'FD STS MICRONESIA' => 109,
];

/*
|--------------------------------------------------------------------------
| Results
|--------------------------------------------------------------------------
*/

$results = [];

$classificationCounts = [
    'SPECIAL NOMENCLATURE' => 0,
    'TERRITORY / DEPENDENCY' => 0,
    'RESOLVED COUNTRY' => 0,
    'MASTER GAP / REVIEW' => 0,
];

/*
|--------------------------------------------------------------------------
| Country resolution
|--------------------------------------------------------------------------
*/

foreach ($residuals as $row) {

    $sourceName = trim((string) $row->country_name);
    $records = (int) $row->records;

    $classification = 'MASTER GAP / REVIEW';
    $targetCountryId = null;
    $targetCountry = null;
    $evidence = 'NO SAFE MASTER MATCH';
    $decision = 'REVIEW';

    /*
    |--------------------------------------------------------------------------
    | Special nomenclature
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $sourceName,
            $specialNomenclature,
            true
        )
    ) {

        $classification = 'SPECIAL NOMENCLATURE';
        $evidence = 'GOVERNANCE RULE';
        $decision = 'KEEP SPECIAL LABEL';
    }

    /*
    |--------------------------------------------------------------------------
    | Territory / dependency
    |--------------------------------------------------------------------------
    */

    elseif (
        in_array(
            $sourceName,
            $territoryDependency,
            true
        )
    ) {

        $classification = 'TERRITORY / DEPENDENCY';
        $evidence = 'GOVERNANCE RULE';
        $decision = 'KEEP FOR TERRITORY GOVERNANCE';
    }

    /*
    |--------------------------------------------------------------------------
    | Explicit safe mappings
    |--------------------------------------------------------------------------
    */

    elseif (
        isset($explicitMappings[$sourceName])
    ) {

        $targetCountryId =
            (int) $explicitMappings[$sourceName];

        $target = $masterCountries->first(
            static function ($country) use ($targetCountryId): bool {
                return (int) $country->id === $targetCountryId;
            }
        );

        if ($target !== null) {

            $classification = 'RESOLVED COUNTRY';
            $targetCountry = $target;
            $evidence = 'EXPLICIT VERIFIED MASTER MAPPING';
            $decision = 'ELIGIBLE FOR DRY-RUN';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Exact normalized master match
    |--------------------------------------------------------------------------
    */

    else {

        $normalizedSource =
            $normalize($sourceName);

        if (
            $normalizedSource !== ''
            &&
            isset(
                $normalizedMaster[$normalizedSource]
            )
        ) {

            $target =
                $normalizedMaster[$normalizedSource];

            $targetCountryId =
                (int) $target->id;

            $targetCountry = $target;

            $classification = 'RESOLVED COUNTRY';
            $evidence = 'NORMALIZED MASTER MATCH';
            $decision = 'ELIGIBLE FOR DRY-RUN';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregate classification
    |--------------------------------------------------------------------------
    */

    $classificationCounts[$classification]++;

    $results[] = [
        'source_name' => $sourceName,
        'records' => $records,
        'classification' => $classification,
        'target_country_id' => $targetCountryId,
        'target_country' => $targetCountry,
        'evidence' => $evidence,
        'decision' => $decision,
    ];
}

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "COUNTRY RESOLUTION RESULTS\n";
echo "========================================\n";

foreach ($results as $index => $result) {

    $targetLabel = '-';

    if ($result['target_country'] !== null) {

        $target = $result['target_country'];

        $targetLabel =
            '#'
            . $result['target_country_id']
            . ' '
            . $target->country_name_en
            . ' ['
            . $target->country_code
            . '/'
            . $target->iso3
            . ']';
    }

    echo sprintf(
        "%02d. %-35s | %5d | %-22s | TARGET=%-45s | %s\n",
        $index + 1,
        mb_strimwidth(
            $result['source_name'],
            0,
            35,
            ''
        ),
        $result['records'],
        $result['classification'],
        mb_strimwidth(
            $targetLabel,
            0,
            45,
            ''
        ),
        $result['decision']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Classification summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CLASSIFICATION SUMMARY\n";
echo "========================================\n";

foreach ($classificationCounts as $classification => $count) {

    echo sprintf(
        "  %-22s : %d labels\n",
        $classification,
        $count
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Record impact summary
|--------------------------------------------------------------------------
*/

$recordImpact = [
    'SPECIAL NOMENCLATURE' => 0,
    'TERRITORY / DEPENDENCY' => 0,
    'RESOLVED COUNTRY' => 0,
    'MASTER GAP / REVIEW' => 0,
];

foreach ($results as $result) {

    $recordImpact[
        $result['classification']
    ] += $result['records'];
}

echo "========================================\n";
echo "RECORD IMPACT SUMMARY\n";
echo "========================================\n";

foreach ($recordImpact as $classification => $records) {

    echo sprintf(
        "  %-22s : %5d records\n",
        $classification,
        $records
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolvable candidates
|--------------------------------------------------------------------------
*/

$resolvedResults = array_values(
    array_filter(
        $results,
        static function (array $result): bool {
            return $result['classification']
                === 'RESOLVED COUNTRY';
        }
    )
);

echo "========================================\n";
echo "SAFE RESOLUTION CANDIDATES\n";
echo "========================================\n";

if (empty($resolvedResults)) {

    echo "  NONE\n";

} else {

    foreach ($resolvedResults as $result) {

        $target = $result['target_country'];

        echo sprintf(
            "  %-35s | %5d records | country_id=%d | %s | EVIDENCE=%s\n",
            $result['source_name'],
            $result['records'],
            $result['target_country_id'],
            $target->country_name_en,
            $result['evidence']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Review candidates
|--------------------------------------------------------------------------
*/

$reviewResults = array_values(
    array_filter(
        $results,
        static function (array $result): bool {
            return $result['classification']
                === 'MASTER GAP / REVIEW';
        }
    )
);

echo "========================================\n";
echo "MASTER GAP / REVIEW CANDIDATES\n";
echo "========================================\n";

if (empty($reviewResults)) {

    echo "  NONE\n";

} else {

    foreach ($reviewResults as $result) {

        echo sprintf(
            "  %-35s | %5d records | %s\n",
            $result['source_name'],
            $result['records'],
            $result['evidence']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final governance summary
|--------------------------------------------------------------------------
*/

$specialRecords =
    $recordImpact['SPECIAL NOMENCLATURE'];

$territoryRecords =
    $recordImpact['TERRITORY / DEPENDENCY'];

$resolvedRecords =
    $recordImpact['RESOLVED COUNTRY'];

$reviewRecords =
    $recordImpact['MASTER GAP / REVIEW'];

$totalClassifiedRecords =
    $specialRecords
    +
    $territoryRecords
    +
    $resolvedRecords
    +
    $reviewRecords;

$recordConservationPass =
    $totalClassifiedRecords
    ===
    $totalResidualRecords;

/*
|--------------------------------------------------------------------------
| Database safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL COUNTRY GOVERNANCE GATE\n";
echo "========================================\n";

echo "  RESIDUAL RECORDS       : {$totalResidualRecords}\n";
echo "  SPECIAL NOMENCLATURE   : {$specialRecords}\n";
echo "  TERRITORY / DEPENDENCY : {$territoryRecords}\n";
echo "  RESOLVED COUNTRY       : {$resolvedRecords}\n";
echo "  MASTER GAP / REVIEW    : {$reviewRecords}\n";

echo "  RECORD CONSERVATION    : "
    . ($recordConservationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  DATABASE MODIFIED      : NO\n";

if ($recordConservationPass) {

    echo "  COUNTRY GOVERNANCE AUDIT : PASS\n";

} else {

    echo "  COUNTRY GOVERNANCE AUDIT : REVIEW\n";
}

echo "========================================\n";
echo "COUNTRY RESOLUTION AUDIT : COMPLETE\n";
echo "========================================\n";

if (!$recordConservationPass) {
    exit(1);
}