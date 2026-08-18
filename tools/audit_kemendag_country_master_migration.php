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
| PHASE 1 CANONICAL CANDIDATES
|--------------------------------------------------------------------------
|
| These are explicit canonical mappings.
| No fuzzy matching is used.
|
*/

$candidates = [
    'MAURITIUS' => [
        'country_name_en' => 'Mauritius',
        'country_name_id' => 'Mauritius',
        'country_code' => 'MU',
        'iso3' => 'MUS',
    ],

    'LITHUANIA' => [
        'country_name_en' => 'Lithuania',
        'country_name_id' => 'Lituania',
        'country_code' => 'LT',
        'iso3' => 'LTU',
    ],

    'KAZAKHSTAN' => [
        'country_name_en' => 'Kazakhstan',
        'country_name_id' => 'Kazakhstan',
        'country_code' => 'KZ',
        'iso3' => 'KAZ',
    ],

    'AZERBAIJAN' => [
        'country_name_en' => 'Azerbaijan',
        'country_name_id' => 'Azerbaijan',
        'country_code' => 'AZ',
        'iso3' => 'AZE',
    ],

    'GEORGIA' => [
        'country_name_en' => 'Georgia',
        'country_name_id' => 'Georgia',
        'country_code' => 'GE',
        'iso3' => 'GEO',
    ],

    'MALTA' => [
        'country_name_en' => 'Malta',
        'country_name_id' => 'Malta',
        'country_code' => 'MT',
        'iso3' => 'MLT',
    ],

    'DJIBOUTI' => [
        'country_name_en' => 'Djibouti',
        'country_name_id' => 'Djibouti',
        'country_code' => 'DJ',
        'iso3' => 'DJI',
    ],

    'MONAKO' => [
        'country_name_en' => 'Monaco',
        'country_name_id' => 'Monako',
        'country_code' => 'MC',
        'iso3' => 'MCO',
    ],

    'SLOVAKIA' => [
        'country_name_en' => 'Slovakia',
        'country_name_id' => 'Slovakia',
        'country_code' => 'SK',
        'iso3' => 'SVK',
    ],

    'ALBANIA' => [
        'country_name_en' => 'Albania',
        'country_name_id' => 'Albania',
        'country_code' => 'AL',
        'iso3' => 'ALB',
    ],

    'MOZAMBIK' => [
        'country_name_en' => 'Mozambique',
        'country_name_id' => 'Mozambik',
        'country_code' => 'MZ',
        'iso3' => 'MOZ',
    ],

    'MOLDOVA' => [
        'country_name_en' => 'Moldova',
        'country_name_id' => 'Moldova',
        'country_code' => 'MD',
        'iso3' => 'MDA',
    ],

    'GUINEA' => [
        'country_name_en' => 'Guinea',
        'country_name_id' => 'Guinea',
        'country_code' => 'GN',
        'iso3' => 'GIN',
    ],

    'UZBEKISTAN' => [
        'country_name_en' => 'Uzbekistan',
        'country_name_id' => 'Uzbekistan',
        'country_code' => 'UZ',
        'iso3' => 'UZB',
    ],

    'BOSNIA DAN HERZEGOVINA' => [
        'country_name_en' => 'Bosnia and Herzegovina',
        'country_name_id' => 'Bosnia dan Herzegovina',
        'country_code' => 'BA',
        'iso3' => 'BIH',
    ],

    /*
     * KONGO is kept explicit because Kemendag also has
     * REPUBLIK DEMOKRATIK KONGO as a separate source name.
     */
    'KONGO' => [
        'country_name_en' => 'Republic of the Congo',
        'country_name_id' => 'Kongo',
        'country_code' => 'CG',
        'iso3' => 'COG',
    ],

    'BELARUS' => [
        'country_name_en' => 'Belarus',
        'country_name_id' => 'Belarus',
        'country_code' => 'BY',
        'iso3' => 'BLR',
    ],

    'ANTIGUA DAN BARBUDA' => [
        'country_name_en' => 'Antigua and Barbuda',
        'country_name_id' => 'Antigua dan Barbuda',
        'country_code' => 'AG',
        'iso3' => 'ATG',
    ],

    'BENIN' => [
        'country_name_en' => 'Benin',
        'country_name_id' => 'Benin',
        'country_code' => 'BJ',
        'iso3' => 'BEN',
    ],

    'MONTENEGRO' => [
        'country_name_en' => 'Montenegro',
        'country_name_id' => 'Montenegro',
        'country_code' => 'ME',
        'iso3' => 'MNE',
    ],

    'ARMENIA' => [
        'country_name_en' => 'Armenia',
        'country_name_id' => 'Armenia',
        'country_code' => 'AM',
        'iso3' => 'ARM',
    ],

    'SIERA LEONE' => [
        'country_name_en' => 'Sierra Leone',
        'country_name_id' => 'Sierra Leone',
        'country_code' => 'SL',
        'iso3' => 'SLE',
    ],

    'ANGOLA' => [
        'country_name_en' => 'Angola',
        'country_name_id' => 'Angola',
        'country_code' => 'AO',
        'iso3' => 'AGO',
    ],

    'LIBERIA' => [
        'country_name_en' => 'Liberia',
        'country_name_id' => 'Liberia',
        'country_code' => 'LR',
        'iso3' => 'LBR',
    ],

    'GAMBIA' => [
        'country_name_en' => 'Gambia',
        'country_name_id' => 'Gambia',
        'country_code' => 'GM',
        'iso3' => 'GMB',
    ],

    'KYRGYZSTAN' => [
        'country_name_en' => 'Kyrgyzstan',
        'country_name_id' => 'Kirgizstan',
        'country_code' => 'KG',
        'iso3' => 'KGZ',
    ],

    'MALI' => [
        'country_name_en' => 'Mali',
        'country_name_id' => 'Mali',
        'country_code' => 'ML',
        'iso3' => 'MLI',
    ],

    'BURUNDI' => [
        'country_name_en' => 'Burundi',
        'country_name_id' => 'Burundi',
        'country_code' => 'BI',
        'iso3' => 'BDI',
    ],

    'SWAZILAND' => [
        'country_name_en' => 'Eswatini',
        'country_name_id' => 'Eswatini',
        'country_code' => 'SZ',
        'iso3' => 'SWZ',
    ],

    'BURKINA FASO' => [
        'country_name_en' => 'Burkina Faso',
        'country_name_id' => 'Burkina Faso',
        'country_code' => 'BF',
        'iso3' => 'BFA',
    ],

    'GRENADA' => [
        'country_name_en' => 'Grenada',
        'country_name_id' => 'Grenada',
        'country_code' => 'GD',
        'iso3' => 'GRD',
    ],

    'SIRIA' => [
        'country_name_en' => 'Syria',
        'country_name_id' => 'Suriah',
        'country_code' => 'SY',
        'iso3' => 'SYR',
    ],

    'ZIMBABWE' => [
        'country_name_en' => 'Zimbabwe',
        'country_name_id' => 'Zimbabwe',
        'country_code' => 'ZW',
        'iso3' => 'ZWE',
    ],

    'KOSOVO' => [
        /*
         * ISO 3166 does not assign Kosovo an official ISO 3166-1
         * code in the same manner as the others. Therefore this
         * candidate is intentionally REVIEW.
         */
        'country_name_en' => 'Kosovo',
        'country_name_id' => 'Kosovo',
        'country_code' => null,
        'iso3' => null,
    ],

    'LESOTHO' => [
        'country_name_en' => 'Lesotho',
        'country_name_id' => 'Lesotho',
        'country_code' => 'LS',
        'iso3' => 'LSO',
    ],

    'NAMIBIA' => [
        'country_name_en' => 'Namibia',
        'country_name_id' => 'Namibia',
        'country_code' => 'NA',
        'iso3' => 'NAM',
    ],
];

