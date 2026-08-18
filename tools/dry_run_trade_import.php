<?php

declare(strict_types=1);

use App\Services\Trade\CountryResolverService;
use App\Models\HsCode;
use App\Services\Trade\TradePointResolverService;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

final class TradeDryRunReadFilter implements IReadFilter
{
    private int $startRow = 1;
    private int $endRow = 1;

    public function setRows(int $startRow, int $endRow): void 
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell($columnAddress, $row, $worksheetName = ''): bool 
    {
        /*
        |--------------------------------------------------------------------------
        | Only read columns A:AC (1 to 29)
        |--------------------------------------------------------------------------
        */
        $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnAddress);

        if ($columnIndex < 1 || $columnIndex > 29) {
            return false;
        }

        return $row >= $this->startRow && $row <= $this->endRow;
    }
}

/*
|--------------------------------------------------------------------------
| SOURCE & OUTPUT PATHS
|--------------------------------------------------------------------------
*/

$sourceFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR . 'Desktop'
    . DIRECTORY_SEPARATOR . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR . 'KEMENDAG'
    . DIRECTORY_SEPARATOR . 'EXPORT'
    . DIRECTORY_SEPARATOR . 'ekspor 2019.xlsx';

$outputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR . 'Desktop'
    . DIRECTORY_SEPARATOR . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR . 'PROCESSED'
    . DIRECTORY_SEPARATOR . 'dry_run_export_2019.csv';

if (!is_file($sourceFile)) {
    throw new RuntimeException("Source file tidak ditemukan:\n{$sourceFile}");
}

/*
|--------------------------------------------------------------------------
| Basic configuration & Month Mapping
|--------------------------------------------------------------------------
*/

$tradeFlow = 'EXPORT';
$year = 2019;
$sourceSystem = 'KEMENDAG';

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
| Helper Functions
|--------------------------------------------------------------------------
*/

function normalizeValue(?string $value): string
{
    $value = trim((string) $value);

    if ($value === '') {
        return '';
    }

    $value = preg_replace('/\s+/', ' ', $value) ?? '';

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

    return is_numeric($value) ? (float) $value : 0.0;
}

/*
|--------------------------------------------------------------------------
| Lookups & Resolvers Initialization
|--------------------------------------------------------------------------
*/

$hsLookup = HsCode::query()
    ->where('is_active', true)
    ->get(['id_hs', 'hs_code', 'uraian_hs_id', 'uraian_hs_en'])
    ->keyBy('hs_code');

$provinceRows = DB::table('provinces')
    ->where('is_active', true)
    ->get(['id', 'code', 'name', 'name_en']);

$provinceLookup = [];
foreach ($provinceRows as $province) {
    foreach ([$province->name, $province->name_en] as $name) {
        $normalized = normalizeValue($name);
        if ($normalized !== '') {
            $provinceLookup[$normalized] = $province;
        }
    }
}

$countryResolver = $app->make(CountryResolverService::class);
$tradePointResolver = $app->make(TradePointResolverService::class);

/*
|--------------------------------------------------------------------------
| Statistics Counters
|--------------------------------------------------------------------------
*/

$totalSourceRows = 0;
$totalMonthlyRows = 0;
$totalZeroMonthsSkipped = 0;
$nonZeroRows = 0;

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

/*
|--------------------------------------------------------------------------
| Spreadsheet Reader & File Setup
|--------------------------------------------------------------------------
*/

$reader = IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);

$sheetInfo = $reader->listWorksheetInfo($sourceFile);

if (empty($sheetInfo)) {
    throw new RuntimeException('Tidak dapat membaca worksheet information.');
}

$highestRow = (int) $sheetInfo[0]['totalRows'];

$filter = new TradeDryRunReadFilter();
$reader->setReadFilter($filter);

$outputDir = dirname($outputFile);
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$output = fopen($outputFile, 'w');

// CSV Header
fputcsv($output, [
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
]);

/*
|--------------------------------------------------------------------------
| Main Iteration Loop (Chunked)
|--------------------------------------------------------------------------
*/

$chunkSize = 5000;

