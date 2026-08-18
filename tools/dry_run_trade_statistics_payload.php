<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HsCode;
use App\Services\Trade\CountryResolverService;
use App\Services\Trade\ProvinceResolverService;
use App\Services\Trade\TradePointResolverService;

/*
|--------------------------------------------------------------------------
| DIGESTEX TRADE STATISTICS PRODUCTION PAYLOAD DRY-RUN
|--------------------------------------------------------------------------
*/

$base = getenv('USERPROFILE') . DIRECTORY_SEPARATOR . 'Desktop' . DIRECTORY_SEPARATOR . 'DIGESTEX_DATA';
$sourceFile = $base . DIRECTORY_SEPARATOR . 'KEMENDAG' . DIRECTORY_SEPARATOR . 'EXPORT' . DIRECTORY_SEPARATOR . 'ekspor 2019.xlsx';
$outputFile = $base . DIRECTORY_SEPARATOR . 'PROCESSED' . DIRECTORY_SEPARATOR . 'dry_run_trade_statistics_payload_2019.csv';

if (!is_file($sourceFile)) {
    throw new RuntimeException("Source file tidak ditemukan:\n{$sourceFile}");
}

/*
|--------------------------------------------------------------------------
| Services & Lookups
|--------------------------------------------------------------------------
*/

$countryResolver    = app(CountryResolverService::class);
$provinceResolver   = app(ProvinceResolverService::class);
$tradePointResolver = app(TradePointResolverService::class);

// PERBAIKAN 1: Inisialisasi $hsLookup dari database
$hsLookup = HsCode::query()
    ->where('is_active', true)
    ->pluck('id_hs', 'hs_code')
    ->toArray();

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function normalizePayloadValue(?string $value): string {
    $value = trim((string) $value);
    if ($value === '') return '';
    $value = preg_replace('/\s+/', ' ', $value) ?? '';
    return mb_strtoupper($value);
}

function toPayloadNumber(mixed $value): float {
    if ($value === null) return 0.0;
    $value = trim((string) $value);
    if ($value === '') return 0.0;
    $value = str_replace(',', '', $value);
    return is_numeric($value) ? (float) $value : 0.0;
}

/*
|--------------------------------------------------------------------------
| Read source through streaming XLSX reader
|--------------------------------------------------------------------------
*/

$zip = new \ZipArchive();
if ($zip->open($sourceFile) !== true) {
    throw new RuntimeException('Tidak dapat membuka XLSX.');
}

$sharedStrings = [];
$sharedIndex = $zip->locateName('xl/sharedStrings.xml', \ZipArchive::FL_NOCASE);

if ($sharedIndex !== false) {
    $sharedName = $zip->getNameIndex($sharedIndex);
    $sharedStream = $zip->getStream($sharedName);

    if ($sharedStream === false) {
        $zip->close();
        throw new RuntimeException('Tidak dapat membuka sharedStrings.xml.');
    }

    $tempShared = tempnam(sys_get_temp_dir(), 'digestex_payload_shared_');
    if ($tempShared === false) {
        fclose($sharedStream);
        $zip->close();
        throw new RuntimeException('Tidak dapat membuat temporary file.');
    }

    $tempHandle = fopen($tempShared, 'wb');
    stream_copy_to_stream($sharedStream, $tempHandle);
    fclose($sharedStream);
    fclose($tempHandle);

    $reader = new \XMLReader();
    if (!$reader->open($tempShared)) {
        @unlink($tempShared);
        $zip->close();
        throw new RuntimeException('XMLReader gagal membuka shared strings.');
    }

    $current = null;
    while ($reader->read()) {
        if ($reader->nodeType === \XMLReader::ELEMENT && $reader->localName === 'si') {
            $current = '';
            continue;
        }
        if ($current !== null && $reader->nodeType === \XMLReader::ELEMENT && $reader->localName === 't') {
            $current .= $reader->readString();
            continue;
        }
        if ($current !== null && $reader->nodeType === \XMLReader::END_ELEMENT && $reader->localName === 'si') {
            $sharedStrings[] = $current;
            $current = null;
        }
    }
    $reader->close();
    @unlink($tempShared);
}

