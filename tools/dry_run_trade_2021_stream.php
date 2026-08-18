<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\CountryResolverService;
use App\Services\Trade\ProvinceResolverService;
use App\Services\Trade\TradePointResolverService;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| DIGESTEX STREAMING TRADE DRY-RUN 2021
|--------------------------------------------------------------------------
|
| Source:
|   Kemendag EXPORT 2021
|
| Mode:
|   READ ONLY
|
| Output:
|   dry_run_export_2021.csv
|
|--------------------------------------------------------------------------
*/

const SOURCE_SYSTEM = 'Kemendag';
const SOURCE_SYSTEM_UPPER = 'KEMENDAG';
const TARGET_YEAR = 2021;
const TRADE_FLOW = 'export';

const MONTH_START = 1;
const MONTH_END = 12;

const OUTPUT_FILE =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\PROCESSED\\dry_run_export_2021.csv';

/*
|--------------------------------------------------------------------------
| Source file
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
    . 'ekspor 2021.xlsx';

if (!is_file($sourceFile)) {
    throw new RuntimeException(
        "Source file tidak ditemukan:\n{$sourceFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function normalizeTradeValue(?string $value): string
{
    $value = trim((string) $value);

    if ($value === '') {
        return '';
    }

    $value =
        preg_replace(
            '/\s+/',
            ' ',
            $value
        ) ?? '';

    return mb_strtoupper($value);
}

function toTradeNumber(mixed $value): float
{
    if ($value === null) {
        return 0.0;
    }

    $value = trim((string) $value);

    if ($value === '') {
        return 0.0;
    }

    $value =
        str_replace(
            ',',
            '',
            $value
        );

    return is_numeric($value)
        ? (float) $value
        : 0.0;
}

function xlsxColumnIndex(
    string $reference
): int {
    if (
        !preg_match(
            '/^([A-Z]+)\d+$/i',
            $reference,
            $match
        )
    ) {
        return 0;
    }

    $letters =
        strtoupper($match[1]);

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

function readCellValue(
    \XMLReader $reader,
    array $sharedStrings
): mixed {
    $cellType =
        $reader->getAttribute('t');

    $cellDepth =
        $reader->depth;

    $value = null;

    while ($reader->read()) {

        if (
            $reader->nodeType
                === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'c'
            &&
            $reader->depth === $cellDepth
        ) {
            break;
        }

        if (
            $reader->nodeType
                === \XMLReader::ELEMENT
            &&
            $reader->localName === 'v'
        ) {
            $raw =
                $reader->readString();

            $value =
                $cellType === 's'
                    ? (
                        $sharedStrings[
                            (int) $raw
                        ] ?? ''
                    )
                    : $raw;

            continue;
        }

        if (
            $reader->nodeType
                === \XMLReader::ELEMENT
            &&
            $reader->localName === 'is'
        ) {
            $text = '';

            while ($reader->read()) {

                if (
                    $reader->nodeType
                        === \XMLReader::ELEMENT
                    &&
                    $reader->localName === 't'
                ) {
                    $text .=
                        $reader->readString();
                }

                if (
                    $reader->nodeType
                        === \XMLReader::END_ELEMENT
                    &&
                    $reader->localName === 'is'
                ) {
                    break;
                }
            }

            $value = $text;
        }
    }

    return $value;
}

/*
|--------------------------------------------------------------------------
| Load shared strings
|--------------------------------------------------------------------------
*/

