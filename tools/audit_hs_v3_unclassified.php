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
    . 'hs_classification_review_v3.csv';

$outputFile =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA'
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'hs_v3_unclassified_by_heading.csv';

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

$header = fgetcsv($input);

if ($header === false) {
    fclose($input);

    throw new RuntimeException(
        'Header CSV tidak ditemukan.'
    );
}

$columnMap = [];

foreach ($header as $index => $name) {
    $columnMap[trim((string) $name)] = $index;
}

$required = [
    'hs_code',
    'description',
    'chapter',
    'heading',
    'subheading',
    'suggested_sector',
    'confidence',
    'reason',
];

foreach ($required as $column) {
    if (!array_key_exists($column, $columnMap)) {
        fclose($input);

        throw new RuntimeException(
            "Column tidak ditemukan: {$column}"
        );
    }
}

$groups = [];
$rows = [];

while (($row = fgetcsv($input)) !== false) {

    $sector = trim(
        (string) $row[$columnMap['suggested_sector']]
    );

    if ($sector !== '') {
        continue;
    }

    $chapter = trim(
        (string) $row[$columnMap['chapter']]
    );

    $heading = trim(
        (string) $row[$columnMap['heading']]
    );

    $key = $chapter . '|' . $heading;

    if (!isset($groups[$key])) {
        $groups[$key] = [
            'chapter' => $chapter,
            'heading' => $heading,
            'count' => 0,
            'hs_codes' => [],
        ];
    }

    $groups[$key]['count']++;

    $groups[$key]['hs_codes'][] =
        trim((string) $row[$columnMap['hs_code']]);

    $rows[] = [
        'hs_code' => trim(
            (string) $row[$columnMap['hs_code']]
        ),
        'description' => trim(
            (string) $row[$columnMap['description']]
        ),
        'chapter' => $chapter,
        'heading' => $heading,
        'subheading' => trim(
            (string) $row[$columnMap['subheading']]
        ),
        'confidence' => trim(
            (string) $row[$columnMap['confidence']]
        ),
        'reason' => trim(
            (string) $row[$columnMap['reason']]
        ),
    ];
}

fclose($input);

uasort(
    $groups,
    function (array $a, array $b): int {
        return $b['count'] <=> $a['count'];
    }
);

$output = fopen($outputFile, 'wb');

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'chapter',
        'heading',
        'count',
        'hs_codes',
    ]
);

foreach ($groups as $group) {
    fputcsv(
        $output,
        [
            $group['chapter'],
            $group['heading'],
            $group['count'],
            implode(
                ', ',
                $group['hs_codes']
            ),
        ]
    );
}

fclose($output);

echo "========================================\n";
echo "V3 UNCLASSIFIED HEADING AUDIT\n";
echo "========================================\n\n";

echo "TOTAL UNCLASSIFIED HS : " . count($rows) . "\n";
echo "UNIQUE HEADINGS       : " . count($groups) . "\n\n";

echo "GROUPS BY HEADING:\n";

foreach ($groups as $group) {
    echo sprintf(
        "Chapter %s | Heading %s | %d HS\n",
        $group['chapter'],
        $group['heading'],
        $group['count']
    );
}

echo "\nOUTPUT:\n";
echo $outputFile . "\n";

echo "\n========================================\n";
echo "FIRST 40 UNCLASSIFIED HS\n";
echo "========================================\n\n";

foreach (array_slice($rows, 0, 40) as $item) {
    echo sprintf(
        "%-10s | %-6s | %-8s | %s\n",
        $item['hs_code'],
        $item['chapter'],
        $item['heading'],
        $item['description']
    );
}

echo "\nDatabase was NOT modified.\n";