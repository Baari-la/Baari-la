<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

final class TradeSourceProfileFilter implements IReadFilter
{
    public function readCell(
        $columnAddress,
        $row,
        $worksheetName = ''
    ) {
        /*
        |--------------------------------------------------------------------------
        | Read first 10 rows across A:AC
        |--------------------------------------------------------------------------
        */
        return $row <= 10;
    }
}

$file =
    getenv('USERPROFILE')
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
    throw new RuntimeException(
        "File tidak ditemukan:\n{$file}"
    );
}

echo "========================================\n";
echo "DIGESTEX TRADE SOURCE PROFILE\n";
echo "========================================\n\n";

echo "FILE:\n";
echo $file . "\n\n";

$reader = IOFactory::createReaderForFile($file);

$reader->setReadDataOnly(true);
$reader->setReadFilter(
    new TradeSourceProfileFilter()
);

$sheetNames = $reader->listWorksheetNames($file);

echo "WORKSHEETS:\n";

foreach ($sheetNames as $index => $sheetName) {
    echo sprintf(
        "  %d. %s\n",
        $index + 1,
        $sheetName
    );
}

echo "\n";

$reader->setLoadSheetsOnly($sheetNames[0]);

$spreadsheet = $reader->load($file);

$sheet = $spreadsheet->getActiveSheet();

$highestColumn =
    $sheet->getHighestColumn();

$highestRow =
    $sheet->getHighestRow();

echo "HIGHEST COLUMN : "
    . $highestColumn
    . PHP_EOL;

echo "HIGHEST ROW    : "
    . $highestRow
    . PHP_EOL;

echo "\n========================================\n";
echo "ROWS 1-10\n";
echo "========================================\n\n";

for ($row = 1; $row <= 10; $row++) {

    echo "ROW {$row}:\n";

    $values = [];

    /*
    |--------------------------------------------------------------------------
    | A:AC
    |--------------------------------------------------------------------------
    */

    for (
        $column = 1;
        $column <= 29;
        $column++
    ) {

        $columnLetter =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $column
            );

        $value = $sheet
            ->getCell(
                $columnLetter . $row
            )
            ->getValue();

        if (
            $value !== null
            &&
            trim((string) $value) !== ''
        ) {
            $values[] =
                sprintf(
                    "%s=%s",
                    $columnLetter,
                    trim((string) $value)
                );
        }
    }

    foreach ($values as $value) {
        echo "  {$value}\n";
    }

    echo "\n";
}

echo "========================================\n";
echo "SELECTED COLUMN LABELS\n";
echo "========================================\n\n";

foreach (
    ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
     'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
     'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC']
    as $column
) {

    $header2 = trim(
        (string) $sheet
            ->getCell($column . '2')
            ->getValue()
    );

    $header1 = trim(
        (string) $sheet
            ->getCell($column . '1')
            ->getValue()
    );

    if (
        $header1 !== ''
        ||
        $header2 !== ''
    ) {
        echo sprintf(
            "%-3s | ROW1: %-30s | ROW2: %s\n",
            $column,
            $header1,
            $header2
        );
    }
}

$spreadsheet->disconnectWorksheets();

unset(
    $spreadsheet,
    $sheet
);

gc_collect_cycles();

echo "\n========================================\n";
echo "PROFILE COMPLETE\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";