for ($chunkStart = 3; $chunkStart <= $highestRow; $chunkStart += $chunkSize) {

    $chunkEnd = min($chunkStart + $chunkSize - 1, $highestRow);

    echo sprintf("Loading rows %d - %d of %d...\n", $chunkStart, $chunkEnd, $highestRow);

    $filter->setRows($chunkStart, $chunkEnd);

    $spreadsheet = $reader->load($sourceFile);
    $sheet = $spreadsheet->getActiveSheet();

    for ($rowNumber = $chunkStart; $rowNumber <= $chunkEnd; $rowNumber++) {

        $totalSourceRows++;

        /*
        |--------------------------------------------------------------------------
        | Read raw source values
        |--------------------------------------------------------------------------
        */
        $hsSource = trim((string) $sheet->getCell("A{$rowNumber}")->getValue());
        $description = trim((string) $sheet->getCell("B{$rowNumber}")->getValue());
        $countrySource = trim((string) $sheet->getCell("C{$rowNumber}")->getValue());
        $provinceSource = trim((string) $sheet->getCell("D{$rowNumber}")->getValue());
        $tradePointSource = trim((string) $sheet->getCell("E{$rowNumber}")->getValue());

        /*
        |--------------------------------------------------------------------------
        | Resolve HS
        |--------------------------------------------------------------------------
        */
        $hsCode = preg_replace('/\D/', '', $hsSource) ?? '';
        if (strlen($hsCode) === 7) {
            $hsCode = '0' . $hsCode;
        }

        $hs = $hsLookup->get($hsCode);

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
        | Resolve Country
        |--------------------------------------------------------------------------
        */
        $countryNormalized = normalizeValue($countrySource);
        $country = $countryResolver->resolve($countrySource, $sourceSystem);

        if ($country !== null) {
            $countryStatus = 'RESOLVED';
            $resolvedCountry++;
            $countryId = $country->id;
            $countryCode = $country->country_code;
        } else {
            $countryStatus = 'UNRESOLVED';
            $unresolvedCountry++;
            $countryId = null;
            $countryCode = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Province
        |--------------------------------------------------------------------------
        */
        $provinceNormalized = normalizeValue($provinceSource);
        $province = $provinceLookup[$provinceNormalized] ?? null;

        if ($province !== null) {
            $provinceStatus = 'RESOLVED';
            $resolvedProvince++;
            $provinceId = $province->id;
            $provinceCode = $province->code;
        } else {
            $provinceStatus = 'UNRESOLVED';
            $unresolvedProvince++;
            $provinceId = null;
            $provinceCode = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Trade Point
        |--------------------------------------------------------------------------
        */
        $tradePointNormalized = normalizeValue($tradePointSource);
        $tradePoint = $tradePointResolver->resolve($tradePointSource, $sourceSystem);

        if ($tradePoint !== null) {
            $tradePointStatus = 'RESOLVED';
            $resolvedTradePoint++;
            $tradePointId = $tradePoint->id;
            $tradePointCode = $tradePoint->code;
            $tradePointTypeId = $tradePoint->trade_point_type_id;
        } else {
            $tradePointStatus = 'UNRESOLVED';
            $unresolvedTradePoint++;
            $tradePointId = null;
            $tradePointCode = null;
            $tradePointTypeId = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Expand 12 months
        |--------------------------------------------------------------------------
        */
        for ($month = 1; $month <= 12; $month++) {

            $valueColumn = $months[$month]['value'];
            $volumeColumn = $months[$month]['volume'];

            $tradeValue = numericValue($sheet->getCell($valueColumn . $rowNumber)->getValue());
            $tradeVolume = numericValue($sheet->getCell($volumeColumn . $rowNumber)->getValue());

            if (abs($tradeValue) < 0.0000001 && abs($tradeVolume) < 0.0000001) {
                $totalZeroMonthsSkipped++;
                continue;
            }

            $totalMonthlyRows++;
            $nonZeroRows++;

            /*
            |--------------------------------------------------------------------------
            | Overall resolution
            |--------------------------------------------------------------------------
            */
            $allResolved =
                $hsStatus === 'RESOLVED'
                && $countryStatus === 'RESOLVED'
                && $provinceStatus === 'RESOLVED'
                && $tradePointStatus === 'RESOLVED';

            if ($allResolved) {
                $overallStatus = 'FULLY_RESOLVED';
                $fullyResolved++;
            } else {
                $overallStatus = 'PARTIALLY_RESOLVED';
                $partiallyResolved++;
            }

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */
            $remarks = [];

            if ($hsStatus !== 'RESOLVED') {
                $remarks[] = 'HS_UNRESOLVED';
            }
            if ($countryStatus !== 'RESOLVED') {
                $remarks[] = 'COUNTRY_UNRESOLVED';
            }
            if ($provinceStatus !== 'RESOLVED') {
                $remarks[] = 'PROVINCE_UNRESOLVED';
            }
            if ($tradePointStatus !== 'RESOLVED') {
                $remarks[] = 'TRADE_POINT_UNRESOLVED';
            }

            fputcsv($output, [
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

                implode('|', $remarks),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Release chunk memory
    |--------------------------------------------------------------------------
    */
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet, $sheet);
    gc_collect_cycles();
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Final Summary Output
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "DIGESTEX TRADE DRY-RUN 2019 EXPORT\n";
echo "========================================\n\n";

echo "SOURCE FILE:\n";
echo $sourceFile . "\n\n";

echo "SOURCE ROWS              : " . $totalSourceRows . PHP_EOL;
echo "MONTHLY NON-ZERO ROWS    : " . $totalMonthlyRows . PHP_EOL;
echo "ZERO MONTHS SKIPPED      : " . $totalZeroMonthsSkipped . PHP_EOL;

echo "\nRESOLUTION:\n";
echo "  HS RESOLVED            : " . $resolvedHs . PHP_EOL;
echo "  HS UNRESOLVED          : " . $unresolvedHs . PHP_EOL;
echo "  COUNTRY RESOLVED       : " . $resolvedCountry . PHP_EOL;
echo "  COUNTRY UNRESOLVED     : " . $unresolvedCountry . PHP_EOL;
echo "  PROVINCE RESOLVED      : " . $resolvedProvince . PHP_EOL;
echo "  PROVINCE UNRESOLVED    : " . $unresolvedProvince . PHP_EOL;
echo "  TRADE POINT RESOLVED   : " . $resolvedTradePoint . PHP_EOL;
echo "  TRADE POINT UNRESOLVED : " . $unresolvedTradePoint . PHP_EOL;

echo "\nMONTHLY RESOLUTION:\n";
echo "  FULLY RESOLVED         : " . $fullyResolved . PHP_EOL;
echo "  PARTIALLY RESOLVED     : " . $partiallyResolved . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . "\n";

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";