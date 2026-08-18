<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

final class ProvinceReadFilter implements IReadFilter
{
    public function readCell(
        $columnAddress,
        $row,
        $worksheetName = ''
    ) {
        return strtoupper($columnAddress) === 'D';
    }
}

function normalizeProvinceName(?string $value): string
{
    $value = trim((string) $value);

    $value = preg_replace('/\s+/', ' ', $value) ?? '';

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
    . 'province_universe_2019_2026.csv';

$files = [];

foreach (['EXPORT', 'IMPORT'] as $flow) {

    $directory =
        $base
        . DIRECTORY_SEPARATOR
        . $flow;

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
    fn (array $a, array $b): int =>
        strcmp($a['path'], $b['path'])
);

if (empty($files)) {
    throw new RuntimeException(
        'File Kemendag tidak ditemukan.'
    );
}

$filter = new ProvinceReadFilter();

$provinces = [];

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

        $value = trim(
            (string) $sheet
                ->getCell("D{$row}")
                ->getValue()
        );

        if (
            in_array(
                mb_strtolower($value),
                ['provinsi', 'province'],
                true
            )
        ) {
            $headerRow = $row;
            break;
        }
    }

    if ($headerRow === null) {
        echo "HEADER PROVINCE NOT FOUND\n";

        $spreadsheet->disconnectWorksheets();

        unset($spreadsheet, $sheet);

        gc_collect_cycles();

        continue;
    }

    echo "Header row: {$headerRow}\n";

    $validRows = 0;
    $newProvinces = 0;

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

        if ($rawProvince === '') {
            continue;
        }

        $province = normalizeProvinceName(
            $rawProvince
        );

        if ($province === '') {
            continue;
        }

        $validRows++;

        if (!isset($provinces[$province])) {

            $provinces[$province] = [
                'name_source' => $rawProvince,
                'name_normalized' => $province,
            ];

            $newProvinces++;
        }
    }

    echo "Valid rows: {$validRows}\n";
    echo "New provinces: {$newProvinces}\n";

    $spreadsheet->disconnectWorksheets();

    unset(
        $spreadsheet,
        $sheet
    );

    gc_collect_cycles();
}

ksort($provinces);

$handle = fopen($outputFile, 'wb');

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuat output: {$outputFile}"
    );
}

fputcsv(
    $handle,
    [
        'name_source',
        'name_normalized',
    ]
);

foreach ($provinces as $province) {

    fputcsv(
        $handle,
        [
            $province['name_source'],
            $province['name_normalized'],
        ]
    );
}

fclose($handle);

echo "\n========================================\n";
echo "PROVINCE UNIVERSE COMPLETE\n";
echo "========================================\n";

echo "Files processed : "
    . count($files)
    . PHP_EOL;

echo "Unique province : "
    . count($provinces)
    . PHP_EOL;

echo "Output          : "
    . $outputFile
    . PHP_EOL;

echo "========================================\n";