<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| DIGESTEX TRADE COUNTRY MASTER GAP AUDIT
|--------------------------------------------------------------------------
|
| INPUT:
|   dry_run_export_2019.csv
|   trade_country_alias_review_2019.csv
|
| MASTER:
|   mst_countries
|
| DATABASE:
|   READ ONLY
|--------------------------------------------------------------------------
*/

$base =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA';

$dryRunFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'dry_run_export_2019.csv';

$aliasReviewFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_alias_review_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_master_gap_review_2019.csv';

foreach ([
    $dryRunFile,
    $aliasReviewFile,
] as $file) {

    if (!is_file($file)) {
        throw new RuntimeException(
            "File tidak ditemukan:\n{$file}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeCountry(string $value): string
{
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtoupper($value);
}

/*
|--------------------------------------------------------------------------
| Load master countries
|--------------------------------------------------------------------------
*/

$countryRows = DB::table('mst_countries')
    ->where('is_active', true)
    ->get([
        'id',
        'country_code',
        'iso3',
        'country_name_en',
        'country_name_id',
    ]);

if ($countryRows->isEmpty()) {
    throw new RuntimeException(
        'mst_countries kosong.'
    );
}

/*
|--------------------------------------------------------------------------
| Lookup master
|--------------------------------------------------------------------------
*/

$countryByCode = [];
$countryByIso3 = [];
$countryByName = [];

foreach ($countryRows as $country) {

    $code = strtoupper(
        trim(
            (string) $country->country_code
        )
    );

    $iso3 = strtoupper(
        trim(
            (string) $country->iso3
        )
    );

    if ($code !== '') {
        $countryByCode[$code] = $country;
    }

    if ($iso3 !== '') {
        $countryByIso3[$iso3] = $country;
    }

    foreach ([
        $country->country_name_en,
        $country->country_name_id,
    ] as $name) {

        $normalized =
            normalizeCountry(
                (string) $name
            );

        if ($normalized !== '') {
            $countryByName[$normalized] = $country;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Load candidate suggestions from alias audit
|--------------------------------------------------------------------------
*/

$aliasHandle = fopen(
    $aliasReviewFile,
    'rb'
);

if ($aliasHandle === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$aliasReviewFile}"
    );
}

$aliasHeader = fgetcsv(
    $aliasHandle
);

if ($aliasHeader === false) {
    fclose($aliasHandle);

    throw new RuntimeException(
        'Header alias review tidak ditemukan.'
    );
}

$aliasColumns = [];

foreach (
    $aliasHeader as $index => $name
) {
    $aliasColumns[
        trim((string) $name)
    ] = $index;
}

$requiredAliasColumns = [
    'country_source',
    'country_normalized',
    'monthly_occurrence_count',
    'suggested_country_code',
    'suggested_country_name_en',
    'confidence',
    'status',
];

foreach ($requiredAliasColumns as $column) {

    if (!isset($aliasColumns[$column])) {
        fclose($aliasHandle);

        throw new RuntimeException(
            "Column alias review tidak ditemukan: {$column}"
        );
    }
}

$candidateLookup = [];

while (($row = fgetcsv($aliasHandle)) !== false) {

    $source = trim(
        (string) $row[
            $aliasColumns['country_source']
        ]
    );

    $normalized = trim(
        (string) $row[
            $aliasColumns['country_normalized']
        ]
    );

    if ($normalized === '') {
        continue;
    }

    $suggestedCode = strtoupper(
        trim(
            (string) $row[
                $aliasColumns['suggested_country_code']
            ]
        )
    );

    $candidateLookup[$normalized] = [
        'suggested_code' =>
            $suggestedCode,

        'suggested_name_en' =>
            trim(
                (string) $row[
                    $aliasColumns[
                        'suggested_country_name_en'
                    ]
                ]
            ),

        'confidence' =>
            trim(
                (string) $row[
                    $aliasColumns['confidence']
                ]
            ),

        'audit_status' =>
            trim(
                (string) $row[
                    $aliasColumns['status']
                ]
            ),
    ];
}

fclose($aliasHandle);

/*
|--------------------------------------------------------------------------
| Read dry-run unresolved countries
|--------------------------------------------------------------------------
*/

$dryHandle = fopen(
    $dryRunFile,
    'rb'
);

if ($dryHandle === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$dryRunFile}"
    );
}

$dryHeader = fgetcsv(
    $dryHandle
);

if ($dryHeader === false) {
    fclose($dryHandle);

    throw new RuntimeException(
        'Header dry-run tidak ditemukan.'
    );
}

$dryColumns = [];

foreach (
    $dryHeader as $index => $name
) {
    $dryColumns[
        trim((string) $name)
    ] = $index;
}

foreach ([
    'COUNTRY_SOURCE',
    'COUNTRY_STATUS',
] as $column) {

    if (!isset($dryColumns[$column])) {
        fclose($dryHandle);

        throw new RuntimeException(
            "Column dry-run tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Aggregate unresolved source names
|--------------------------------------------------------------------------
*/

$sourceStats = [];

while (($row = fgetcsv($dryHandle)) !== false) {

    $status = trim(
        (string) $row[
            $dryColumns['COUNTRY_STATUS']
        ]
    );

    if ($status !== 'UNRESOLVED') {
        continue;
    }

    $source = trim(
        (string) $row[
            $dryColumns['COUNTRY_SOURCE']
        ]
    );

    $normalized =
        normalizeCountry($source);

    if ($normalized === '') {
        continue;
    }

    if (!isset($sourceStats[$normalized])) {

        $sourceStats[$normalized] = [
            'country_source' =>
                $source,

            'country_normalized' =>
                $normalized,

            'occurrence_count' =>
                0,
        ];
    }

    $sourceStats[$normalized]['occurrence_count']++;
}

fclose($dryHandle);

/*
|--------------------------------------------------------------------------
| Build gap review
|--------------------------------------------------------------------------
*/

$results = [];

$existingMaster = 0;
$missingMaster = 0;
$manualReview = 0;
$masterGapCandidates = 0;

foreach ($sourceStats as $normalized => $stats) {

    $candidate =
        $candidateLookup[$normalized]
        ?? null;

    $suggestedCode =
        strtoupper(
            trim(
                (string) (
                    $candidate['suggested_code']
                    ?? ''
                )
            )
        );

    $suggestedNameEn =
        $candidate['suggested_name_en']
        ?? '';

    $confidence =
        $candidate['confidence']
        ?? '';

    /*
    |--------------------------------------------------------------------------
    | Determine whether master exists
    |--------------------------------------------------------------------------
    */

    $matchedMaster = null;
    $matchMethod = null;

    if (
        $suggestedCode !== ''
        &&
        isset(
            $countryByCode[
                $suggestedCode
            ]
        )
    ) {

        $matchedMaster =
            $countryByCode[
                $suggestedCode
            ];

        $matchMethod =
            'SUGGESTED_CODE';

    } elseif (
        $suggestedCode !== ''
        &&
        isset(
            $countryByIso3[
                $suggestedCode
            ]
        )
    ) {

        $matchedMaster =
            $countryByIso3[
                $suggestedCode
            ];

        $matchMethod =
            'SUGGESTED_ISO3';

    } elseif (
        $suggestedNameEn !== ''
    ) {

        $nameKey =
            normalizeCountry(
                $suggestedNameEn
            );

        if (
            isset(
                $countryByName[
                    $nameKey
                ]
            )
        ) {

            $matchedMaster =
                $countryByName[
                    $nameKey
                ];

            $matchMethod =
                'SUGGESTED_NAME';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Determine status
    |--------------------------------------------------------------------------
    */

    if ($matchedMaster !== null) {

        $masterStatus =
            'EXISTING_MASTER';

        $existingMaster++;

        $recommendation =
            'COUNTRY_ALIAS';

    } else {

        $masterStatus =
            'MISSING_MASTER';

        $missingMaster++;

        /*
        |--------------------------------------------------------------------------
        | A candidate coming from the low-confidence audit is a
        | candidate for master expansion, not alias creation.
        |--------------------------------------------------------------------------
        */

        if (
            $suggestedCode !== ''
            ||
            $suggestedNameEn !== ''
        ) {

            $recommendation =
                'MASTER_ADDITION_CANDIDATE';

            $masterGapCandidates++;

        } else {

            $recommendation =
                'MANUAL_REVIEW';

            $manualReview++;
        }
    }

    $results[] = [
        'country_source' =>
            $stats['country_source'],

        'country_normalized' =>
            $normalized,

        'occurrence_count' =>
            $stats['occurrence_count'],

        'suggested_country_code' =>
            $suggestedCode,

        'suggested_country_name_en' =>
            $suggestedNameEn,

        'candidate_confidence' =>
            $confidence,

        'master_status' =>
            $masterStatus,

        'master_id' =>
            $matchedMaster?->id,

        'master_country_code' =>
            $matchedMaster?->country_code,

        'master_iso3' =>
            $matchedMaster?->iso3,

        'master_country_name_en' =>
            $matchedMaster?->country_name_en,

        'master_country_name_id' =>
            $matchedMaster?->country_name_id,

        'match_method' =>
            $matchMethod,

        'recommendation' =>
            $recommendation,
    ];
}

/*
|--------------------------------------------------------------------------
| Sort by occurrence count
|--------------------------------------------------------------------------
*/

usort(
    $results,
    function (
        array $a,
        array $b
    ): int {
        return
            $b['occurrence_count']
            <=>
            $a['occurrence_count'];
    }
);

/*
|--------------------------------------------------------------------------
| Output CSV
|--------------------------------------------------------------------------
*/

$output = fopen(
    $outputFile,
    'wb'
);

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'country_source',
        'country_normalized',
        'occurrence_count',
        'suggested_country_code',
        'suggested_country_name_en',
        'candidate_confidence',
        'master_status',
        'master_id',
        'master_country_code',
        'master_iso3',
        'master_country_name_en',
        'master_country_name_id',
        'match_method',
        'recommendation',
    ]
);

foreach ($results as $result) {

    fputcsv(
        $output,
        $result
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$totalUnresolved =
    count($results);

echo "========================================\n";
echo "DIGESTEX TRADE COUNTRY MASTER GAP AUDIT\n";
echo "========================================\n\n";

echo "UNRESOLVED COUNTRY NAMES : "
    . $totalUnresolved
    . PHP_EOL;

echo "EXISTING MASTER          : "
    . $existingMaster
    . PHP_EOL;

echo "MISSING MASTER           : "
    . $missingMaster
    . PHP_EOL;

echo "MASTER ADD CANDIDATES    : "
    . $masterGapCandidates
    . PHP_EOL;

echo "MANUAL REVIEW            : "
    . $manualReview
    . PHP_EOL;

echo "\nMASTER COUNTRIES         : "
    . $countryRows->count()
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";