echo "========================================\n";
echo "DIGESTEX TRADE STATISTICS PAYLOAD DRY-RUN\n";
echo "========================================\n\n";
echo "SOURCE:\n" . $sourceFile . PHP_EOL;
echo "\nLoading shared strings...\nShared strings loaded : " . count($sharedStrings) . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Worksheet Parsing
|--------------------------------------------------------------------------
*/

$sheetIndex = $zip->locateName('xl/worksheets/sheet1.xml', \ZipArchive::FL_NOCASE);
if ($sheetIndex === false) {
    $zip->close();
    throw new RuntimeException('Sheet1 tidak ditemukan.');
}

$sheetName = $zip->getNameIndex($sheetIndex);
$sheetStream = $zip->getStream($sheetName);
if ($sheetStream === false) {
    $zip->close();
    throw new RuntimeException('Tidak dapat membuka Sheet1.');
}

$tempSheet = tempnam(sys_get_temp_dir(), 'digestex_payload_sheet_');
$tempSheetHandle = fopen($tempSheet, 'wb');
stream_copy_to_stream($sheetStream, $tempSheetHandle);
fclose($sheetStream);
fclose($tempSheetHandle);
$zip->close();

function payloadColumnIndex(string $reference): int {
    if (!preg_match('/^([A-Z]+)\d+$/i', $reference, $match)) return 0;
    $letters = strtoupper($match[1]);
    $index = 0;
    for ($i = 0; $i < strlen($letters); $i++) {
        $index = ($index * 26) + (ord($letters[$i]) - 64);
    }
    return $index;
}

function payloadCellValue(\XMLReader $reader, array $sharedStrings): mixed {
    $cellType = $reader->getAttribute('t');
    $cellDepth = $reader->depth;
    $value = null;

    while ($reader->read()) {
        if ($reader->nodeType === \XMLReader::END_ELEMENT && $reader->localName === 'c' && $reader->depth === $cellDepth) {
            break;
        }
        if ($reader->nodeType === \XMLReader::ELEMENT && $reader->localName === 'v') {
            $raw = $reader->readString();
            $value = ($cellType === 's') ? ($sharedStrings[(int) $raw] ?? '') : $raw;
        }
    }
    return $value;
}

/*
|--------------------------------------------------------------------------
| Scan Rows
|--------------------------------------------------------------------------
*/

$totalSourceRows = 0;
$activeSourceRows = 0;
$monthlyRecords = 0;
$fullyResolved = 0;
$partiallyResolved = 0;

$hsResolved = 0; $hsUnresolved = 0;
$countryResolved = 0; $countryUnresolved = 0;
$provinceResolved = 0; $provinceUnresolved = 0;
$tradePointResolved = 0; $tradePointUnresolved = 0;

$identityCount = [];
$sampleRows = [];

$reader = new \XMLReader();
if (!$reader->open($tempSheet)) {
    @unlink($tempSheet);
    throw new RuntimeException('XMLReader gagal membuka worksheet.');
}

