<?php

declare(strict_types=1);

use App\Services\Trade\CountryResolverService;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| DIGESTEX STREAMING TRADE DRY-RUN
|--------------------------------------------------------------------------
|
| Excel source:
|   ekspor 2019.xlsx
|
| Reader:
|   ZIP + XMLReader
|
| Database:
|   READ ONLY
|
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Source / Output
|--------------------------------------------------------------------------
*/

$base =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA';

$sourceFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'KEMENDAG'
    . DIRECTORY_SEPARATOR
    . 'EXPORT'
    . DIRECTORY_SEPARATOR
    . 'ekspor 2019.xlsx';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'dry_run_export_2019.csv';

if (!is_file($sourceFile)) {
    throw new RuntimeException(
        "Source file tidak ditemukan:\n{$sourceFile}"
    );
}

if (!class_exists(\ZipArchive::class)) {
    throw new RuntimeException(
        'PHP ZipArchive extension tidak tersedia.'
    );
}

if (!class_exists(\XMLReader::class)) {
    throw new RuntimeException(
        'PHP XMLReader extension tidak tersedia.'
    );
}


/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

$tradeFlow = 'EXPORT';
$year = 2019;
$sourceSystem = 'KEMENDAG';
$countryResolver = app(
    \App\Services\Trade\CountryResolverService::class
);
/*
|--------------------------------------------------------------------------
| Month mapping
|--------------------------------------------------------------------------
*/

