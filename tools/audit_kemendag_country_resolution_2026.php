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

/*
|--------------------------------------------------------------------------
| VERIFIED KEMENDAG ALIASES
|--------------------------------------------------------------------------
|
| Hanya masukkan mapping yang sudah diverifikasi secara eksplisit.
| TIDAK menggunakan fuzzy matching otomatis.
|
*/

$verifiedKemendagAliases = [
    'SRI LANGKA' =>
        23,

    'REPUBLIK CZECH' =>
        51,

    'LIBIA' =>
        90,

    'KEPULAUAN SALOMON' =>
        103,

    'GUIANA PERANCIS' =>
        71,
];

echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY RESOLUTION GAP REPORT 2026 V3\n";
echo "========================================\n\n";

$resolver =
    app(
        CountryResolverService::class
    );

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeCountry(
    string $value
): string {
    $value =
        trim($value);

    if ($value === '') {
        return '';
    }

    $value =
        preg_replace(
            '/\s+/',
            ' ',
            $value
        ) ?? '';

    return mb_strtoupper(
        $value
    );
}

/*
|--------------------------------------------------------------------------
| Validate verified aliases
|--------------------------------------------------------------------------
*/

$verifiedAliasRecords = [];

foreach (
    $verifiedKemendagAliases
    as $sourceName => $countryId
) {

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
            'Verified alias menunjuk ke '
            . 'country_id yang tidak ditemukan/aktif: '
            . $countryId
            . ' untuk '
            . $sourceName
        );
    }

    $verifiedAliasRecords[
        normalizeCountry($sourceName)
    ] = [
        'country_id' =>
            (int) $country->id,

        'country_code' =>
            $country->country_code,

        'iso3' =>
            $country->iso3,

        'country_name_en' =>
            $country->country_name_en,

        'country_name_id' =>
            $country->country_name_id,
    ];
}

/*
|--------------------------------------------------------------------------
| Source unresolved names
|--------------------------------------------------------------------------
*/

$sourceRows =
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
        ->select(
            'trade_flow',
            'country_name'
        )
        ->groupBy(
            'trade_flow',
            'country_name'
        )
        ->orderBy(
            'trade_flow'
        )
        ->orderBy(
            'country_name'
        )
        ->get();

/*
|--------------------------------------------------------------------------
| Result buckets
|--------------------------------------------------------------------------
*/

$resolved = [];
$verifiedAliases = [];
$masterGap = [];
$review = [];

/*
|--------------------------------------------------------------------------
| Process
|--------------------------------------------------------------------------
*/

foreach (
    $sourceRows as $row
) {

    $tradeFlow =
        (string) $row->trade_flow;

    $sourceName =
        trim(
            (string) $row->country_name
        );

    $normalized =
        normalizeCountry(
            $sourceName
        );

    if ($normalized === '') {
        continue;
    }

    /*
     * 1. Current resolver.
     */
    $current =
        $resolver->resolve(
            $sourceName,
            'KEMENDAG'
        );

    if ($current !== null) {

        $resolved[] = [
            'trade_flow' =>
                $tradeFlow,

            'source_name' =>
                $sourceName,

            'country_id' =>
                (int) $current->id,

            'country_code' =>
                $current->country_code,

            'iso3' =>
                $current->iso3,

            'canonical_name' =>
                $current->country_name_en,
        ];

        continue;
    }

    /*
     * 2. Explicit verified alias.
     */
    if (
        isset(
            $verifiedAliasRecords[
                $normalized
            ]
        )
    ) {

        $country =
            $verifiedAliasRecords[
                $normalized
            ];

        $verifiedAliases[] = [
            'trade_flow' =>
                $tradeFlow,

            'source_name' =>
                $sourceName,

            'country_id' =>
                $country['country_id'],

            'country_code' =>
                $country['country_code'],

            'iso3' =>
                $country['iso3'],

            'canonical_name' =>
                $country['country_name_en'],
        ];

        continue;
    }

    /*
     * 3. No exact resolver + no verified alias.
     *
     * We intentionally do NOT attempt fuzzy mapping.
     *
     * First determine whether an exact canonical match
     * exists by normalized canonical fields.
     */
    $canonical =
        DB::table('mst_countries')
            ->where(
                'is_active',
                true
            )
            ->where(
                function ($query) use (
                    $normalized
                ) {
                    $query
                        ->whereRaw(
                            'UPPER(TRIM(country_name_en)) = ?',
                            [
                                $normalized,
                            ]
                        )
                        ->orWhereRaw(
                            'UPPER(TRIM(country_name_id)) = ?',
                            [
                                $normalized,
                            ]
                        )
                        ->orWhereRaw(
                            'UPPER(TRIM(country_code)) = ?',
                            [
                                $normalized,
                            ]
                        )
                        ->orWhereRaw(
                            'UPPER(TRIM(iso3)) = ?',
                            [
                                $normalized,
                            ]
                        );
                }
            )
            ->first([
                'id',
                'country_code',
                'iso3',
                'country_name_en',
                'country_name_id',
            ]);

    if ($canonical !== null) {

        $review[] = [
            'trade_flow' =>
                $tradeFlow,

            'source_name' =>
                $sourceName,

            'country_id' =>
                (int) $canonical->id,

            'country_code' =>
                $canonical->country_code,

            'iso3' =>
                $canonical->iso3,

            'canonical_name' =>
                $canonical->country_name_en,

            'reason' =>
                'Exact canonical master exists but is not covered by current alias resolver.',
        ];

        continue;
    }

    /*
     * 4. True master gap.
     */
    $masterGap[] = [
        'trade_flow' =>
            $tradeFlow,

        'source_name' =>
            $sourceName,

        'reason' =>
            'No exact canonical master and no verified KEMENDAG alias.',
    ];
}