function loadSharedStrings(
    \ZipArchive $zip
): array {
    $index =
        $zip->locateName(
            'xl/sharedStrings.xml',
            \ZipArchive::FL_NOCASE
        );

    if ($index === false) {
        return [];
    }

    $name =
        $zip->getNameIndex($index);

    $stream =
        $zip->getStream($name);

    if ($stream === false) {
        throw new RuntimeException(
            'Tidak dapat membuka sharedStrings.xml.'
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_dryrun_shared_'
        );

    if ($temporary === false) {
        fclose($stream);

        throw new RuntimeException(
            'Gagal membuat temporary shared strings.'
        );
    }

    $handle =
        fopen(
            $temporary,
            'wb'
        );

    if ($handle === false) {
        fclose($stream);
        @unlink($temporary);

        throw new RuntimeException(
            'Gagal membuka temporary shared strings.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);

    $reader =
        new \XMLReader();

    if (!$reader->open($temporary)) {
        @unlink($temporary);

        throw new RuntimeException(
            'XMLReader gagal membuka sharedStrings.'
        );
    }

    $strings = [];
    $current = null;

    while ($reader->read()) {

        if (
            $reader->nodeType
                === \XMLReader::ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $current = '';

            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType
                === \XMLReader::ELEMENT
            &&
            $reader->localName === 't'
        ) {
            $current .=
                $reader->readString();

            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType
                === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $strings[] = $current;
            $current = null;
        }
    }

    $reader->close();

    @unlink($temporary);

    return $strings;
}

/*
|--------------------------------------------------------------------------
| Extract worksheet
|--------------------------------------------------------------------------
*/

function extractRowsToTemporaryXml(
    string $sourceFile,
    string $worksheet
): string {
    $zip =
        new \ZipArchive();

    if (
        $zip->open($sourceFile)
        !== true
    ) {
        throw new RuntimeException(
            'Tidak dapat membuka workbook.'
        );
    }

    $index =
        $zip->locateName(
            $worksheet,
            \ZipArchive::FL_NOCASE
        );

    if ($index === false) {
        $zip->close();

        throw new RuntimeException(
            "Worksheet tidak ditemukan: {$worksheet}"
        );
    }

    $name =
        $zip->getNameIndex($index);

    $stream =
        $zip->getStream($name);

    if ($stream === false) {
        $zip->close();

        throw new RuntimeException(
            "Tidak dapat membaca worksheet: {$worksheet}"
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_dryrun_sheet_'
        );

    if ($temporary === false) {
        fclose($stream);
        $zip->close();

        throw new RuntimeException(
            'Gagal membuat temporary worksheet.'
        );
    }

    $handle =
        fopen(
            $temporary,
            'wb'
        );

    if ($handle === false) {
        fclose($stream);
        $zip->close();
        @unlink($temporary);

        throw new RuntimeException(
            'Gagal membuka temporary worksheet.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);
    $zip->close();

    return $temporary;
}

/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX STREAMING TRADE DRY-RUN 2021\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo $sourceFile . PHP_EOL;
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolvers
|--------------------------------------------------------------------------
*/

$countryResolver =
    app(
        CountryResolverService::class
    );

$provinceResolver =
    app(
        ProvinceResolverService::class
    );

$tradePointResolver =
    app(
        TradePointResolverService::class
    );

/*
|--------------------------------------------------------------------------
| Canonical HS master
|--------------------------------------------------------------------------
*/

$hsLookup =
    DB::table('mst_hscode')
        ->where(
            'is_active',
            true
        )
        ->pluck(
            'id_hs',
            'hs_code'
        )
        ->toArray();

if (empty($hsLookup)) {
    throw new RuntimeException(
        'Canonical HS master mst_hscode kosong.'
    );
}

/*
|--------------------------------------------------------------------------
| Prepare output
|--------------------------------------------------------------------------
*/

$output =
    fopen(
        OUTPUT_FILE,
        'wb'
    );

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat output:\n"
        . OUTPUT_FILE
    );
}

fputcsv(
    $output,
    [
        'ROW_NUMBER',
        'HS_CODE',
        'HS_DESCRIPTION',
        'COUNTRY_SOURCE',
        'COUNTRY_CODE',
        'COUNTRY_ID',
        'PROVINCE_SOURCE',
        'PROVINCE_CODE',
        'PROVINCE_ID',
        'TRADE_POINT_SOURCE',
        'TRADE_POINT_CODE',
        'TRADE_POINT_ID',
        'TRADE_POINT_TYPE_ID',
        'MONTH',
        'TRADE_VALUE',
        'TRADE_VOLUME',
        'TRADE_IDENTITY',
        'HS_STATUS',
        'COUNTRY_STATUS',
        'PROVINCE_STATUS',
        'TRADE_POINT_STATUS',
        'RESOLUTION_STATUS',
    ]
);

/*
|--------------------------------------------------------------------------
| Open workbook
|--------------------------------------------------------------------------
*/

$zip =
    new \ZipArchive();

if ($zip->open($sourceFile) !== true) {

    fclose($output);

    throw new RuntimeException(
        'Tidak dapat membuka workbook.'
    );
}

$sharedStrings =
    loadSharedStrings($zip);

$zip->close();

echo "Loading shared strings...\n";
echo "Shared strings loaded : "
    . count($sharedStrings)
    . PHP_EOL;

$sheetXml =
    extractRowsToTemporaryXml(
        $sourceFile,
        'xl/worksheets/sheet1.xml'
    );

$reader =
    new \XMLReader();

if (!$reader->open($sheetXml)) {

    fclose($output);
    @unlink($sheetXml);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

/*
|--------------------------------------------------------------------------
| Counters
|--------------------------------------------------------------------------
*/

$totalSourceRows = 0;
$activeSourceRows = 0;
$zeroActivityRows = 0;

$monthlyRecords = 0;

$hsResolved = 0;
$hsUnresolved = 0;

$countryResolved = 0;
$countryUnresolved = 0;

$provinceResolved = 0;
$provinceUnresolved = 0;

$tradePointResolved = 0;
$tradePointUnresolved = 0;

$fullyResolved = 0;
$partiallyResolved = 0;

$uniqueIdentities = [];
$duplicateIdentityCount = 0;

/*
|--------------------------------------------------------------------------
| Processing
|--------------------------------------------------------------------------
*/

while ($reader->read()) {

    if (
        $reader->nodeType
            !== \XMLReader::ELEMENT
        ||
        $reader->localName !== 'row'
    ) {
        continue;
    }

    $sourceRow =
        (int) (
            $reader->getAttribute('r')
            ?? 0
        );

    if ($sourceRow < 3) {
        continue;
    }

    $rowDepth =
        $reader->depth;

    $values = [];

    while ($reader->read()) {

        if (
            $reader->nodeType
                === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'row'
            &&
            $reader->depth === $rowDepth
        ) {
            break;
        }

        if (
            $reader->nodeType
                !== \XMLReader::ELEMENT
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

        $columnIndex =
            xlsxColumnIndex(
                $reference
            );

        if (
            $columnIndex < 1
            ||
            $columnIndex > 29
        ) {
            readCellValue(
                $reader,
                $sharedStrings
            );

            continue;
        }

        $values[$columnIndex] =
            readCellValue(
                $reader,
                $sharedStrings
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Source columns
    |--------------------------------------------------------------------------
    */

    $hsCode =
        trim(
            (string) (
                $values[1] ?? ''
            )
        );

    $hsDescription =
        trim(
            (string) (
                $values[2] ?? ''
            )
        );

    $countryName =
        trim(
            (string) (
                $values[3] ?? ''
            )
        );

    $provinceName =
        trim(
            (string) (
                $values[4] ?? ''
            )
        );

    $portName =
        trim(
            (string) (
                $values[5] ?? ''
            )
        );

    if ($hsCode === '') {
        continue;
    }

    $totalSourceRows++;

    /*
    |--------------------------------------------------------------------------
    | HS resolution
    |--------------------------------------------------------------------------
    */

    $cleanHsCode =
        trim($hsCode);

    $hsId =
        $hsLookup[
            $cleanHsCode
        ] ?? null;

    if ($hsId !== null) {
        $hsResolved++;
        $hsStatus = 'RESOLVED';
    } else {
        $hsUnresolved++;
        $hsStatus = 'UNRESOLVED';
    }

    /*
    |--------------------------------------------------------------------------
    | Geography resolution
    |--------------------------------------------------------------------------
    */

    $country =
        $countryResolver->resolve(
            $countryName,
            SOURCE_SYSTEM_UPPER
        );

    $province =
        $provinceResolver->resolve(
            $provinceName
        );

    $tradePoint =
        $tradePointResolver->resolve(
            $portName,
            SOURCE_SYSTEM_UPPER
        );

    if ($country !== null) {
        $countryResolved++;
        $countryStatus = 'RESOLVED';
    } else {
        $countryUnresolved++;
        $countryStatus = 'UNRESOLVED';
    }

    if ($province !== null) {
        $provinceResolved++;
        $provinceStatus = 'RESOLVED';
    } else {
        $provinceUnresolved++;
        $provinceStatus = 'UNRESOLVED';
    }

    if ($tradePoint !== null) {
        $tradePointResolved++;
        $tradePointStatus = 'RESOLVED';
    } else {
        $tradePointUnresolved++;
        $tradePointStatus = 'UNRESOLVED';
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly expansion
    |--------------------------------------------------------------------------
    */

    $rowHasActiveMonth = false;

    for (
        $month = MONTH_START;
        $month <= MONTH_END;
        $month++
    ) {

        $valueColumn =
            5 + $month;

        $volumeColumn =
            17 + $month;

        $tradeValue =
            toTradeNumber(
                $values[
                    $valueColumn
                ] ?? 0
            );

        $tradeVolume =
            toTradeNumber(
                $values[
                    $volumeColumn
                ] ?? 0
            );

        if (
            $tradeValue == 0
            &&
            $tradeVolume == 0
        ) {
            continue;
        }

        $rowHasActiveMonth = true;
        $monthlyRecords++;

        /*
        |--------------------------------------------------------------------------
        | Identity
        |--------------------------------------------------------------------------
        */

        $tradeIdentity =
            hash(
                'sha256',
                implode(
                    '|',
                    [
                        SOURCE_SYSTEM,
                        TRADE_FLOW,
                        TARGET_YEAR,
                        $month,
                        normalizeTradeValue(
                            $hsCode
                        ),
                        normalizeTradeValue(
                            $countryName
                        ),
                        normalizeTradeValue(
                            $provinceName
                        ),
                        normalizeTradeValue(
                            $portName
                        ),
                    ]
                )
            );

        if (
            isset(
                $uniqueIdentities[
                    $tradeIdentity
                ]
            )
        ) {
            $duplicateIdentityCount++;
        }

        $uniqueIdentities[
            $tradeIdentity
        ] = true;

        /*
        |--------------------------------------------------------------------------
        | Resolution
        |--------------------------------------------------------------------------
        */

        $allResolved =
            $hsStatus === 'RESOLVED'
            &&
            $countryStatus === 'RESOLVED'
            &&
            $provinceStatus === 'RESOLVED'
            &&
            $tradePointStatus === 'RESOLVED';

        if ($allResolved) {
            $fullyResolved++;
            $resolutionStatus =
                'FULLY_RESOLVED';
        } else {
            $partiallyResolved++;
            $resolutionStatus =
                'PARTIALLY_RESOLVED';
        }

        /*
        |--------------------------------------------------------------------------
        | CSV output
        |--------------------------------------------------------------------------
        */

        fputcsv(
            $output,
            [
                $sourceRow,
                $hsCode,
                $hsDescription,

                $countryName,
                $country?->country_code,
                $country?->id,

                $provinceName,
                $province['code'] ?? null,
                $province['id'] ?? null,

                $portName,
                $tradePoint['code'] ?? null,
                $tradePoint['id'] ?? null,
                $tradePoint[
                    'trade_point_type_id'
                ] ?? null,

                $month,

                $tradeValue,
                $tradeVolume,

                $tradeIdentity,

                $hsStatus,
                $countryStatus,
                $provinceStatus,
                $tradePointStatus,

                $resolutionStatus,
            ]
        );
    }

    if ($rowHasActiveMonth) {
        $activeSourceRows++;
    } else {
        $zeroActivityRows++;
    }

    /*
    |--------------------------------------------------------------------------
    | Progress
    |--------------------------------------------------------------------------
    */

    if (
        $totalSourceRows > 0
        &&
        $totalSourceRows % 5000 === 0
    ) {

        echo sprintf(
            "Processed rows: %d | Monthly non-zero: %d | Memory: %.2f MB\n",
            $totalSourceRows,
            $monthlyRecords,
            memory_get_usage(true)
                / 1024
                / 1024
        );
    }
}

/*
|--------------------------------------------------------------------------
| Cleanup
|--------------------------------------------------------------------------
*/

$reader->close();

@unlink($sheetXml);

fclose($output);

/*
|--------------------------------------------------------------------------
| Final statistics
|--------------------------------------------------------------------------
*/

$uniqueIdentityTotal =
    count($uniqueIdentities);

$memory =
    memory_get_peak_usage(true)
    / 1024
    / 1024;

echo PHP_EOL;
echo "========================================\n";
echo "DIGESTEX STREAMING TRADE DRY-RUN 2021\n";
echo "========================================\n\n";

echo "SOURCE FILE:\n";
echo $sourceFile . PHP_EOL;
echo PHP_EOL;

echo "SOURCE ROWS              : "
    . $totalSourceRows
    . PHP_EOL;

echo "ACTIVE SOURCE ROWS      : "
    . $activeSourceRows
    . PHP_EOL;

echo "ZERO-ACTIVITY ROWS      : "
    . $zeroActivityRows
    . PHP_EOL;

echo "MONTHLY NON-ZERO        : "
    . $monthlyRecords
    . PHP_EOL;

echo PHP_EOL;
echo "RESOLUTION:\n";

echo "  HS RESOLVED           : "
    . $hsResolved
    . PHP_EOL;

echo "  HS UNRESOLVED         : "
    . $hsUnresolved
    . PHP_EOL;

echo "  COUNTRY RESOLVED      : "
    . $countryResolved
    . PHP_EOL;

echo "  COUNTRY UNRESOLVED    : "
    . $countryUnresolved
    . PHP_EOL;

echo "  PROVINCE RESOLVED     : "
    . $provinceResolved
    . PHP_EOL;

echo "  PROVINCE UNRESOLVED   : "
    . $provinceUnresolved
    . PHP_EOL;

echo "  TRADE POINT RESOLVED  : "
    . $tradePointResolved
    . PHP_EOL;

echo "  TRADE POINT UNRESOLVED: "
    . $tradePointUnresolved
    . PHP_EOL;

echo PHP_EOL;
echo "MONTHLY RESOLUTION:\n";

echo "  FULLY RESOLVED        : "
    . $fullyResolved
    . PHP_EOL;

echo "  PARTIALLY RESOLVED    : "
    . $partiallyResolved
    . PHP_EOL;

$fullyResolvedPct =
    $monthlyRecords > 0
        ? (
            $fullyResolved
            / $monthlyRecords
            * 100
        )
        : 0;

echo "  FULLY RESOLVED %      : "
    . number_format(
        $fullyResolvedPct,
        2
    )
    . "%"
    . PHP_EOL;

echo PHP_EOL;
echo "IDENTITY:\n";

echo "  UNIQUE IDENTITIES     : "
    . $uniqueIdentityTotal
    . PHP_EOL;

echo "  DUPLICATE IDENTITIES  : "
    . $duplicateIdentityCount
    . PHP_EOL;

echo PHP_EOL;
echo "OUTPUT:\n";

echo OUTPUT_FILE . PHP_EOL;

echo PHP_EOL;
echo "FINAL MEMORY            : "
    . number_format(
        $memory,
        2
    )
    . " MB\n";

echo PHP_EOL;
echo "========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";