$months = [
    1  => ['value' => 'F',  'volume' => 'R'],
    2  => ['value' => 'G',  'volume' => 'S'],
    3  => ['value' => 'H',  'volume' => 'T'],
    4  => ['value' => 'I',  'volume' => 'U'],
    5  => ['value' => 'J',  'volume' => 'V'],
    6  => ['value' => 'K',  'volume' => 'W'],
    7  => ['value' => 'L',  'volume' => 'X'],
    8  => ['value' => 'M',  'volume' => 'Y'],
    9  => ['value' => 'N',  'volume' => 'Z'],
    10 => ['value' => 'O',  'volume' => 'AA'],
    11 => ['value' => 'P',  'volume' => 'AB'],
    12 => ['value' => 'Q',  'volume' => 'AC'],
];

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function normalizeValue(?string $value): string
{
    $value = trim((string) $value);

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

function numericValue(mixed $value): float
{
    if ($value === null || $value === '') {
        return 0.0;
    }

    if (is_numeric($value)) {
        return (float) $value;
    }

    $value = trim((string) $value);

    $value = str_replace(',', '', $value);

    return is_numeric($value)
        ? (float) $value
        : 0.0;
}

/*
|--------------------------------------------------------------------------
| XLSX Shared Strings
|--------------------------------------------------------------------------
*/

function loadSharedStrings(
    ZipArchive $zip
): array {
    $index = $zip->locateName(
        'xl/sharedStrings.xml',
        ZipArchive::FL_NOCASE
    );

    if ($index === false) {
        return [];
    }

    $stream = $zip->getStream(
        $zip->getNameIndex($index)
    );

    if ($stream === false) {
        throw new RuntimeException(
            'Tidak dapat membuka sharedStrings.xml.'
        );
    }

    $reader = new XMLReader();

    /*
    |--------------------------------------------------------------------------
    | XMLReader::open cannot consume the ZipArchive stream directly
    | on all PHP builds. Write the stream to a temporary file.
    |--------------------------------------------------------------------------
    */

    $tempFile = tempnam(
        sys_get_temp_dir(),
        'digestex_shared_'
    );

    if ($tempFile === false) {
        fclose($stream);

        throw new RuntimeException(
            'Tidak dapat membuat temporary file.'
        );
    }

    $tempHandle = fopen(
        $tempFile,
        'wb'
    );

    if ($tempHandle === false) {
        fclose($stream);
        @unlink($tempFile);

        throw new RuntimeException(
            'Tidak dapat membuka temporary shared string file.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $tempHandle
    );

    fclose($stream);
    fclose($tempHandle);

    if (!$reader->open($tempFile)) {
        @unlink($tempFile);

        throw new RuntimeException(
            'XMLReader gagal membuka sharedStrings.xml.'
        );
    }

    $strings = [];
    $currentString = null;

    while ($reader->read()) {

        if (
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $currentString = '';
            continue;
        }

        if (
            $currentString !== null
            &&
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 't'
        ) {
            $currentString .=
                $reader->readString();

            continue;
        }

        if (
            $currentString !== null
            &&
            $reader->nodeType === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $strings[] = $currentString;
            $currentString = null;
        }
    }

    $reader->close();

    @unlink($tempFile);

    return $strings;
}

/*
|--------------------------------------------------------------------------
| Read one XLSX cell
|--------------------------------------------------------------------------
|
| XMLReader must currently be positioned on <c>.
|--------------------------------------------------------------------------
*/

function readCellValue(
    XMLReader $reader,
    array $sharedStrings
): mixed {
    $cellType =
        $reader->getAttribute('t');

    $cellDepth =
        $reader->depth;

    $value = null;

    while ($reader->read()) {

        if (
            $reader->nodeType === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'c'
            &&
            $reader->depth === $cellDepth
        ) {
            break;
        }

        if (
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 'v'
        ) {
            $raw = $reader->readString();

            if ($cellType === 's') {

                $sharedIndex =
                    (int) $raw;

                $value =
                    $sharedStrings[$sharedIndex]
                    ?? '';

            } elseif ($cellType === 'b') {

                $value =
                    $raw === '1'
                    ? 1
                    : 0;

            } else {

                $value = $raw;
            }

            continue;
        }

        if (
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 'is'
        ) {

            $inlineText = '';

            while ($reader->read()) {

                if (
                    $reader->nodeType === XMLReader::ELEMENT
                    &&
                    $reader->localName === 't'
                ) {
                    $inlineText .=
                        $reader->readString();
                }

                if (
                    $reader->nodeType === XMLReader::END_ELEMENT
                    &&
                    $reader->localName === 'is'
                ) {
                    break;
                }
            }

            $value = $inlineText;
        }
    }

    return $value;
}

/*
|--------------------------------------------------------------------------
| Column reference → index
|--------------------------------------------------------------------------
*/

function columnIndex(
    string $reference
): int {
    if (
        !preg_match(
            '/^([A-Z]+)\d+$/i',
            $reference,
            $matches
        )
    ) {
        return 0;
    }

    $letters =
        strtoupper($matches[1]);

    $index = 0;

    for (
        $i = 0;
        $i < strlen($letters);
        $i++
    ) {
        $index =
            ($index * 26)
            +
            (
                ord($letters[$i])
                - 64
            );
    }

    return $index;
}

/*
|--------------------------------------------------------------------------
| Load master HS
|--------------------------------------------------------------------------
*/

$hsLookup = \App\Models\HsCode::query()
    ->where('is_active', true)
    ->get([
        'id_hs',
        'hs_code',
    ])
    ->keyBy('hs_code');

if ($hsLookup->isEmpty()) {
    throw new RuntimeException(
        'HS master kosong.'
    );
}

/*
|--------------------------------------------------------------------------
| Province lookup
|--------------------------------------------------------------------------
*/

$provinceRows = DB::table('provinces')
    ->where('is_active', true)
    ->get([
        'id',
        'code',
        'name',
        'name_en',
    ]);

$provinceLookup = [];

foreach ($provinceRows as $province) {

    foreach ([
        $province->name,
        $province->name_en,
    ] as $name) {

        $normalized =
            normalizeValue($name);

        if ($normalized !== '') {
            $provinceLookup[$normalized] =
                $province;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Province aliases known from audit
|--------------------------------------------------------------------------
*/

$provinceAliases = [
    'D.I. YOGYAKARTA' =>
        'ID-YO',

    'NANGROE ACEH DARUSALAM' =>
        'ID-AC',
];

/*
|--------------------------------------------------------------------------
| Convert province code → province record
|--------------------------------------------------------------------------
*/

$provinceByCode = $provinceRows
    ->keyBy('code');

/*
|--------------------------------------------------------------------------
| Country master
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

$countryLookup = [];

foreach ($countryRows as $country) {

    foreach ([
        $country->country_name_en,
        $country->country_name_id,
        $country->country_code,
        $country->iso3,
    ] as $name) {

        $normalized =
            normalizeValue($name);

        if ($normalized !== '') {
            $countryLookup[$normalized] =
                $country;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Country curated aliases
|--------------------------------------------------------------------------
*/

$curatedCountries = [];

$curatedFile =
    base_path(
        'config/trade_country_curated.php'
    );

if (is_file($curatedFile)) {

   
    }


/*
|--------------------------------------------------------------------------
| Country lookup by ISO2
|--------------------------------------------------------------------------
*/

$countryByCode = [];

foreach ($countryRows as $country) {

    $countryByCode[
        strtoupper(
            trim(
                (string) $country->country_code
            )
        )
    ] = $country;
}

/*
|--------------------------------------------------------------------------
| Trade Point Alias Lookup
|--------------------------------------------------------------------------
*/

$tradePointAliases =
    DB::table('trade_point_aliases as a')
        ->join(
            'trade_points as tp',
            'tp.id',
            '=',
            'a.trade_point_id'
        )
        ->where(
            'a.source_system',
            $sourceSystem
        )
        ->where(
            'a.is_active',
            true
        )
        ->where(
            'tp.is_active',
            true
        )
        ->get([
            'a.normalized_name',
            'tp.id',
            'tp.code',
            'tp.trade_point_type_id',
        ]);

$tradePointLookup = [];

foreach ($tradePointAliases as $alias) {

    $tradePointLookup[
        normalizeValue(
            $alias->normalized_name
        )
    ] = $alias;
}

/*
|--------------------------------------------------------------------------
| ZIP open
|--------------------------------------------------------------------------
*/

$zip = new ZipArchive();

if (
    $zip->open($sourceFile)
    !== true
) {
    throw new RuntimeException(
        'Tidak dapat membuka XLSX sebagai ZIP.'
    );
}

echo "========================================\n";
echo "DIGESTEX STREAMING TRADE DRY-RUN\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo $sourceFile . "\n\n";

/*
|--------------------------------------------------------------------------
| Shared strings
|--------------------------------------------------------------------------
*/

echo "Loading shared strings...\n";

$sharedStrings =
    loadSharedStrings($zip);

echo "Shared strings loaded : "
    . count($sharedStrings)
    . "\n";

/*
|--------------------------------------------------------------------------
| Worksheet
|--------------------------------------------------------------------------
*/

$sheetPath =
    'xl/worksheets/sheet1.xml';

$sheetIndex =
    $zip->locateName(
        $sheetPath,
        ZipArchive::FL_NOCASE
    );

if ($sheetIndex === false) {
    $zip->close();

    throw new RuntimeException(
        "Worksheet tidak ditemukan: {$sheetPath}"
    );
}

$sheetPathActual =
    $zip->getNameIndex($sheetIndex);

$tempSheetFile = tempnam(
    sys_get_temp_dir(),
    'digestex_sheet_'
);

if ($tempSheetFile === false) {
    $zip->close();

    throw new RuntimeException(
        'Tidak dapat membuat temporary worksheet.'
    );
}

$sheetStream =
    $zip->getStream(
        $sheetPathActual
    );

if ($sheetStream === false) {
    $zip->close();
    @unlink($tempSheetFile);

    throw new RuntimeException(
        'Tidak dapat membuka worksheet XML.'
    );
}

$tempSheetHandle =
    fopen(
        $tempSheetFile,
        'wb'
    );

if ($tempSheetHandle === false) {
    fclose($sheetStream);
    $zip->close();
    @unlink($tempSheetFile);

    throw new RuntimeException(
        'Tidak dapat menulis temporary worksheet.'
    );
}

stream_copy_to_stream(
    $sheetStream,
    $tempSheetHandle
);

fclose($sheetStream);
fclose($tempSheetHandle);

$zip->close();

/*
|--------------------------------------------------------------------------
| XMLReader
|--------------------------------------------------------------------------
*/

$reader = new XMLReader();

if (!$reader->open($tempSheetFile)) {
    @unlink($tempSheetFile);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

$outputDir =
    dirname($outputFile);

if (!is_dir($outputDir)) {
    mkdir(
        $outputDir,
        0777,
        true
    );
}

$output = fopen(
    $outputFile,
    'wb'
);

if ($output === false) {
    $reader->close();
    @unlink($tempSheetFile);

    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'ROW_NUMBER',
        'TRADE_FLOW',
        'YEAR',
        'MONTH',

        'HS_SOURCE',
        'HS_CODE',
        'HS_ID',
        'HS_STATUS',

        'COUNTRY_SOURCE',
        'COUNTRY_NORMALIZED',
        'COUNTRY_ID',
        'COUNTRY_CODE',
        'COUNTRY_STATUS',

        'PROVINCE_SOURCE',
        'PROVINCE_NORMALIZED',
        'PROVINCE_ID',
        'PROVINCE_CODE',
        'PROVINCE_STATUS',

        'TRADE_POINT_SOURCE',
        'TRADE_POINT_NORMALIZED',
        'TRADE_POINT_ID',
        'TRADE_POINT_CODE',
        'TRADE_POINT_TYPE_ID',
        'TRADE_POINT_STATUS',

        'TRADE_VALUE',
        'TRADE_VOLUME',

        'OVERALL_STATUS',
        'REMARKS',
    ]
);

/*
|--------------------------------------------------------------------------
| Counters
|--------------------------------------------------------------------------
*/

$totalSourceRows = 0;
$totalMonthlyRows = 0;
$totalZeroMonthsSkipped = 0;

$resolvedHs = 0;
$unresolvedHs = 0;

$resolvedCountry = 0;
$unresolvedCountry = 0;

$resolvedProvince = 0;
$unresolvedProvince = 0;

$resolvedTradePoint = 0;
$unresolvedTradePoint = 0;

$fullyResolved = 0;
$partiallyResolved = 0;

$maxRowSeen = 0;

/*
|--------------------------------------------------------------------------
| Stream worksheet
|--------------------------------------------------------------------------
*/

while ($reader->read()) {

    if (
        $reader->nodeType !== XMLReader::ELEMENT
        ||
        $reader->localName !== 'row'
    ) {
        continue;
    }

    $rowNumber =
        (int) (
            $reader->getAttribute('r')
            ?? 0
        );

    if ($rowNumber < 3) {
        continue;
    }

    $maxRowSeen =
        max(
            $maxRowSeen,
            $rowNumber
        );

    $rowValues = [];

    $rowDepth =
        $reader->depth;

    while ($reader->read()) {

        if (
            $reader->nodeType === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'row'
            &&
            $reader->depth === $rowDepth
        ) {
            break;
        }

        if (
            $reader->nodeType !== XMLReader::ELEMENT
            ||
            $reader->localName !== 'c'
        ) {
            continue;
        }

        $reference =
            (string) (
                $reader->getAttribute('r')
                ?? ''
            );

        if ($reference === '') {
            continue;
        }

        $index =
            columnIndex($reference);

        if (
            $index < 1
            ||
            $index > 29
        ) {
            readCellValue(
                $reader,
                $sharedStrings
            );

            continue;
        }

        $rowValues[$index] =
            readCellValue(
                $reader,
                $sharedStrings
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Source fields
    |--------------------------------------------------------------------------
    */

    $hsSource = trim(
        (string) (
            $rowValues[1] ?? ''
        )
    );

    $countrySource = trim(
        (string) (
            $rowValues[3] ?? ''
        )
    );

    $provinceSource = trim(
        (string) (
            $rowValues[4] ?? ''
        )
    );

    $tradePointSource = trim(
        (string) (
            $rowValues[5] ?? ''
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Empty source row
    |--------------------------------------------------------------------------
    */

    if (
        $hsSource === ''
        &&
        $countrySource === ''
        &&
        $provinceSource === ''
        &&
        $tradePointSource === ''
    ) {
        continue;
    }

    $totalSourceRows++;

    /*
    |--------------------------------------------------------------------------
    | HS resolver
    |--------------------------------------------------------------------------
    */

    $hsCode =
        preg_replace(
            '/\D/',
            '',
            $hsSource
        ) ?? '';

    if (strlen($hsCode) === 7) {
        $hsCode = '0' . $hsCode;
    }

    $hs =
        $hsLookup->get($hsCode);

    if ($hs !== null) {

        $hsStatus = 'RESOLVED';
        $resolvedHs++;
        $hsId = $hs->id_hs;

    } else {

    $hsStatus = 'UNRESOLVED';
    $unresolvedHs++;
    $hsId = null;
}

/*
|--------------------------------------------------------------------------
| Country resolver
|--------------------------------------------------------------------------
*/
$countryNormalized =
    normalizeValue(
        $countrySource
    );

$countryId =
    $countryResolver->resolveId(
        $countrySource,
        $sourceSystem
    );

$countryCode =
    $countryResolver->resolveCode(
        $countrySource,
        $sourceSystem
    );

if ($countryId !== null) {

    $countryStatus =
        'RESOLVED';

    $resolvedCountry++;

} else {

    $countryStatus =
        'UNRESOLVED';

    $unresolvedCountry++;

    $countryId = null;
    $countryCode = null;
}

/*
|--------------------------------------------------------------------------
| Province resolver
|--------------------------------------------------------------------------
*/

    $provinceNormalized =
        normalizeValue(
            $provinceSource
        );

    $province =
        $provinceLookup[
            $provinceNormalized
        ] ?? null;

    if ($province === null) {

        $aliasCode =
            $provinceAliases[
                $provinceNormalized
            ] ?? null;

        if ($aliasCode !== null) {
            $province =
                $provinceByCode[
                    $aliasCode
                ] ?? null;
        }
    }

    if ($province !== null) {

        $provinceStatus = 'RESOLVED';
        $resolvedProvince++;

        $provinceId =
            $province->id;

        $provinceCode =
            $province->code;

    } else {

        $provinceStatus = 'UNRESOLVED';
        $unresolvedProvince++;

        $provinceId = null;
        $provinceCode = null;
    }

    /*
    |--------------------------------------------------------------------------
    | Trade point resolver
    |--------------------------------------------------------------------------
    */

    $tradePointNormalized =
        normalizeValue(
            $tradePointSource
        );

    $tradePoint =
        $tradePointLookup[
            $tradePointNormalized
        ] ?? null;

    if ($tradePoint !== null) {

        $tradePointStatus = 'RESOLVED';
        $resolvedTradePoint++;

        $tradePointId =
            $tradePoint->id;

        $tradePointCode =
            $tradePoint->code;

        $tradePointTypeId =
            $tradePoint->trade_point_type_id;

    } else {

        $tradePointStatus = 'UNRESOLVED';
        $unresolvedTradePoint++;

        $tradePointId = null;
        $tradePointCode = null;
        $tradePointTypeId = null;
    }

   
    /*
    |--------------------------------------------------------------------------
    | Monthly expansion
    |--------------------------------------------------------------------------
    */

    for (
        $month = 1;
        $month <= 12;
        $month++
    ) {

        $valueColumn =
            $months[$month]['value'];

        $volumeColumn =
            $months[$month]['volume'];

        /*
        |--------------------------------------------------------------------------
        | Convert column letter to numeric index
        |--------------------------------------------------------------------------
        */

        $valueIndex =
            columnIndex(
                $valueColumn . '1'
            );

        $volumeIndex =
            columnIndex(
                $volumeColumn . '1'
            );

        $tradeValue =
            numericValue(
                $rowValues[
                    $valueIndex
                ] ?? 0
            );

        $tradeVolume =
            numericValue(
                $rowValues[
                    $volumeIndex
                ] ?? 0
            );

        if (
            abs($tradeValue) < 0.0000001
            &&
            abs($tradeVolume) < 0.0000001
        ) {
            $totalZeroMonthsSkipped++;
            continue;
        }

        $totalMonthlyRows++;

        $allResolved =
            $hsStatus === 'RESOLVED'
            &&
            $countryStatus === 'RESOLVED'
            &&
            $provinceStatus === 'RESOLVED'
            &&
            $tradePointStatus === 'RESOLVED';

        if ($allResolved) {

            $overallStatus =
                'FULLY_RESOLVED';

            $fullyResolved++;

        } else {

            $overallStatus =
                'PARTIALLY_RESOLVED';

            $partiallyResolved++;
        }

        $remarks = [];

        if ($hsStatus !== 'RESOLVED') {
            $remarks[] =
                'HS_UNRESOLVED';
        }

        if ($countryStatus !== 'RESOLVED') {
            $remarks[] =
                'COUNTRY_UNRESOLVED';
        }

        if ($provinceStatus !== 'RESOLVED') {
            $remarks[] =
                'PROVINCE_UNRESOLVED';
        }

        if ($tradePointStatus !== 'RESOLVED') {
            $remarks[] =
                'TRADE_POINT_UNRESOLVED';
        }

        fputcsv(
            $output,
            [
                $rowNumber,
                $tradeFlow,
                $year,
                $month,

                $hsSource,
                $hsCode,
                $hsId,
                $hsStatus,

                $countrySource,
                $countryNormalized,
                $countryId,
                $countryCode,
                $countryStatus,

                $provinceSource,
                $provinceNormalized,
                $provinceId,
                $provinceCode,
                $provinceStatus,

                $tradePointSource,
                $tradePointNormalized,
                $tradePointId,
                $tradePointCode,
                $tradePointTypeId,
                $tradePointStatus,

                $tradeValue,
                $tradeVolume,

                $overallStatus,

                implode(
                    '|',
                    $remarks
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Progress every 5,000 source rows
    |--------------------------------------------------------------------------
    */

    if (
        $totalSourceRows % 5000 === 0
    ) {

        echo sprintf(
            "Processed rows: %d | Monthly non-zero: %d | Memory: %.2f MB\n",
            $totalSourceRows,
            $totalMonthlyRows,
            memory_get_usage(true) /
                1024 /
                1024
        );
    }
}

/*
|--------------------------------------------------------------------------
| Cleanup
|--------------------------------------------------------------------------
*/

$reader->close();

@unlink($tempSheetFile);

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "DIGESTEX STREAMING TRADE DRY-RUN 2019\n";
echo "========================================\n\n";

echo "SOURCE FILE:\n";
echo $sourceFile . "\n\n";

echo "SOURCE ROWS              : "
    . $totalSourceRows
    . PHP_EOL;

echo "MAX SOURCE ROW           : "
    . $maxRowSeen
    . PHP_EOL;

echo "MONTHLY NON-ZERO ROWS    : "
    . $totalMonthlyRows
    . PHP_EOL;

echo "ZERO MONTHS SKIPPED      : "
    . $totalZeroMonthsSkipped
    . PHP_EOL;

echo "\nRESOLUTION:\n";

echo "  HS RESOLVED            : "
    . $resolvedHs
    . PHP_EOL;

echo "  HS UNRESOLVED          : "
    . $unresolvedHs
    . PHP_EOL;

echo "  COUNTRY RESOLVED       : "
    . $resolvedCountry
    . PHP_EOL;

echo "  COUNTRY UNRESOLVED     : "
    . $unresolvedCountry
    . PHP_EOL;

echo "  PROVINCE RESOLVED      : "
    . $resolvedProvince
    . PHP_EOL;

echo "  PROVINCE UNRESOLVED    : "
    . $unresolvedProvince
    . PHP_EOL;

echo "  TRADE POINT RESOLVED   : "
    . $resolvedTradePoint
    . PHP_EOL;

echo "  TRADE POINT UNRESOLVED : "
    . $unresolvedTradePoint
    . PHP_EOL;

echo "\nMONTHLY RESOLUTION:\n";

echo "  FULLY RESOLVED         : "
    . $fullyResolved
    . PHP_EOL;

echo "  PARTIALLY RESOLVED     : "
    . $partiallyResolved
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\nFINAL MEMORY: "
    . number_format(
        memory_get_usage(true) /
        1024 /
        1024,
        2
    )
    . " MB\n";

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";