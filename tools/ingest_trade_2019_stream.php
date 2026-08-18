<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Models\TradeImportBatch;
use App\Services\Trade\CountryResolverService;
use App\Services\Trade\ProvinceResolverService;
use App\Services\Trade\TradePointResolverService;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| DIGESTEX PRODUCTION TRADE INGESTION 2019
|--------------------------------------------------------------------------
|
| Canonical master:
|   mst_hscode
|
| Source:
|   Kemendag EXPORT 2019
|
| Mode:
|   Streaming XLSX
|
| Database:
|   WRITE
|
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

if (!is_file($sourceFile)) {
    throw new RuntimeException(
        "Source file tidak ditemukan:\n{$sourceFile}"
    );
}

const SOURCE_SYSTEM = 'Kemendag';
const SOURCE_SYSTEM_UPPER = 'KEMENDAG';
const TARGET_YEAR = 2019;
const TRADE_FLOW = 'export';
const BATCH_SIZE = 1000;

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
            'digestex_ingest_shared_'
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
            'digestex_ingest_sheet_'
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
| Preflight
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX PRODUCTION TRADE INGESTION 2019\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo $sourceFile . PHP_EOL;
echo "\n";

/*
|--------------------------------------------------------------------------
| Existing 2019 guard
|--------------------------------------------------------------------------
*/

$existing2019 =
    DB::table('trade_statistics')
        ->where(
            'year',
            TARGET_YEAR
        )
        ->count();

if ($existing2019 > 0) {

    throw new RuntimeException(
        "ABORT: trade_statistics sudah memiliki "
        . $existing2019
        . " record untuk tahun "
        . TARGET_YEAR
        . "."
    );
}

echo "PRECHECK:\n";
echo "Existing 2019 rows : 0\n";
echo "Status             : PASS\n";

/*
|--------------------------------------------------------------------------
| Resolver services
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

echo "HS master loaded   : "
    . count($hsLookup)
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Runtime state
|--------------------------------------------------------------------------
*/

$batch = null;
$reader = null;
$sheetXml = null;

$totalSourceRows = 0;
$activeSourceRows = 0;
$zeroActivityRows = 0;

$monthlyRecords = 0;

$fullyResolved = 0;
$partiallyResolved = 0;

$hsUnresolved = 0;
$countryUnresolved = 0;
$provinceUnresolved = 0;
$tradePointUnresolved = 0;

$insertedRows = 0;
$updatedRows = 0;

$skippedRows = 0;

$batchBuffer = [];

$startedAt = microtime(true);

/*
|--------------------------------------------------------------------------
| Production ingestion
|--------------------------------------------------------------------------
*/