/*
|--------------------------------------------------------------------------
| Normalize helper
|--------------------------------------------------------------------------
*/

function normalizeCountry(
    ?string $value
): string {
    $value =
        trim(
            (string) $value
        );

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
| Load master
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX COUNTRY MASTER MIGRATION READINESS AUDIT\n";
echo "========================================\n\n";

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

echo "CURRENT ACTIVE MASTER : "
    . $master->count()
    . PHP_EOL;

echo "PHASE 1 CANDIDATES     : "
    . count($candidates)
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Production record counts
|--------------------------------------------------------------------------
*/

function candidateRecordCount(
    string $sourceName
): int {
    return (int) DB::table(
        'trade_statistics'
    )
        ->where(
            'year',
            TARGET_YEAR
        )
        ->whereNull(
            'country_id'
        )
        ->where(
            'country_name',
            $sourceName
        )
        ->count();
}

/*
|--------------------------------------------------------------------------
| Evaluate
|--------------------------------------------------------------------------
*/

$results = [];

foreach (
    $candidates as $sourceName => $candidate
) {

    $isoMatch =
        null;

    if (
        $candidate['iso3'] !== null
    ) {
        $isoMatch =
            $master->first(
                fn ($row) =>
                    strtoupper(
                        trim(
                            (string) (
                                $row->iso3
                                ?? ''
                            )
                        )
                    )
                    ===
                    strtoupper(
                        trim(
                            (string) $candidate['iso3']
                        )
                    )
            );
    }

    $englishMatch =
        $master->first(
            fn ($row) =>
                normalizeCountry(
                    $row->country_name_en
                )
                ===
                normalizeCountry(
                    $candidate['country_name_en']
                )
        );

    $indonesianMatch =
        $master->first(
            fn ($row) =>
                normalizeCountry(
                    $row->country_name_id
                )
                ===
                normalizeCountry(
                    $candidate['country_name_id']
                )
        );

    $codeMatch =
        null;

    if (
        $candidate['country_code'] !== null
    ) {
        $codeMatch =
            $master->first(
                fn ($row) =>
                    strtoupper(
                        trim(
                            (string) (
                                $row->country_code
                                ?? ''
                            )
                        )
                    )
                    ===
                    strtoupper(
                        trim(
                            (string) $candidate['country_code']
                        )
                    )
            );
    }

    $records =
        candidateRecordCount(
            $sourceName
        );

    /*
     * Special case: Kosovo
     */
    if (
        $sourceName === 'KOSOVO'
    ) {

        $status =
            'REVIEW';

    } elseif (
        $isoMatch !== null
        &&
        (
            normalizeCountry(
                $isoMatch->country_name_en
            )
            ===
            normalizeCountry(
                $candidate['country_name_en']
            )
        )
    ) {

        $status =
            'EXISTS';

    } elseif (
        $isoMatch === null
        &&
        $englishMatch === null
        &&
        $indonesianMatch === null
        &&
        $codeMatch === null
    ) {

        $status =
            'READY_FOR_MASTER';

    } else {

        $status =
            'CONFLICT';
    }

    $results[] = [
        'source_name' =>
            $sourceName,

        'canonical_en' =>
            $candidate['country_name_en'],

        'canonical_id' =>
            $candidate['country_name_id'],

        'country_code' =>
            $candidate['country_code'],

        'iso3' =>
            $candidate['iso3'],

        'records' =>
            $records,

        'status' =>
            $status,

        'matched_id' =>
            $isoMatch?->id
            ?? $englishMatch?->id
            ?? $indonesianMatch?->id
            ?? $codeMatch?->id,
    ];
}

/*
|--------------------------------------------------------------------------
| Sort: status then records
|--------------------------------------------------------------------------
*/

$statusOrder = [
    'CONFLICT' => 1,
    'REVIEW' => 2,
    'EXISTS' => 3,
    'READY_FOR_MASTER' => 4,
];

usort(
    $results,
    static function (
        array $a,
        array $b
    ) use (
        $statusOrder
    ): int {

        $statusCompare =
            ($statusOrder[$a['status']] ?? 99)
            <=>
            ($statusOrder[$b['status']] ?? 99);

        if (
            $statusCompare !== 0
        ) {
            return $statusCompare;
        }

        return
            $b['records']
            <=>
            $a['records'];
    }
);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$summary = [
    'READY_FOR_MASTER' => 0,
    'EXISTS' => 0,
    'CONFLICT' => 0,
    'REVIEW' => 0,
];

foreach (
    $results as $result
) {
    $summary[
        $result['status']
    ]++;
}

echo "========================================\n";
echo "SUMMARY\n";
echo "========================================\n";

foreach (
    $summary as $status => $count
) {

    echo sprintf(
        "  %-18s : %d\n",
        $status,
        $count
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Detailed output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MIGRATION READINESS\n";
echo "========================================\n";

foreach (
    $results as $result
) {

    echo sprintf(
        "  %-17s | %-35s | %3d records | ISO=%-3s | %s\n",
        $result['status'],
        $result['source_name'],
        $result['records'],
        $result['iso3'] ?? '-',
        $result['canonical_en']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Recommended migration list
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "READY FOR MASTER INSERT\n";
echo "========================================\n";

foreach (
    $results as $result
) {

    if (
        $result['status'] !== 'READY_FOR_MASTER'
    ) {
        continue;
    }

    echo sprintf(
        "  %-35s | %-30s | %s | %s | %3d records\n",
        $result['source_name'],
        $result['canonical_en'],
        $result['country_code'] ?? '-',
        $result['iso3'] ?? '-',
        $result['records']
    );
}

echo PHP_EOL;

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";
echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";
echo "COUNTRY MASTER MIGRATION AUDIT : COMPLETE\n";
echo "========================================\n";