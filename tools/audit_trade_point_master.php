<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Point Master Audit
|--------------------------------------------------------------------------
|
| Input:
|   trade_point_universe_2019_2026.csv
|
| Output:
|   trade_point_master_review.csv
|
| Database:
|   READ ONLY
|
|--------------------------------------------------------------------------
*/

$base =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA';

$inputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_universe_2019_2026.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_master_review.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeName(?string $value): string
{
    $value = trim((string) $value);

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtoupper($value);
}

/*
|--------------------------------------------------------------------------
| Infer Trade Point Type
|--------------------------------------------------------------------------
|
| Important:
| This is only a candidate suggestion.
| It is NOT yet approved master data.
|
*/

function suggestTradePointType(
    string $tradePoint,
    string $province
): array {
    $text = mb_strtoupper(
        trim($tradePoint)
    );

    /*
    |--------------------------------------------------------------------------
    | AIRPORT
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'AIRPORT')
        ||
        str_contains($text, 'BANDARA')
        ||
        str_contains($text, '(U)')
        ||
        str_contains($text, 'SOEKARNO-HATTA')
        ||
        str_contains($text, 'JUANDA')
        ||
        str_contains($text, 'NGURAH RAI')
        ||
        str_contains($text, 'KUALANAMU')
        ||
        str_contains($text, 'HANG NADIM')
        ||
        str_contains($text, 'SULTAN HASANUDDIN')
    ) {
        return [
            'code' => 'AIRPORT',
            'confidence' => 'HIGH',
            'reason' => 'Airport / aviation indicator',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LAND BORDER
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'BORDER')
        ||
        str_contains($text, 'PERBATASAN')
        ||
        str_contains($text, 'CHECKPOINT')
        ||
        str_contains($text, 'JAGOI BABANG')
        ||
        str_contains($text, 'ENTIKONG')
        ||
        str_contains($text, 'ARUK')
        ||
        str_contains($text, 'BADAI')
        ||
        str_contains($text, 'MOTAAIN')
        ||
        str_contains($text, 'SKOUW')
        ||
        str_contains($text, 'WINI')
    ) {
        return [
            'code' => 'LAND_BORDER',
            'confidence' => 'HIGH',
            'reason' => 'Land border indicator',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SEA PORT
    |--------------------------------------------------------------------------
    */

    if (
        str_contains($text, 'PORT')
        ||
        str_contains($text, 'PELABUHAN')
        ||
        str_contains($text, 'HARBOUR')
        ||
        str_contains($text, 'HARBOR')
        ||
        str_contains($text, 'PORT OF')
        ||
        str_contains($text, 'TANJUNG PRIOK')
        ||
        str_contains($text, 'TANJUNG PERAK')
        ||
        str_contains($text, 'BELAWAN')
        ||
        str_contains($text, 'MAKASSAR')
        ||
        str_contains($text, 'BITUNG')
        ||
        str_contains($text, 'BANYUWANGI')
        ||
        str_contains($text, 'MERAK')
        ||
        str_contains($text, 'DUMAI')
    ) {
        return [
            'code' => 'SEA_PORT',
            'confidence' => 'MEDIUM',
            'reason' => 'Sea port / harbour indicator',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | OTHER
    |--------------------------------------------------------------------------
    */

    return [
        'code' => 'OTHER',
        'confidence' => 'LOW',
        'reason' => 'No reliable type indicator',
    ];
}

/*
|--------------------------------------------------------------------------
| Generate Stable Candidate Code
|--------------------------------------------------------------------------
|
| We use normalized name + province to create a deterministic candidate
| slug. This is NOT yet the final public trade point code.
|
*/

function makeCandidateCode(
    string $province,
    string $tradePoint
): string {
    $value =
        $province
        . '-'
        . $tradePoint;

    $value = preg_replace(
        '/[^A-Z0-9]+/',
        '-',
        mb_strtoupper($value)
    ) ?? '';

    $value = trim(
        $value,
        '-'
    );

    return 'TP-' . $value;
}

/*
|--------------------------------------------------------------------------
| Read Universe
|--------------------------------------------------------------------------
*/

$handle = fopen($inputFile, 'rb');

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$inputFile}"
    );
}

$header = fgetcsv($handle);

if ($header === false) {
    fclose($handle);

    throw new RuntimeException(
        'Header CSV tidak ditemukan.'
    );
}

$columns = [];

foreach ($header as $index => $name) {
    $columns[trim((string) $name)] = $index;
}

$required = [
    'trade_point_source',
    'trade_point_normalized',
    'province_source',
    'province_normalized',
    'first_flow',
    'export_seen',
    'import_seen',
];

