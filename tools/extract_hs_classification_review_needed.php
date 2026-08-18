<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Models\HsCode;

$inputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_classification_review.csv';

$outputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_classification_review_needed.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Classification review tidak ditemukan:\n{$inputFile}"
    );
}

$handle = fopen($inputFile, 'rb');

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$inputFile}"
    );
}

$output = fopen($outputFile, 'wb');

if ($output === false) {
    fclose($handle);

    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

$header = fgetcsv($handle);

if ($header === false) {
    fclose($handle);
    fclose($output);

    throw new RuntimeException(
        'Header CSV tidak ditemukan.'
    );
}

/*
|--------------------------------------------------------------------------
| Keep only fields needed for human review
|--------------------------------------------------------------------------
*/

fputcsv(
    $output,
    [
        'id_hs',
        'hs_code',
        'description',
        'chapter',
        'heading',
        'subheading',
        'suggested_sector',
        'suggested_product_family',
        'is_fiber',
        'is_yarn',
        'is_fabric',
        'is_technical_textile',
        'is_apparel',
        'is_madeup',
        'confidence',
        'reason',
    ]
);

$total = 0;
$selected = 0;

while (($row = fgetcsv($handle)) !== false) {

    if (count($row) < 16) {
        continue;
    }

    $total++;

    $confidence = strtoupper(
        trim((string) $row[14])
    );

    $sector = trim(
        (string) $row[6]
    );

    $isTechnical =
        (string) $row[11] === '1';

    $needsReview = false;

    /*
    |--------------------------------------------------------------------------
    | Review rules
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $confidence,
            ['LOW', 'MEDIUM'],
            true
        )
    ) {
        $needsReview = true;
    }

    if ($isTechnical) {
        $needsReview = true;
    }

    if ($sector === '') {
        $needsReview = true;
    }

    if (!$needsReview) {
        continue;
    }

    $selected++;

    fputcsv(
        $output,
        $row
    );
}

fclose($handle);
fclose($output);

echo "========================================\n";
echo "HS CLASSIFICATION REVIEW NEEDED\n";
echo "========================================\n\n";

echo "TOTAL REVIEW RECORDS : {$selected}\n";
echo "OUTPUT:\n{$outputFile}\n\n";

echo "Database was NOT modified.\n";