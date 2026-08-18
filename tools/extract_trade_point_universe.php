<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

final class TradePointReadFilter implements IReadFilter
{
    public function readCell(
        $columnAddress,
        $row,
        $worksheetName = ''
    ) {
        return in_array(
            strtoupper($columnAddress),
            ['D', 'E'],
            true
        );
    }
}

function normalizeValue(?string $value): string
{
    $value = trim((string) $value);

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtoupper($value);
}

$base =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'KEMENDAG';

$outputDir =
    dirname($base)
    . DIRECTORY_SEPARATOR
    . 'PROCESSED';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$outputFile =
    $outputDir
    . DIRECTORY_SEPARATOR
    . 'trade_point_universe_2019_2026.csv';

/*
|--------------------------------------------------------------------------
| Collect source files
|--------------------------------------------------------------------------
*/

$files = [];

foreach (['EXPORT', 'IMPORT'] as $flow) {

    $directory =
        $base
        . DIRECTORY_SEPARATOR
        . $flow;

    if (!is_dir($directory)) {
        continue;
    }

    foreach (
        glob($directory . DIRECTORY_SEPARATOR . '*.xlsx') ?: []
        as $file
    ) {
        $files[] = [
            'path' => $file,
            'flow' => $flow,
        ];
    }
}

usort(
    $files,
    function (array $a, array $b): int {
        return strcmp($a['path'], $b['path']);
    }
);

if (empty($files)) {
    throw new RuntimeException(
        'Tidak ditemukan file XLSX Kemendag.'
    );
}

$filter = new TradePointReadFilter();

/*
|--------------------------------------------------------------------------
| Trade Point Universe
|--------------------------------------------------------------------------
|
| Identity:
|   ONLY normalized trade point name
|
| Province:
|   collected as source evidence, not identity
|
*/

$tradePoints = [];

