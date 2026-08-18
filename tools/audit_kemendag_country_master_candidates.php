<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

const TARGET_YEAR = 2026;

/*
|--------------------------------------------------------------------------
| Known special / territory nomenclature
|--------------------------------------------------------------------------
|
| Ini sengaja dipisahkan dari sovereign country candidates.
|
*/

$territoryNames = [
    'NETHERLANDS ANTILLES',
    'JERSEY',
    'KEPULAUAN CAYMAN',
    'KEP. VALLIS DAN FUTUNA',
    'KEPULAUAN TURKS DAN CAICOS',
    'U.S MINOR OUTLYING ISLAND',
    'VIRGIN ISLANDS (BRITISH)',
    'MAYOTTE',
    'ARUBA',
    'ANGUILA',
    'U.S. VIRGIN ISLANDS',
    'KEPULAUAN MARIANA UTARA',
    'KEPULAUAN ALAND',
    'KEPULAUAN COCOS (KEELING)',
];

$specialNames = [
    'INDONESIA (BATAM)',
    'FD STS MICRONESIA',
];

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeCountryCandidate(
    string $value
): string {
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    return mb_strtoupper(
        preg_replace(
            '/\s+/',
            ' ',
            $value
        ) ?? ''
    );
}

/*
|--------------------------------------------------------------------------
| Load unresolved source names
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY MASTER CANDIDATES\n";
echo "========================================\n\n";

echo "STEP 1: Loading 2026 unresolved countries...\n";

$rows =
    DB::table('trade_statistics')
        ->where(
            'year',
            TARGET_YEAR
        )
        ->whereIn(
            'trade_flow',
            [
                'export',
                'import',
            ]
        )
        ->whereNull(
            'country_id'
        )
        ->whereNotNull(
            'country_name'
        )
        ->where(
            'country_name',
            '<>',
            ''
        )
        ->selectRaw(
            'country_name, trade_flow, COUNT(*) AS records'
        )
        ->groupBy(
            'country_name',
            'trade_flow'
        )
        ->get();

echo "  Rows loaded : "
    . $rows->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Consolidate by source country name
|--------------------------------------------------------------------------
*/

$candidates = [];

foreach ($rows as $row) {

    $sourceName =
        trim(
            (string) $row->country_name
        );

    $normalized =
        normalizeCountryCandidate(
            $sourceName
        );

    if ($normalized === '') {
        continue;
    }

    if (
        !isset(
            $candidates[$normalized]
        )
    ) {
        $candidates[$normalized] = [
            'source_name' =>
                $sourceName,

            'export_records' =>
                0,

            'import_records' =>
                0,

            'total_records' =>
                0,
        ];
    }

    $records =
        (int) $row->records;

    if (
        $row->trade_flow === 'export'
    ) {
        $candidates[$normalized][
            'export_records'
        ] += $records;
    }

    if (
        $row->trade_flow === 'import'
    ) {
        $candidates[$normalized][
            'import_records'
        ] += $records;
    }

    $candidates[$normalized][
        'total_records'
    ] += $records;
}

echo "  Unique source names : "
    . count($candidates)
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Load current country master
|--------------------------------------------------------------------------
*/

$master =
    DB::table('mst_countries')
        ->where(
            'is_active',
            true
        )
        ->get([
            'id',
            'country_code',
            'iso3',
            'country_name_en',
            'country_name_id',
        ]);

$masterByIso3 = [];

foreach ($master as $country) {

    if (
        $country->iso3 !== null
        &&
        trim(
            (string) $country->iso3
        ) !== ''
    ) {
        $masterByIso3[
            strtoupper(
                trim(
                    (string) $country->iso3
                )
            )
        ] = $country;
    }
}

echo "  Current master countries : "
    . $master->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Phase 1 sovereign candidates
|--------------------------------------------------------------------------
|
| Hanya daftar yang sudah terlihat jelas sebagai sovereign / standard
| country names dari V4.
|
*/

$phase1Names = [
    'MAURITIUS',
    'LITHUANIA',
    'KAZAKHSTAN',
    'AZERBAIJAN',
    'GEORGIA',
    'MALTA',
    'DJIBOUTI',
    'MONAKO',
    'SLOVAKIA',
    'ALBANIA',
    'MOZAMBIK',
    'MOLDOVA',
    'GUINEA',
    'UZBEKISTAN',
    'KONGO',
    'BOSNIA DAN HERZEGOVINA',
    'BELARUS',
    'ANTIGUA DAN BARBUDA',
    'MONTENEGRO',
    'BENIN',
    'ANGOLA',
    'LIBERIA',
    'ARUBA',
    'KYRGYZSTAN',
    'MALI',
    'ARMENIA',
    'GAMBIA',
    'BURUNDI',
    'SWAZILAND',
    'GRENADA',
    'ZIMBABWE',
    'NAMIBIA',
    'SIERA LEONE',
    'SIRIA',
    'BURKINA FASO',
    'KOSOVO',
    'LESOTHO',
];

