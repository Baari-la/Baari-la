<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\CountryResolverService;
use Illuminate\Support\Facades\DB;

const TARGET_YEAR = 2026;

$verifiedKemendagAliases = [
    'SRI LANGKA' => 23,
    'REPUBLIK CZECH' => 51,
    'LIBIA' => 90,
    'KEPULAUAN SALOMON' => 103,
    'GUIANA PERANCIS' => 71,
];

$specialSourceNames = [
    'INDONESIA (BATAM)',
];

function normalizeCountryName(string $value): string
{
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    return mb_strtoupper(
        preg_replace('/\s+/', ' ', $value) ?? ''
    );
}

echo "========================================\n";
echo "DIGESTEX UNIQUE COUNTRY MASTER GAP REPORT 2026 V4 OPTIMIZED\n";
echo "========================================\n\n";

echo "STEP 1: Loading unresolved country aggregates...\n";

$rows = DB::table('trade_statistics')
    ->where('year', TARGET_YEAR)
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('country_id')
    ->whereNotNull('country_name')
    ->where('country_name', '<>', '')
    ->selectRaw(
        'trade_flow, country_name, COUNT(*) AS records'
    )
    ->groupBy(
        'trade_flow',
        'country_name'
    )
    ->orderBy(
        'trade_flow'
    )
    ->orderByDesc(
        'records'
    )
    ->get();

echo "  Source rows loaded : "
    . $rows->count()
    . PHP_EOL;

echo PHP_EOL;

echo "STEP 2: Consolidating unique country names...\n";

$countries = [];

foreach ($rows as $row) {

    $sourceName =
        trim((string) $row->country_name);

    $normalized =
        normalizeCountryName($sourceName);

    if ($normalized === '') {
        continue;
    }

    if (!isset($countries[$normalized])) {
        $countries[$normalized] = [
            'source_name' => $sourceName,
            'export_records' => 0,
            'import_records' => 0,
            'total_records' => 0,
        ];
    }

    $records =
        (int) $row->records;

    if ($row->trade_flow === 'export') {
        $countries[$normalized]['export_records'] += $records;
    }

    if ($row->trade_flow === 'import') {
        $countries[$normalized]['import_records'] += $records;
    }

    $countries[$normalized]['total_records'] += $records;
}

echo "  Unique country names : "
    . count($countries)
    . PHP_EOL;

echo PHP_EOL;

echo "STEP 3: Loading country master...\n";

$masterCountries =
    DB::table('mst_countries')
        ->where('is_active', true)
        ->get([
            'id',
            'country_code',
            'iso3',
            'country_name_en',
            'country_name_id',
        ]);

echo "  Active countries : "
    . $masterCountries->count()
    . PHP_EOL;

echo PHP_EOL;

$resolver =
    app(CountryResolverService::class);

$verifiedAliases = [];
$specialNames = [];
$review = [];
$masterGaps = [];

echo "STEP 4: Classifying unresolved countries...\n";

foreach ($countries as $normalized => $item) {

    $sourceName =
        $item['source_name'];

    $flows = [];

    if ($item['export_records'] > 0) {
        $flows[] = 'EXPORT';
    }

    if ($item['import_records'] > 0) {
        $flows[] = 'IMPORT';
    }

    $flowLabel =
        count($flows) === 2
            ? 'EXPORT+IMPORT'
            : $flows[0];

    /*
     * Current resolver
     */
    $resolved =
        $resolver->resolve(
            $sourceName,
            'KEMENDAG'
        );

    if ($resolved !== null) {
        $review[] = [
            'source_name' => $sourceName,
            'flow' => $flowLabel,
            'records' => $item['total_records'],
            'reason' =>
                'Current resolver unexpectedly resolved a NULL country_id source.',
        ];

        continue;
    }

    /*
     * Explicit verified alias
     */
    if (isset($verifiedKemendagAliases[$normalized])) {

        $countryId =
            $verifiedKemendagAliases[$normalized];

        $country =
            DB::table('mst_countries')
                ->where('id', $countryId)
                ->where('is_active', true)
                ->first([
                    'id',
                    'country_code',
                    'iso3',
                    'country_name_en',
                    'country_name_id',
                ]);

        if ($country === null) {
            throw new RuntimeException(
                "Verified alias target tidak ditemukan: "
                . $sourceName
                . " -> "
                . $countryId
            );
        }

        $verifiedAliases[] = [
            'source_name' =>
                $sourceName,
            'flow' =>
                $flowLabel,
            'records' =>
                $item['total_records'],
            'country_id' =>
                (int) $country->id,
            'country_code' =>
                $country->country_code,
            'iso3' =>
                $country->iso3,
            'canonical_name' =>
                $country->country_name_en,
        ];

        continue;
    }

    /*
     * Special nomenclature
     */
    if (
        in_array(
            $normalized,
            array_map(
                'normalizeCountryName',
                $specialSourceNames
            ),
            true
        )
    ) {

        $specialNames[] = [
            'source_name' =>
                $sourceName,
            'flow' =>
                $flowLabel,
            'records' =>
                $item['total_records'],
            'reason' =>
                'Special Kemendag nomenclature. Do not create a separate country.',
        ];

        continue;
    }

    /*
     * Exact canonical master match
     */
    $canonical = null;

    foreach ($masterCountries as $country) {

        $candidates = [
            $country->country_name_en,
            $country->country_name_id,
            $country->country_code,
            $country->iso3,
        ];

        foreach ($candidates as $candidate) {

            if (
                normalizeCountryName(
                    (string) $candidate
                ) === $normalized
            ) {
                $canonical = $country;
                break 2;
            }
        }
    }

    if ($canonical !== null) {

        $review[] = [
            'source_name' =>
                $sourceName,
            'flow' =>
                $flowLabel,
            'records' =>
                $item['total_records'],
            'reason' =>
                'Exact canonical master exists but current alias mapping does not cover the source name.',
        ];

        continue;
    }

    /*
     * True master gap
     */
    $masterGaps[] = [
        'source_name' =>
            $sourceName,
        'flow' =>
            $flowLabel,
        'records' =>
            $item['total_records'],
    ];
}