foreach ($files as $index => $item) {

    $file = $item['path'];
    $flow = $item['flow'];

    echo PHP_EOL;
    echo "========================================" . PHP_EOL;
    echo sprintf(
        "[%d/%d] %s" . PHP_EOL,
        $index + 1,
        count($files),
        $file
    );
    echo "FLOW: {$flow}" . PHP_EOL;

    $reader = IOFactory::createReaderForFile($file);

    $reader->setReadDataOnly(true);
    $reader->setReadFilter($filter);

    $sheetNames = $reader->listWorksheetNames($file);

    if (empty($sheetNames)) {
        echo "NO WORKSHEET" . PHP_EOL;
        continue;
    }

    $reader->setLoadSheetsOnly($sheetNames[0]);

    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet();

    $highestRow = $sheet->getHighestRow();

    echo "Highest row: {$highestRow}" . PHP_EOL;

    /*
    |--------------------------------------------------------------------------
    | Detect Header
    |--------------------------------------------------------------------------
    */

    $headerRow = null;

    for (
        $row = 1;
        $row <= min($highestRow, 20);
        $row++
    ) {

        $provinceHeader = normalizeValue(
            (string) $sheet
                ->getCell("D{$row}")
                ->getValue()
        );

        $tradePointHeader = normalizeValue(
            (string) $sheet
                ->getCell("E{$row}")
                ->getValue()
        );

        if (
            in_array(
                $provinceHeader,
                ['PROVINSI', 'PROVINCE'],
                true
            )
            &&
            in_array(
                $tradePointHeader,
                [
                    'PELABUHAN',
                    'TRADE POINT',
                    'PORT',
                ],
                true
            )
        ) {
            $headerRow = $row;
            break;
        }
    }

    if ($headerRow === null) {

        echo "HEADER PROVINCE / TRADE POINT NOT FOUND"
            . PHP_EOL;

        $spreadsheet->disconnectWorksheets();

        unset(
            $spreadsheet,
            $sheet
        );

        gc_collect_cycles();

        continue;
    }

    echo "Header row: {$headerRow}" . PHP_EOL;

    $validRows = 0;
    $newTradePoints = 0;

    for (
        $row = $headerRow + 1;
        $row <= $highestRow;
        $row++
    ) {

        $rawProvince = trim(
            (string) $sheet
                ->getCell("D{$row}")
                ->getValue()
        );

        $rawTradePoint = trim(
            (string) $sheet
                ->getCell("E{$row}")
                ->getValue()
        );

        if ($rawTradePoint === '') {
            continue;
        }

        $province = normalizeValue(
            $rawProvince
        );

        $tradePoint = normalizeValue(
            $rawTradePoint
        );

        if ($tradePoint === '') {
            continue;
        }

        $validRows++;

        /*
        |--------------------------------------------------------------------------
        | Identity = Trade Point only
        |--------------------------------------------------------------------------
        */

        if (!isset($tradePoints[$tradePoint])) {

            $tradePoints[$tradePoint] = [
                'trade_point_source' =>
                    $rawTradePoint,

                'trade_point_normalized' =>
                    $tradePoint,

                'source_provinces' => [],

                'first_flow' => $flow,

                'export_seen' =>
                    $flow === 'EXPORT' ? 1 : 0,

                'import_seen' =>
                    $flow === 'IMPORT' ? 1 : 0,

                'occurrence_count' => 1,
            ];

            $newTradePoints++;

        } else {

            /*
            |--------------------------------------------------------------------------
            | Keep first source name, collect evidence
            |--------------------------------------------------------------------------
            */

            $tradePoints[$tradePoint][
                'occurrence_count'
            ]++;

            if ($flow === 'EXPORT') {
                $tradePoints[$tradePoint][
                    'export_seen'
                ] = 1;
            }

            if ($flow === 'IMPORT') {
                $tradePoints[$tradePoint][
                    'import_seen'
                ] = 1;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Collect every source province
        |--------------------------------------------------------------------------
        */

        if ($province !== '') {

            $tradePoints[$tradePoint][
                'source_provinces'
            ][$province] = true;
        }
    }

    echo "Valid rows: {$validRows}" . PHP_EOL;
    echo "New trade points: {$newTradePoints}" . PHP_EOL;

    $spreadsheet->disconnectWorksheets();

    unset(
        $spreadsheet,
        $sheet
    );

    gc_collect_cycles();
}

/*
|--------------------------------------------------------------------------
| Sort Trade Points
|--------------------------------------------------------------------------
*/

uksort(
    $tradePoints,
    function (string $a, string $b): int {
        return strcmp($a, $b);
    }
);

/*
|--------------------------------------------------------------------------
| Write CSV
|--------------------------------------------------------------------------
*/

$handle = fopen($outputFile, 'wb');

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuat output:\n{$outputFile}"
    );
}

fputcsv(
    $handle,
    [
        'trade_point_source',
        'trade_point_normalized',
        'source_province_count',
        'source_provinces',
        'first_flow',
        'export_seen',
        'import_seen',
        'occurrence_count',
    ]
);

foreach ($tradePoints as $tradePoint) {

    $sourceProvinces =
        array_keys(
            $tradePoint['source_provinces']
        );

    sort($sourceProvinces);

    fputcsv(
        $handle,
        [
            $tradePoint[
                'trade_point_source'
            ],

            $tradePoint[
                'trade_point_normalized'
            ],

            count($sourceProvinces),

            implode(
                ' | ',
                $sourceProvinces
            ),

            $tradePoint['first_flow'],

            $tradePoint['export_seen'],

            $tradePoint['import_seen'],

            $tradePoint['occurrence_count'],
        ]
    );
}

fclose($handle);

echo PHP_EOL;
echo "========================================" . PHP_EOL;
echo "TRADE POINT UNIVERSE COMPLETE" . PHP_EOL;
echo "========================================" . PHP_EOL;

echo "Files processed : "
    . count($files)
    . PHP_EOL;

echo "Unique points   : "
    . count($tradePoints)
    . PHP_EOL;

echo "Output          : "
    . $outputFile
    . PHP_EOL;

echo "========================================" . PHP_EOL;