while ($reader->read()) {
    if ($reader->nodeType !== \XMLReader::ELEMENT || $reader->localName !== 'row') {
        continue;
    }

    $rowNumber = (int) ($reader->getAttribute('r') ?? 0);
    if ($rowNumber < 3) continue;

    $rowDepth = $reader->depth;
    $values = [];

    while ($reader->read()) {
        if ($reader->nodeType === \XMLReader::END_ELEMENT && $reader->localName === 'row' && $reader->depth === $rowDepth) {
            break;
        }
        if ($reader->nodeType !== \XMLReader::ELEMENT || $reader->localName !== 'c') {
            continue;
        }

        $reference = (string) ($reader->getAttribute('r') ?? '');
        $columnIndex = payloadColumnIndex($reference);

        if ($columnIndex < 1 || $columnIndex > 29) {
            payloadCellValue($reader, $sharedStrings);
            continue;
        }
        $values[$columnIndex] = payloadCellValue($reader, $sharedStrings);
    }

    $hsCode        = trim((string) ($values[1] ?? ''));
    $hsDescription = trim((string) ($values[2] ?? ''));
    $countryName   = trim((string) ($values[3] ?? ''));
    $provinceName  = trim((string) ($values[4] ?? ''));
    $portName      = trim((string) ($values[5] ?? ''));

    if ($hsCode === '') continue;

    $totalSourceRows++;
    $tradeFlow = 'export';

    /*
    |--------------------------------------------------------------------------
    | Resolvers
    |--------------------------------------------------------------------------
    */

    $country    = $countryResolver->resolve($countryName, 'KEMENDAG');
    $province   = $provinceResolver->resolve($provinceName);
    $tradePoint = $tradePointResolver->resolve($portName, 'KEMENDAG');

    if ($country !== null) $countryResolved++; else $countryUnresolved++;
    if ($province !== null) $provinceResolved++; else $provinceUnresolved++;
    if ($tradePoint !== null) $tradePointResolved++; else $tradePointUnresolved++;

    /*
    |--------------------------------------------------------------------------
    | HS Validation
    |--------------------------------------------------------------------------
    */

    // Normalisasi format HS Code (Misal memasukkan pad 0 jika 7 digit)
    $cleanHsCode = preg_replace('/\D/', '', $hsCode) ?? '';
    if (strlen($cleanHsCode) === 7) $cleanHsCode = '0' . $cleanHsCode;

    $hsId = $hsLookup[$cleanHsCode] ?? $hsLookup[$hsCode] ?? null;
    $hsExists = $hsId !== null;

    if ($hsExists) $hsResolved++; else $hsUnresolved++;

    /*
    |--------------------------------------------------------------------------
    | Monthly Expansion
    |--------------------------------------------------------------------------
    */

    $rowHasActiveMonth = false;

    for ($month = 1; $month <= 12; $month++) {
        $valueColumn  = 5 + $month;
        $volumeColumn = 17 + $month;

        $tradeValue  = toPayloadNumber($values[$valueColumn] ?? 0);
        $tradeVolume = toPayloadNumber($values[$volumeColumn] ?? 0);

        if ($tradeValue == 0 && $tradeVolume == 0) {
            continue;
        }

        $rowHasActiveMonth = true;
        $monthlyRecords++;

        $tradeIdentity = hash('sha256', implode('|', [
            'Kemendag',
            $tradeFlow,
            2019,
            $month,
            normalizePayloadValue($hsCode),
            normalizePayloadValue($countryName),
            normalizePayloadValue($provinceName),
            normalizePayloadValue($portName),
        ]));

        $identityCount[$tradeIdentity] = ($identityCount[$tradeIdentity] ?? 0) + 1;

        $allResolved = $hsExists && $country !== null && $province !== null && $tradePoint !== null;
        if ($allResolved) $fullyResolved++; else $partiallyResolved++;

        /*
        |--------------------------------------------------------------------------
        | Sample Payloads
        |--------------------------------------------------------------------------
        */

        if (count($sampleRows) < 10) {
            // PERBAIKAN 2: Menggunakan Nullsafe Operator (?->) secara konsisten
            $sampleRows[] = [
                'row'                 => $rowNumber,
                'month'               => $month,
                'trade_identity'      => $tradeIdentity,
                'hs_code'             => $hsCode,
                'country_code'        => $country?->country_code,
                'country_id'          => $country?->id,
                'province_code'       => $province?->code ?? $province['code'] ?? null,
                'province_id'         => $province?->id ?? $province['id'] ?? null,
                'trade_point_code'    => $tradePoint?->code ?? $tradePoint['code'] ?? null,
                'trade_point_id'      => $tradePoint?->id ?? $tradePoint['id'] ?? null,
                'trade_point_type_id' => $tradePoint?->trade_point_type_id ?? $tradePoint['trade_point_type_id'] ?? null,
                'trade_value'         => $tradeValue,
                'trade_volume'        => $tradeVolume,
            ];
        }
    }

    if ($rowHasActiveMonth) $activeSourceRows++;

    if ($totalSourceRows % 5000 === 0) {
        echo sprintf("Processed rows: %d | Monthly: %d | Memory: %.2f MB\n", $totalSourceRows, $monthlyRecords, memory_get_usage(true) / 1024 / 1024);
    }
}

