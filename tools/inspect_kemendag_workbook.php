<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $argv[1] ?? null;

if (!$file || !is_file($file)) {
    fwrite(
        STDERR,
        "Usage:\nphp tools\\inspect_kemendag_workbook.php \"FULL_PATH.xlsx\"\n"
    );

    exit(1);
}

echo "========================================\n";
echo "DIGESTEX KEMENDAG WORKBOOK INSPECTION\n";
echo "========================================\n\n";

echo "FILE:\n{$file}\n\n";

$reader = IOFactory::createReaderForFile($file);

$sheetNames = $reader->listWorksheetNames($file);

echo "WORKSHEETS: " . count($sheetNames) . "\n\n";

foreach ($sheetNames as $index => $sheetName) {

    echo "SHEET #" . ($index + 1) . ": {$sheetName}\n";

    $reader->setLoadSheetsOnly($sheetName);

    $spreadsheet = $reader->load($file);

    $sheet = $spreadsheet->getActiveSheet();

    echo "  ROWS       : " . $sheet->getHighestRow() . "\n";
    echo "  LAST COLUMN: " . $sheet->getHighestColumn() . "\n";
    echo "  DIMENSION  : " . $sheet->calculateWorksheetDimension() . "\n";

    /*
    |--------------------------------------------------------------------------
    | Show first 5 rows
    |--------------------------------------------------------------------------
    */

    $highestRow =
        min(
            5,
            $sheet->getHighestRow()
        );

    $highestColumn =
        $sheet->getHighestColumn();

    echo "  SAMPLE:\n";

    for (
        $row = 1;
        $row <= $highestRow;
        $row++
    ) {
        $values = [];

        for (
            $col = 1;
            $col <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                $highestColumn
            );
            $col++
        ) {
            $values[] =
                $sheet->getCellByColumnAndRow(
                    $col,
                    $row
                )->getValue();
        }

        echo "    ROW {$row}: "
            . implode(
                " | ",
                array_map(
                    static fn($v) => trim((string) $v),
                    $values
                )
            )
            . "\n";
    }

    echo "\n";
}

echo "========================================\n";
echo "INSPECTION COMPLETE\n";
echo "========================================\n";