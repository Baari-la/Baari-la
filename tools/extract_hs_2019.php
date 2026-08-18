<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$home = getenv('USERPROFILE');

$file = $home
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'KEMENDAG'
    . DIRECTORY_SEPARATOR
    . 'EXPORT'
    . DIRECTORY_SEPARATOR
    . 'ekspor 2019.xlsx';

if (!is_file($file)) {
    fwrite(STDERR, "FILE NOT FOUND:\n{$file}\n");
    exit(1);
}

echo "FILE:\n{$file}\n\n";

ini_set('memory_limit', '1024M');
set_time_limit(0);

$reader = IOFactory::createReaderForFile($file);
$reader->setReadDataOnly(true);

$sheetNames = $reader->listWorksheetNames($file);

echo "WORKSHEETS:\n";

foreach ($sheetNames as $i => $sheetName) {
    echo sprintf(
        "  %d. %s\n",
        $i + 1,
        $sheetName
    );
}

$reader->setLoadSheetsOnly($sheetNames[0]);

$spreadsheet = $reader->load($file);
$sheet = $spreadsheet->getActiveSheet();

echo "\nHighest Row: " . $sheet->getHighestRow() . "\n";
echo "Highest Column: " . $sheet->getHighestColumn() . "\n";

/*
|--------------------------------------------------------------------------
| Find Header Row
|--------------------------------------------------------------------------
*/

$headerRow = null;

$highestRow = $sheet->getHighestRow();

for ($row = 1; $row <= min($highestRow, 20); $row++) {

    $hs = trim((string) $sheet->getCell("A{$row}")->getValue());
    $desc = trim((string) $sheet->getCell("B{$row}")->getValue());

    if (
        strtolower($hs) === 'hs' &&
        str_contains(
            strtolower($desc),
            'uraian'
        )
    ) {
        $headerRow = $row;
        break;
    }
}

if ($headerRow === null) {
    throw new RuntimeException(
        'Header HS / Uraian HS tidak ditemukan.'
    );
}

echo "HEADER ROW: {$headerRow}\n";

/*
|--------------------------------------------------------------------------
| Stream HS Only
|--------------------------------------------------------------------------
*/

$uniqueHs = [];

$processedRows = 0;
$validRows = 0;

for (
    $row = $headerRow + 1;
    $row <= $highestRow;
    $row++
) {
    $processedRows++;

    $hsCode = trim(
        (string) $sheet->getCell("A{$row}")->getValue()
    );

    if ($hsCode === '') {
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize HS
    |--------------------------------------------------------------------------
    */

    $hsCode = preg_replace(
        '/\D+/',
        '',
        $hsCode
    ) ?? '';

    if ($hsCode === '') {
        continue;
    }

    $description = trim(
        (string) $sheet->getCell("B{$row}")->getValue()
    );

    $validRows++;

    /*
    |--------------------------------------------------------------------------
    | First occurrence wins
    |--------------------------------------------------------------------------
    */

    if (!isset($uniqueHs[$hsCode])) {
        $uniqueHs[$hsCode] = [
            'hs_code' => $hsCode,
            'description' => $description,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Progress
    |--------------------------------------------------------------------------
    */

    if ($processedRows % 10000 === 0) {

        echo sprintf(
            "Processed: %d | Unique HS: %d | Memory: %.2f MB\n",
            $processedRows,
            count($uniqueHs),
            memory_get_usage(true) / 1024 / 1024
        );
    }
}

ksort($uniqueHs);

echo "\n";
echo "PROCESSED ROWS : {$processedRows}\n";
echo "VALID HS ROWS  : {$validRows}\n";
echo "UNIQUE HS      : " . count($uniqueHs) . "\n\n";

echo "FIRST 50 UNIQUE HS:\n";

foreach (
    array_slice(
        $uniqueHs,
        0,
        50
    ) as $item
) {
    echo sprintf(
        "%-12s | %s\n",
        $item['hs_code'],
        $item['description']
    );
}

$spreadsheet->disconnectWorksheets();

unset(
    $spreadsheet,
    $sheet
);

gc_collect_cycles();

echo "\nEXTRACTION COMPLETE.\n";