try {

    /*
    |--------------------------------------------------------------------------
    | Create batch
    |--------------------------------------------------------------------------
    */

    $batch =
        TradeImportBatch::create([
            'source' =>
                SOURCE_SYSTEM,

            'filename' =>
                basename($sourceFile),

            'trade_flow' =>
                TRADE_FLOW,

            'year' =>
                TARGET_YEAR,

            'release_date' =>
                now(),

            'total_rows' =>
                0,

            'inserted_rows' =>
                0,

            'updated_rows' =>
                0,

            'skipped_rows' =>
                0,

            'failed_rows' =>
                0,

            'status' =>
                'processing',

            'remarks' =>
                'Streaming production ingestion 2019.',

            'created_by' =>
                auth()->id() ?? null,
        ]);

    echo "STEP 1: Batch created "
        . "(ID: {$batch->id}).\n";

    /*
    |--------------------------------------------------------------------------
    | Open workbook
    |--------------------------------------------------------------------------
    */

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

    echo "STEP 2: Workbook opened.\n";

    /*
    |--------------------------------------------------------------------------
    | Shared strings
    |--------------------------------------------------------------------------
    */

    $sharedStrings =
        loadSharedStrings($zip);

    $zip->close();

    echo "STEP 3: Shared strings loaded ("
        . count($sharedStrings)
        . " items).\n";

    /*
    |--------------------------------------------------------------------------
    | Worksheet
    |--------------------------------------------------------------------------
    */

    $sheetXml =
        extractRowsToTemporaryXml(
            $sourceFile,
            'xl/worksheets/sheet1.xml'
        );

    echo "STEP 4: Worksheet extracted.\n";

    /*
    |--------------------------------------------------------------------------
    | XML reader
    |--------------------------------------------------------------------------
    */

    $reader =
        new \XMLReader();

    if (!$reader->open($sheetXml)) {
        throw new RuntimeException(
            'XMLReader gagal membuka worksheet.'
        );
    }

    echo "STEP 5: XMLReader opened.\n";
    echo "STEP 6: Processing rows...\n";

    /*
    |--------------------------------------------------------------------------
    | Row processing
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
            $skippedRows++;
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

        if ($hsId === null) {
            $hsUnresolved++;
        }

        if ($country === null) {
            $countryUnresolved++;
        }

        if ($province === null) {
            $provinceUnresolved++;
        }

        if ($tradePoint === null) {
            $tradePointUnresolved++;
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly expansion
        |--------------------------------------------------------------------------
        */

        $rowHasActiveMonth = false;

        for (
            $month = 1;
            $month <= 12;
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
            | Deterministic trade identity
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

            $allResolved =
                $hsId !== null
                &&
                $country !== null
                &&
                $province !== null
                &&
                $tradePoint !== null;

            if ($allResolved) {
                $fullyResolved++;
            } else {
                $partiallyResolved++;
            }

            $now = now();

            $batchBuffer[] = [

                'import_batch_id' =>
                    $batch->id,

                'source' =>
                    SOURCE_SYSTEM,

                'trade_flow' =>
                    TRADE_FLOW,

                'year' =>
                    TARGET_YEAR,

                'month' =>
                    $month,

                'dimension' =>
                    'country',

                'product' =>
                    null,

                'product_category' =>
                    null,

                'industry_segment' =>
                    null,

                'hs_code' =>
                    $hsCode,

                'hs_description' =>
                    $hsDescription,

                'hs_id' =>
                    $hsId,

                'country_code' =>
                    $country?->country_code,

                'country_name' =>
                    $countryName,

                'country_id' =>
                    $country?->id,

                'province_code' =>
                    $province['code']
                    ?? null,

                'province_name' =>
                    $provinceName,

                'province_id' =>
                    $province['id']
                    ?? null,

                'port_code' =>
                    $tradePoint['code']
                    ?? null,

                'port_name' =>
                    $portName,

                'trade_point_id' =>
                    $tradePoint['id']
                    ?? null,

                'trade_point_type_id' =>
                    $tradePoint[
                        'trade_point_type_id'
                    ] ?? null,

                'trade_identity' =>
                    $tradeIdentity,

                'trade_value' =>
                    $tradeValue,

                'currency' =>
                    'USD',

                'trade_volume' =>
                    $tradeVolume,

                'volume_unit' =>
                    'KG',

                'release_date' =>
                    $now,

                'remarks' =>
                    null,

                'created_at' =>
                    $now,

                'updated_at' =>
                    $now,
            ];

            /*
            |--------------------------------------------------------------------------
            | Flush buffer
            |--------------------------------------------------------------------------
            */

            if (
                count($batchBuffer)
                >= BATCH_SIZE
            ) {

                DB::transaction(
                    function () use (
                        &$batchBuffer,
                        &$insertedRows
                    ): void {

                        DB::table('trade_statistics')
                            ->upsert(
                                $batchBuffer,
                                [
                                    'trade_identity',
                                    'year',
                                    'month',
                                ],
                                [
                                    'import_batch_id',
                                    'source',
                                    'trade_flow',
                                    'dimension',
                                    'product',
                                    'product_category',
                                    'industry_segment',
                                    'hs_code',
                                    'hs_description',
                                    'hs_id',
                                    'country_code',
                                    'country_name',
                                    'country_id',
                                    'province_code',
                                    'province_name',
                                    'province_id',
                                    'port_code',
                                    'port_name',
                                    'trade_point_id',
                                    'trade_point_type_id',
                                    'trade_value',
                                    'currency',
                                    'trade_volume',
                                    'volume_unit',
                                    'release_date',
                                    'remarks',
                                    'updated_at',
                                ]
                            );

                        $insertedRows +=
                            count($batchBuffer);

                        $batchBuffer = [];
                    }
                );
            }
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
                "Processed rows: %d | Monthly: %d | Memory: %.2f MB\n",
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
    | Flush remaining records
    |--------------------------------------------------------------------------
    */

    if (!empty($batchBuffer)) {

        DB::transaction(
            function () use (
                &$batchBuffer,
                &$insertedRows
            ): void {

                DB::table('trade_statistics')
                    ->upsert(
                        $batchBuffer,
                        [
                            'trade_identity',
                            'year',
                            'month',
                        ],
                        [
                            'import_batch_id',
                            'source',
                            'trade_flow',
                            'dimension',
                            'product',
                            'product_category',
                            'industry_segment',
                            'hs_code',
                            'hs_description',
                            'hs_id',
                            'country_code',
                            'country_name',
                            'country_id',
                            'province_code',
                            'province_name',
                            'province_id',
                            'port_code',
                            'port_name',
                            'trade_point_id',
                            'trade_point_type_id',
                            'trade_value',
                            'currency',
                            'trade_volume',
                            'volume_unit',
                            'release_date',
                            'remarks',
                            'updated_at',
                        ]
                    );

                $insertedRows +=
                    count($batchBuffer);

                $batchBuffer = [];
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cleanup
    |--------------------------------------------------------------------------
    */

    $reader->close();

    if (
        $sheetXml !== null
        &&
        is_file($sheetXml)
    ) {
        @unlink($sheetXml);
    }

    /*
    |--------------------------------------------------------------------------
    | Finalize batch
    |--------------------------------------------------------------------------
    */

    $duration =
        round(
            microtime(true)
            - $startedAt,
            2
        );

    $batch->update([

        'total_rows' =>
            $monthlyRecords,

        'inserted_rows' =>
            $insertedRows,

        'updated_rows' =>
            $updatedRows,

        'skipped_rows' =>
            $skippedRows,

        'failed_rows' =>
            0,

        'status' =>
            'completed',

        'remarks' =>
            sprintf(
                '2019 streaming ingestion completed. Source rows: %d, active source rows: %d, monthly records: %d, fully resolved: %d, partially resolved: %d, duration: %.2f seconds.',
                $totalSourceRows,
                $activeSourceRows,
                $monthlyRecords,
                $fullyResolved,
                $partiallyResolved,
                $duration
            ),
    ]);

} catch (\Throwable $e) {

    if ($reader instanceof \XMLReader) {
        try {
            $reader->close();
        } catch (\Throwable $ignored) {
        }
    }

    if (
        $sheetXml !== null
        && is_file($sheetXml)
    ) {
        @unlink($sheetXml);
    }

    fwrite(
        STDERR,
        PHP_EOL .
        "========================================" . PHP_EOL .
        "INGESTION FAILED" . PHP_EOL .
        "========================================" . PHP_EOL .
        "Exception : " . $e::class . PHP_EOL .
        "Code      : " . $e->getCode() . PHP_EOL .
        "Message   : " . $e->getMessage() . PHP_EOL
    );

    if ($e instanceof \Illuminate\Database\QueryException) {
        fwrite(
            STDERR,
            "SQLSTATE   : "
            . ($e->errorInfo[0] ?? '')
            . PHP_EOL
            . "DriverCode : "
            . ($e->errorInfo[1] ?? '')
            . PHP_EOL
            . "DriverMsg  : "
            . ($e->errorInfo[2] ?? '')
            . PHP_EOL
        );
    }

    if ($batch !== null) {
        $batch->update([
            'total_rows' =>
                $monthlyRecords ?? 0,

            'inserted_rows' =>
                $insertedRows ?? 0,

            'updated_rows' =>
                $updatedRows ?? 0,

            'skipped_rows' =>
                $skippedRows ?? 0,

            'failed_rows' =>
                1,

            'status' =>
                'failed',

            'remarks' =>
                mb_substr(
                    'FAILED: '
                    . $e::class
                    . ' | '
                    . $e->getMessage(),
                    0,
                    1000
                ),
        ]);
    }

    throw $e;
}

/*
|--------------------------------------------------------------------------
| Final verification
|--------------------------------------------------------------------------
*/

$dbCount =
    DB::table('trade_statistics')
        ->where(
            'year',
            TARGET_YEAR
        )
        ->count();

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "DIGESTEX TRADE INGESTION 2019 COMPLETE\n";
echo "========================================\n\n";

echo "SOURCE ROWS              : "
    . $totalSourceRows
    . PHP_EOL;

echo "ACTIVE SOURCE ROWS       : "
    . $activeSourceRows
    . PHP_EOL;

echo "ZERO-ACTIVITY ROWS       : "
    . $zeroActivityRows
    . PHP_EOL;

echo "MONTHLY RECORDS          : "
    . $monthlyRecords
    . PHP_EOL;

echo PHP_EOL;
echo "RESOLUTION:\n";

echo "  HS UNRESOLVED          : "
    . $hsUnresolved
    . PHP_EOL;

echo "  COUNTRY UNRESOLVED     : "
    . $countryUnresolved
    . PHP_EOL;

echo "  PROVINCE UNRESOLVED    : "
    . $provinceUnresolved
    . PHP_EOL;

echo "  TRADE POINT UNRESOLVED : "
    . $tradePointUnresolved
    . PHP_EOL;

echo PHP_EOL;
echo "MONTHLY QUALITY:\n";

echo "  FULLY RESOLVED         : "
    . $fullyResolved
    . PHP_EOL;

echo "  PARTIALLY RESOLVED     : "
    . $partiallyResolved
    . PHP_EOL;

echo PHP_EOL;
echo "DATABASE:\n";

echo "  2019 ROWS              : "
    . $dbCount
    . PHP_EOL;

echo "  BATCH ID               : "
    . $batch->id
    . PHP_EOL;

echo "  STATUS                 : "
    . $batch->status
    . PHP_EOL;

echo PHP_EOL;
echo "FINAL MEMORY             : "
    . number_format(
        memory_get_peak_usage(true)
            / 1024
            / 1024,
        2
    )
    . " MB\n";

echo PHP_EOL;
echo "========================================\n";
echo "PRODUCTION INGESTION FINISHED.\n";
echo "========================================\n";