foreach ($required as $column) {
    if (!array_key_exists($column, $columns)) {
        fclose($handle);

        throw new RuntimeException(
            "Column tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Process
|--------------------------------------------------------------------------
*/

$rows = [];

$seenCodes = [];

while (($row = fgetcsv($handle)) !== false) {

    $tradePointSource = trim(
        (string) $row[
            $columns['trade_point_source']
        ]
    );

    $tradePoint = normalizeName(
        $row[
            $columns['trade_point_normalized']
        ] ?? ''
    );

    $provinceSource = trim(
        (string) $row[
            $columns['province_source']
        ]
    );

    $province = normalizeName(
        $row[
            $columns['province_normalized']
        ] ?? ''
    );

    if (
        $tradePoint === ''
        ||
        $province === ''
    ) {
        continue;
    }

    $suggestion = suggestTradePointType(
        $tradePoint,
        $province
    );

    $candidateCode = makeCandidateCode(
        $province,
        $tradePoint
    );

    $status = 'REVIEW';

    if (
        $suggestion['confidence'] === 'HIGH'
    ) {
        $status = 'CANDIDATE';
    }

    /*
    |--------------------------------------------------------------------------
    | Detect duplicate candidate code
    |--------------------------------------------------------------------------
    */

    if (isset($seenCodes[$candidateCode])) {
        $status = 'DUPLICATE_CODE';
    } else {
        $seenCodes[$candidateCode] = true;
    }

    $rows[] = [
        'trade_point_source' => $tradePointSource,
        'trade_point_normalized' => $tradePoint,
        'province_source' => $provinceSource,
        'province_normalized' => $province,

        'candidate_code' => $candidateCode,

        'suggested_type' =>
            $suggestion['code'],

        'type_confidence' =>
            $suggestion['confidence'],

        'type_reason' =>
            $suggestion['reason'],

        'export_seen' => (
            (int) (
                $row[
                    $columns['export_seen']
                ]
            )
        ),

        'import_seen' => (
            (int) (
                $row[
                    $columns['import_seen']
                ]
            )
        ),

        'status' => $status,
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort
|--------------------------------------------------------------------------
*/

usort(
    $rows,
    function (array $a, array $b): int {
        $provinceCompare =
            strcmp(
                $a['province_normalized'],
                $b['province_normalized']
            );

        if ($provinceCompare !== 0) {
            return $provinceCompare;
        }

        return strcmp(
            $a['trade_point_normalized'],
            $b['trade_point_normalized']
        );
    }
);

/*
|--------------------------------------------------------------------------
| Write review file
|--------------------------------------------------------------------------
*/

$output = fopen($outputFile, 'wb');

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'trade_point_source',
        'trade_point_normalized',
        'province_source',
        'province_normalized',
        'candidate_code',
        'suggested_type',
        'type_confidence',
        'type_reason',
        'export_seen',
        'import_seen',
        'status',
    ]
);

foreach ($rows as $row) {

    fputcsv(
        $output,
        [
            $row['trade_point_source'],
            $row['trade_point_normalized'],
            $row['province_source'],
            $row['province_normalized'],
            $row['candidate_code'],
            $row['suggested_type'],
            $row['type_confidence'],
            $row['type_reason'],
            $row['export_seen'],
            $row['import_seen'],
            $row['status'],
        ]
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$total = count($rows);

$candidate = 0;
$review = 0;
$duplicate = 0;

$typeSummary = [
    'SEA_PORT' => 0,
    'AIRPORT' => 0,
    'LAND_BORDER' => 0,
    'OTHER' => 0,
];

foreach ($rows as $row) {

    if ($row['status'] === 'CANDIDATE') {
        $candidate++;
    }

    if ($row['status'] === 'REVIEW') {
        $review++;
    }

    if ($row['status'] === 'DUPLICATE_CODE') {
        $duplicate++;
    }

    if (isset($typeSummary[$row['suggested_type']])) {
        $typeSummary[
            $row['suggested_type']
        ]++;
    }
}

/*
|--------------------------------------------------------------------------
| Console Output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX TRADE POINT MASTER AUDIT\n";
echo "========================================\n\n";

echo "TOTAL TRADE POINTS : {$total}\n";

echo "CANDIDATE           : {$candidate}\n";

echo "REVIEW              : {$review}\n";

echo "DUPLICATE CODE      : {$duplicate}\n\n";

echo "SUGGESTED TYPE:\n";

foreach ($typeSummary as $type => $count) {
    echo sprintf(
        "  %-15s : %d\n",
        $type,
        $count
    );
}

echo "\nOUTPUT:\n";
echo $outputFile . "\n";

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";