$reader->close();
@unlink($tempSheet);

/*
|--------------------------------------------------------------------------
| Summary & CSV Export
|--------------------------------------------------------------------------
*/

$duplicateIdentityCount = 0;
foreach ($identityCount as $count) {
    if ($count > 1) $duplicateIdentityCount++;
}

$zeroActivityRows = $totalSourceRows - $activeSourceRows;

$output = fopen($outputFile, 'wb');
if ($output === false) {
    throw new RuntimeException("Tidak dapat membuat:\n{$outputFile}");
}

fputcsv($output, [
    'row', 'month', 'trade_identity', 'hs_code', 'country_code', 'country_id',
    'province_code', 'province_id', 'trade_point_code', 'trade_point_id',
    'trade_point_type_id', 'trade_value', 'trade_volume'
]);

foreach ($sampleRows as $sample) {
    fputcsv($output, $sample);
}
fclose($output);

$fullyPct = $monthlyRecords > 0 ? ($fullyResolved / $monthlyRecords * 100) : 0;

echo "\n========================================\n";
echo "DIGESTEX TRADE STATISTICS PAYLOAD DRY-RUN\n";
echo "========================================\n\n";
echo "SOURCE ROWS              : " . $totalSourceRows . PHP_EOL;
echo "ACTIVE SOURCE ROWS       : " . $activeSourceRows . PHP_EOL;
echo "ZERO-ACTIVITY ROWS       : " . $zeroActivityRows . PHP_EOL;
echo "MONTHLY NON-ZERO         : " . $monthlyRecords . PHP_EOL;

echo "\nRESOLUTION:\n";
echo "  HS RESOLVED            : " . $hsResolved . PHP_EOL;
echo "  HS UNRESOLVED          : " . $hsUnresolved . PHP_EOL;
echo "  COUNTRY RESOLVED       : " . $countryResolved . PHP_EOL;
echo "  COUNTRY UNRESOLVED     : " . $countryUnresolved . PHP_EOL;
echo "  PROVINCE RESOLVED      : " . $provinceResolved . PHP_EOL;
echo "  PROVINCE UNRESOLVED    : " . $provinceUnresolved . PHP_EOL;
echo "  TRADE POINT RESOLVED   : " . $tradePointResolved . PHP_EOL;
echo "  TRADE POINT UNRESOLVED : " . $tradePointUnresolved . PHP_EOL;

echo "\nMONTHLY PAYLOAD:\n";
echo "  FULLY RESOLVED         : " . $fullyResolved . PHP_EOL;
echo "  PARTIALLY RESOLVED     : " . $partiallyResolved . PHP_EOL;
echo "  FULLY RESOLVED %       : " . number_format($fullyPct, 2) . "%\n";

echo "\nIDENTITY:\n";
echo "  UNIQUE IDENTITIES      : " . count($identityCount) . PHP_EOL;
echo "  DUPLICATE IDENTITIES   : " . $duplicateIdentityCount . PHP_EOL;

echo "\nOUTPUT SAMPLE:\n" . $outputFile . PHP_EOL;
echo "\nFINAL MEMORY             : " . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . " MB\n";
echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";