<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

final class HsOnlyReadFilter implements IReadFilter
{
    public function readCell(
        $columnAddress,
        $row,
        $worksheetName = ''
    ) {
        return in_array(
            strtoupper($columnAddress),
            ['A', 'B'],
            true
        );
    }
}

$base = rtrim(
    getenv('USERPROFILE')
        . DIRECTORY_SEPARATOR
        . 'Desktop'
        . DIRECTORY_SEPARATOR
        . 'DIGESTEX_DATA'
        . DIRECTORY_SEPARATOR
        . 'KEMENDAG',
    DIRECTORY_SEPARATOR
);

$processedDir = dirname($base) . DIRECTORY_SEPARATOR . 'PROCESSED';

if (!is_dir($processedDir)) {
    mkdir($processedDir, 0777, true);
}

$outputFile = $processedDir
    . DIRECTORY_SEPARATOR
    . 'hs_universe_2019_2026.csv';

$files = [];

foreach (['EXPORT', 'IMPORT'] as $flow) {
    $directory = $base
        . DIRECTORY_SEPARATOR
        . $flow;

    if (!is_dir($directory)) {
        continue;
    }

    foreach (glob($directory . DIRECTORY_SEPARATOR . '*.xlsx') ?: [] as $file) {
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

$filter = new HsOnlyReadFilter();

$uniqueHs = [];

foreach ($files as $index => $item) {
    $file = $item['path'];
    $flow = $item['flow'];

    echo "\n========================================\n";
    echo sprintf(
        "[%d/%d] %s\n",
        $index + 1,
        count($files),
        $file
    );
    echo "FLOW: {$flow}\n";

    $reader = IOFactory::createReaderForFile($file);
    $reader->setReadDataOnly(true);
    $reader->setReadFilter($filter);

    $sheetNames = $reader->listWorksheetNames($file);

    if (empty($sheetNames)) {
        echo "NO WORKSHEET\n";
        continue;
    }

    $reader->setLoadSheetsOnly($sheetNames[0]);

    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet();

    $highestRow = $sheet->getHighestRow();

    echo "Highest row: {$highestRow}\n";

    $headerRow = null;

    for ($row = 1; $row <= min($highestRow, 20); $row++) {
        $hs = trim(
            (string) $sheet
                ->getCell("A{$row}")
                ->getValue()
        );

        $desc = trim(
            (string) $sheet
                ->getCell("B{$row}")
                ->getValue()
        );

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
        echo "HEADER NOT FOUND\n";

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $sheet);

        gc_collect_cycles();

        continue;
    }

    echo "Header row: {$headerRow}\n";

    $validRows = 0;
    $newHs = 0;

    for (
        $row = $headerRow + 1;
        $row <= $highestRow;
        $row++
    ) {
        $hsCode = trim(
            (string) $sheet
                ->getCell("A{$row}")
                ->getValue()
        );

        if ($hsCode === '') {
            continue;
        }

        $hsCode = preg_replace(
            '/\D+/',
            '',
            $hsCode
        ) ?? '';

        if ($hsCode === '') {
            continue;
        }

        $description = trim(
            (string) $sheet
                ->getCell("B{$row}")
                ->getValue()
        );

        $validRows++;

        if (!isset($uniqueHs[$hsCode])) {
            $uniqueHs[$hsCode] = [
                'hs_code' => $hsCode,
                'uraian_hs' => $description,
            ];

            $newHs++;
        }

        if ($validRows % 10000 === 0) {
            echo sprintf(
                "Rows: %d | Total unique HS: %d | Memory: %.2f MB\n",
                $validRows,
                count($uniqueHs),
                memory_get_usage(true) / 1024 / 1024
            );
        }
    }

    echo "Valid rows: {$validRows}\n";
    echo "New HS from file: {$newHs}\n";

    $spreadsheet->disconnectWorksheets();

    unset(
        $spreadsheet,
        $sheet
    );

    gc_collect_cycles();
}

ksort($uniqueHs);

$handle = fopen($outputFile, 'wb');

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuat file: {$outputFile}"
    );
}

fputcsv(
    $handle,
    ['hs_code', 'uraian_hs']
);

foreach ($uniqueHs as $item) {
    fputcsv(
        $handle,
        [
            $item['hs_code'],
            $item['uraian_hs'],
        ]
    );
}

fclose($handle);

echo "\n========================================\n";
echo "HS UNIVERSE COMPLETE\n";
echo "Files processed : " . count($files) . "\n";
echo "Unique HS       : " . count($uniqueHs) . "\n";
echo "Output          : {$outputFile}\n";
echo "========================================\n";