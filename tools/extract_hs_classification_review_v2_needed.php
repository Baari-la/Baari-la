<?php

declare(strict_types=1);

$inputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_classification_review_v2.csv';

$outputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_classification_review_v2_needed.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "File tidak ditemukan:\n{$inputFile}"
    );
}

$input = fopen($inputFile, 'rb');

if ($input === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$inputFile}"
    );
}

$output = fopen($outputFile, 'wb');

if ($output === false) {
    fclose($input);

    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

$header = fgetcsv($input);

if ($header === false) {
    fclose($input);
    fclose($output);

    throw new RuntimeException(
        'Header CSV tidak ditemukan.'
    );
}

fputcsv(
    $output,
    $header
);

$total = 0;
$reviewed = 0;

while (($row = fgetcsv($input)) !== false) {

    if (count($row) < 16) {
        continue;
    }

    $total++;

    $sector = trim((string) $row[6]);

    $confidence = strtoupper(
        trim((string) $row[14])
    );

    $isTechnical = (
        (string) $row[11] === '1'
    );

    $needsReview =
        $confidence === 'LOW'
        || $confidence === 'MEDIUM'
        || $sector === ''
        || $isTechnical;

    if (!$needsReview) {
        continue;
    }

    fputcsv(
        $output,
        $row
    );

    $reviewed++;
}

fclose($input);
fclose($output);

echo "========================================\n";
echo "HS CLASSIFICATION V2 REVIEW NEEDED\n";
echo "========================================\n\n";

echo "TOTAL INPUT       : {$total}\n";
echo "REVIEW RECORDS    : {$reviewed}\n";

echo "\nOUTPUT:\n";
echo $outputFile . "\n\n";

echo "Database was NOT modified.\n";