$sortRecords =
    static function (
        array $a,
        array $b
    ): int {
        return
            $b['records']
            <=>
            $a['records'];
    };

usort(
    $verifiedAliases,
    $sortRecords
);

usort(
    $specialNames,
    $sortRecords
);

usort(
    $review,
    $sortRecords
);

usort(
    $masterGaps,
    $sortRecords
);

echo PHP_EOL;

echo "========================================\n";
echo "UNIQUE COUNTRY RESOLUTION SUMMARY\n";
echo "========================================\n";

echo "  UNIQUE UNRESOLVED NAMES : "
    . count($countries)
    . PHP_EOL;

echo "  VERIFIED ALIAS          : "
    . count($verifiedAliases)
    . PHP_EOL;

echo "  SPECIAL NOMENCLATURE    : "
    . count($specialNames)
    . PHP_EOL;

echo "  REVIEW REQUIRED         : "
    . count($review)
    . PHP_EOL;

echo "  TRUE MASTER GAP         : "
    . count($masterGaps)
    . PHP_EOL;

echo PHP_EOL;

echo "========================================\n";
echo "VERIFIED ALIAS\n";
echo "========================================\n";

if (empty($verifiedAliases)) {

    echo "  NONE\n";

} else {

    foreach ($verifiedAliases as $item) {

        echo sprintf(
            "  %-35s | %-13s | %6d | ID=%d | %s\n",
            $item['source_name'],
            $item['flow'],
            $item['records'],
            $item['country_id'],
            $item['canonical_name']
        );
    }
}

echo PHP_EOL;

echo "========================================\n";
echo "SPECIAL SOURCE NOMENCLATURE\n";
echo "========================================\n";

if (empty($specialNames)) {

    echo "  NONE\n";

} else {

    foreach ($specialNames as $item) {

        echo sprintf(
            "  %-35s | %-13s | %6d | %s\n",
            $item['source_name'],
            $item['flow'],
            $item['records'],
            $item['reason']
        );
    }
}

echo PHP_EOL;

echo "========================================\n";
echo "REVIEW REQUIRED\n";
echo "========================================\n";

if (empty($review)) {

    echo "  NONE\n";

} else {

    foreach ($review as $item) {

        echo sprintf(
            "  %-35s | %-13s | %6d | %s\n",
            $item['source_name'],
            $item['flow'],
            $item['records'],
            $item['reason']
        );
    }
}

echo PHP_EOL;

echo "========================================\n";
echo "TRUE MASTER GAP\n";
echo "========================================\n";

if (empty($masterGaps)) {

    echo "  NONE\n";

} else {

    foreach ($masterGaps as $item) {

        echo sprintf(
            "  %-35s | %-13s | %6d records\n",
            $item['source_name'],
            $item['flow'],
            $item['records']
        );
    }
}

echo PHP_EOL;

$allIssues =
    array_merge(
        $verifiedAliases,
        $specialNames,
        $review,
        $masterGaps
    );

usort(
    $allIssues,
    $sortRecords
);

echo "========================================\n";
echo "TOP UNIQUE COUNTRY ISSUES\n";
echo "========================================\n";

foreach (
    array_slice(
        $allIssues,
        0,
        50
    ) as $item
) {

    if (
        isset($item['country_id'])
    ) {

        $category = 'ALIAS';

    } elseif (
        isset($item['reason'])
        &&
        str_starts_with(
            $item['reason'],
            'Special'
        )
    ) {

        $category = 'SPECIAL';

    } elseif (
        isset($item['reason'])
    ) {

        $category = 'REVIEW';

    } else {

        $category = 'MASTER';
    }

    echo sprintf(
        "  %-7s | %-35s | %-13s | %6d records\n",
        $category,
        $item['source_name'],
        $item['flow'],
        $item['records']
    );
}

echo PHP_EOL;

echo "========================================\n";
echo "UNIQUE COUNTRY GAP REPORT V4 : COMPLETE\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";