/*
|--------------------------------------------------------------------------
| Record counts
|--------------------------------------------------------------------------
*/

function unresolvedRecordCount(
    string $tradeFlow,
    string $sourceName
): int {
    return (int) DB::table(
        'trade_statistics'
    )
        ->where(
            'year',
            TARGET_YEAR
        )
        ->where(
            'trade_flow',
            $tradeFlow
        )
        ->where(
            'country_name',
            $sourceName
        )
        ->whereNull(
            'country_id'
        )
        ->count();
}

function attachCounts(
    array $items
): array {
    foreach (
        $items as &$item
    ) {
        $item['records'] =
            unresolvedRecordCount(
                $item['trade_flow'],
                $item['source_name']
            );
    }

    unset($item);

    usort(
        $items,
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

    return $items;
}

$verifiedAliases =
    attachCounts(
        $verifiedAliases
    );

$review =
    attachCounts(
        $review
    );

$masterGap =
    attachCounts(
        $masterGap
    );

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "VERIFIED ALIAS DICTIONARY:\n";

foreach (
    $verifiedAliasRecords
    as $sourceName => $country
) {
    echo sprintf(
        "  %-35s -> ID=%d | %s | %s\n",
        $sourceName,
        $country['country_id'],
        $country['country_code'],
        $country['country_name_en']
    );
}

echo PHP_EOL;

echo "SOURCE UNRESOLVED NAMES:\n";
echo "  "
    . count($sourceRows)
    . PHP_EOL;

echo PHP_EOL;

echo "RESOLUTION SUMMARY:\n";

echo "  CURRENTLY RESOLVED       : "
    . count($resolved)
    . PHP_EOL;

echo "  VERIFIED ALIAS           : "
    . count($verifiedAliases)
    . PHP_EOL;

echo "  REVIEW REQUIRED          : "
    . count($review)
    . PHP_EOL;

echo "  TRUE MASTER GAP          : "
    . count($masterGap)
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Verified aliases
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "VERIFIED ALIAS\n";
echo "========================================\n";

if (
    empty(
        $verifiedAliases
    )
) {

    echo "  NONE\n";

} else {

    foreach (
        $verifiedAliases as $item
    ) {
        echo sprintf(
            "  %-7s | %-35s | ID=%d | %s | %s | %d records\n",
            strtoupper(
                $item['trade_flow']
            ),
            $item['source_name'],
            $item['country_id'],
            $item['country_code'],
            $item['canonical_name'],
            $item['records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Review
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "REVIEW REQUIRED\n";
echo "========================================\n";

if (
    empty($review)
) {

    echo "  NONE\n";

} else {

    foreach (
        $review as $item
    ) {

        echo sprintf(
            "  %-7s | %-35s | ID=%d | %s | %d records\n",
            strtoupper(
                $item['trade_flow']
            ),
            $item['source_name'],
            $item['country_id'],
            $item['canonical_name'],
            $item['records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| True master gap
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TRUE MASTER GAP\n";
echo "========================================\n";

if (
    empty($masterGap)
) {

    echo "  NONE\n";

} else {

    foreach (
        $masterGap as $item
    ) {

        echo sprintf(
            "  %-7s | %-45s | %d records\n",
            strtoupper(
                $item['trade_flow']
            ),
            $item['source_name'],
            $item['records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Top unresolved
|--------------------------------------------------------------------------
*/

$allIssues =
    array_merge(
        $verifiedAliases,
        $review,
        $masterGap
    );

usort(
    $allIssues,
    static function (
        array $a,
        array $b
    ): int {
        return
            ($b['records'] ?? 0)
            <=>
            ($a['records'] ?? 0);
    }
);

echo "========================================\n";
echo "TOP COUNTRY RESOLUTION ISSUES\n";
echo "========================================\n";

foreach (
    array_slice(
        $allIssues,
        0,
        50
    ) as $item
) {

    if (
        isset(
            $item['country_id']
        )
    ) {
        $category =
            'ALIAS';
    } else {
        $category =
            'MASTER';
    }

    echo sprintf(
        "  %-6s | %-7s | %-45s | %d records\n",
        $category,
        strtoupper(
            $item['trade_flow']
        ),
        $item['source_name'],
        $item['records']
    );
}

echo PHP_EOL;

echo "========================================\n";
echo "COUNTRY RESOLUTION GAP REPORT V3 : COMPLETE\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";