/*
|--------------------------------------------------------------------------
| Classification
|--------------------------------------------------------------------------
*/

$phase1 = [];
$territories = [];
$special = [];
$otherMasterGaps = [];

foreach (
    $candidates as $normalized => $item
) {

    $sourceName =
        $item['source_name'];

    /*
     * Special nomenclature
     */
    if (
        in_array(
            $normalized,
            array_map(
                'normalizeCountryCandidate',
                $specialNames
            ),
            true
        )
    ) {
        $special[] =
            $item;

        continue;
    }

    /*
     * Territory / dependency
     */
    if (
        in_array(
            $normalized,
            array_map(
                'normalizeCountryCandidate',
                $territoryNames
            ),
            true
        )
    ) {
        $territories[] =
            $item;

        continue;
    }

    /*
     * Explicit Phase 1 candidate
     */
    if (
        in_array(
            $normalized,
            $phase1Names,
            true
        )
    ) {
        $phase1[] =
            $item;

        continue;
    }

    /*
     * Remaining master gaps
     */
    $otherMasterGaps[] =
        $item;
}

/*
|--------------------------------------------------------------------------
| Sort
|--------------------------------------------------------------------------
*/

$sortByRecords =
    static function (
        array $a,
        array $b
    ): int {
        return
            $b['total_records']
            <=>
            $a['total_records'];
    };

usort(
    $phase1,
    $sortByRecords
);

usort(
    $territories,
    $sortByRecords
);

usort(
    $special,
    $sortByRecords
);

usort(
    $otherMasterGaps,
    $sortByRecords
);

/*
|--------------------------------------------------------------------------
| Output summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CANDIDATE CLASSIFICATION\n";
echo "========================================\n";

echo "  PHASE 1 COUNTRY       : "
    . count($phase1)
    . PHP_EOL;

echo "  TERRITORY / DEPENDENCY: "
    . count($territories)
    . PHP_EOL;

echo "  SPECIAL NOMENCLATURE  : "
    . count($special)
    . PHP_EOL;

echo "  OTHER MASTER GAPS     : "
    . count($otherMasterGaps)
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Phase 1 output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "PHASE 1 COUNTRY CANDIDATES\n";
echo "========================================\n";

if (
    empty($phase1)
) {

    echo "  NONE\n";

} else {

    foreach (
        $phase1 as $item
    ) {

        $flow =
            $item['export_records'] > 0
            &&
            $item['import_records'] > 0
                ? 'EXPORT+IMPORT'
                : (
                    $item['export_records'] > 0
                        ? 'EXPORT'
                        : 'IMPORT'
                );

        echo sprintf(
            "  %-35s | %-13s | %6d records\n",
            $item['source_name'],
            $flow,
            $item['total_records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Territory output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TERRITORY / DEPENDENCY\n";
echo "========================================\n";

if (
    empty($territories)
) {

    echo "  NONE\n";

} else {

    foreach (
        $territories as $item
    ) {

        echo sprintf(
            "  %-35s | %6d records\n",
            $item['source_name'],
            $item['total_records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Special
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SPECIAL NOMENCLATURE\n";
echo "========================================\n";

if (
    empty($special)
) {

    echo "  NONE\n";

} else {

    foreach (
        $special as $item
    ) {

        echo sprintf(
            "  %-35s | %6d records\n",
            $item['source_name'],
            $item['total_records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Other gaps
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "OTHER MASTER GAPS\n";
echo "========================================\n";

if (
    empty($otherMasterGaps)
) {

    echo "  NONE\n";

} else {

    foreach (
        $otherMasterGaps as $item
    ) {

        echo sprintf(
            "  %-35s | %6d records\n",
            $item['source_name'],
            $item['total_records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Database safety check
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE CHECK\n";
echo "========================================\n";

echo "  No INSERT performed.\n";
echo "  No UPDATE performed.\n";
echo "  No DELETE performed.\n";

echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "COUNTRY MASTER CANDIDATE AUDIT : COMPLETE\n";
echo "========================================\n";