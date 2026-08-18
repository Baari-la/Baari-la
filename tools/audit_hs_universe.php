<?php

declare(strict_types=1);

$input = getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_universe_2019_2026.csv';

if (!is_file($input)) {
    fwrite(
        STDERR,
        "FILE NOT FOUND:\n{$input}\n"
    );

    exit(1);
}

$handle = fopen($input, 'rb');

if ($handle === false) {
    throw new RuntimeException(
        "Unable to open CSV."
    );
}

$header = fgetcsv($handle);

if ($header === false) {
    throw new RuntimeException(
        "CSV header tidak ditemukan."
    );
}

$stats = [
    'total_rows' => 0,
    'valid_8_digit' => 0,
    'non_8_digit' => 0,
    'empty_hs' => 0,
    'empty_description' => 0,
    'invalid_numeric_hs' => 0,
];

$hsDescriptions = [];
$invalidRows = [];

while (($row = fgetcsv($handle)) !== false) {

    if (count($row) < 2) {
        continue;
    }

    $hs = trim((string) $row[0]);
    $description = trim((string) $row[1]);

    $stats['total_rows']++;

    if ($hs === '') {
        $stats['empty_hs']++;
        continue;
    }

    if ($description === '') {
        $stats['empty_description']++;
    }

    if (!preg_match('/^\d+$/', $hs)) {

        $stats['invalid_numeric_hs']++;

        $invalidRows[] = [
            'hs_code' => $hs,
            'description' => $description,
        ];

        continue;
    }

    if (strlen($hs) === 8) {
        $stats['valid_8_digit']++;
    } else {
        $stats['non_8_digit']++;

        $invalidRows[] = [
            'hs_code' => $hs,
            'description' => $description,
        ];
    }

    $hsDescriptions[$hs] ??= [];

    $normalizedDescription = preg_replace(
        '/\s+/',
        ' ',
        mb_strtoupper($description)
    ) ?? '';

    if ($normalizedDescription !== '') {
        $hsDescriptions[$hs][$normalizedDescription] = true;
    }
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| HS with multiple descriptions
|--------------------------------------------------------------------------
*/

$multipleDescriptions = [];

foreach ($hsDescriptions as $hs => $descriptions) {

    if (count($descriptions) > 1) {

        $multipleDescriptions[$hs] =
            array_keys($descriptions);
    }
}

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "\n========================================\n";
echo "DIGESTEX HS UNIVERSE QUALITY AUDIT\n";
echo "========================================\n\n";

echo "INPUT:\n{$input}\n\n";

echo "TOTAL UNIQUE HS ROWS      : {$stats['total_rows']}\n";
echo "VALID 8-DIGIT HS          : {$stats['valid_8_digit']}\n";
echo "NON 8-DIGIT HS             : {$stats['non_8_digit']}\n";
echo "EMPTY HS                   : {$stats['empty_hs']}\n";
echo "EMPTY DESCRIPTION         : {$stats['empty_description']}\n";
echo "INVALID NUMERIC HS        : {$stats['invalid_numeric_hs']}\n";
echo "HS WITH MULTIPLE DESCRIPT.: " . count($multipleDescriptions) . "\n\n";

if (!empty($invalidRows)) {

    echo "FIRST 30 ABNORMAL HS:\n";

    foreach (
        array_slice(
            $invalidRows,
            0,
            30
        ) as $item
    ) {
        echo sprintf(
            "%-15s | %s\n",
            $item['hs_code'],
            $item['description']
        );
    }

    echo "\n";
}

if (!empty($multipleDescriptions)) {

    echo "FIRST 30 HS WITH MULTIPLE DESCRIPTIONS:\n";

    $count = 0;

    foreach (
        $multipleDescriptions as $hs => $descriptions
    ) {
        echo "\nHS: {$hs}\n";

        foreach ($descriptions as $description) {
            echo "  - {$description}\n";
        }

        $count++;

        if ($count >= 30) {
            break;
        }
    }
}

echo "\n========================================\n";
echo "AUDIT COMPLETE\n";
echo "========================================\n";