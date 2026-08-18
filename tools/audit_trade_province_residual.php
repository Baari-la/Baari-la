<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| DIGESTEX TRADE PROVINCE RESIDUAL AUDIT
|--------------------------------------------------------------------------
|
| PURPOSE:
|   Find the source row(s) whose province could not be resolved,
|   including rows whose monthly trade values are all zero.
|
| SOURCE:
|   ekspor 2019.xlsx
|
| DATABASE:
|   READ ONLY
|--------------------------------------------------------------------------
*/

$base =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA';

$sourceFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'KEMENDAG'
    . DIRECTORY_SEPARATOR
    . 'EXPORT'
    . DIRECTORY_SEPARATOR
    . 'ekspor 2019.xlsx';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_province_residual_review_2019.csv';

if (!is_file($sourceFile)) {
    throw new RuntimeException(
        "Source file tidak ditemukan:\n{$sourceFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeProvinceResidual(
    string $value
): string {
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtoupper($value);
}

/*
|--------------------------------------------------------------------------
| Load province master
|--------------------------------------------------------------------------
*/

$provinceRows = DB::table('provinces')
    ->where('is_active', true)
    ->get([
        'id',
        'code',
        'name',
        'name_en',
    ]);

$provinceLookup = [];

foreach ($provinceRows as $province) {

    foreach ([
        $province->name,
        $province->name_en,
    ] as $name) {

        $normalized =
            normalizeProvinceResidual(
                (string) $name
            );

        if ($normalized !== '') {
            $provinceLookup[
                $normalized
            ] = $province;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Known aliases used by the streaming dry-run
|--------------------------------------------------------------------------
*/

$provinceAliases = [
    'D.I. YOGYAKARTA' =>
        'ID-YO',

    'NANGROE ACEH DARUSALAM' =>
        'ID-AC',
];

/*
|--------------------------------------------------------------------------
| Province by code
|--------------------------------------------------------------------------
*/

$provinceByCode =
    $provinceRows->keyBy('code');

/*
|--------------------------------------------------------------------------
| XLSX shared strings
|--------------------------------------------------------------------------
*/

function loadProvinceAuditSharedStrings(
    ZipArchive $zip
): array {
    $index =
        $zip->locateName(
            'xl/sharedStrings.xml',
            \ZipArchive::FL_NOCASE
        );

    if ($index === false) {
        return [];
    }

    $name =
        $zip->getNameIndex($index);

    $stream =
        $zip->getStream($name);

    if ($stream === false) {
        throw new RuntimeException(
            'Tidak dapat membuka sharedStrings.xml.'
        );
    }

    $tempFile =
        tempnam(
            sys_get_temp_dir(),
            'digestex_province_shared_'
        );

    if ($tempFile === false) {
        fclose($stream);

        throw new RuntimeException(
            'Tidak dapat membuat temporary shared strings.'
        );
    }

    $handle =
        fopen(
            $tempFile,
            'wb'
        );

    if ($handle === false) {
        fclose($stream);
        @unlink($tempFile);

        throw new RuntimeException(
            'Tidak dapat menulis temporary shared strings.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);

   $reader =
    new \XMLReader();

    if (!$reader->open($tempFile)) {
        @unlink($tempFile);

        throw new RuntimeException(
            'XMLReader gagal membuka sharedStrings.xml.'
        );
    }

    $strings = [];
    $current = null;

    while ($reader->read()) {

        if (
            $reader->nodeType === \XMLReader::ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $current = '';
            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType === \XMLReader::ELEMENT
            &&
            $reader->localName === 't'
        ) {
            $current .=
                $reader->readString();

            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $strings[] = $current;
            $current = null;
        }
    }

    $reader->close();

    @unlink($tempFile);

    return $strings;
}

/*
|--------------------------------------------------------------------------
| Column reference
|--------------------------------------------------------------------------
*/

function provinceAuditColumnIndex(
    string $reference
): int {
    if (
        !preg_match(
            '/^([A-Z]+)\d+$/i',
            $reference,
            $matches
        )
    ) {
        return 0;
    }

    $letters =
        strtoupper($matches[1]);

    $index = 0;

    for (
        $i = 0;
        $i < strlen($letters);
        $i++
    ) {
        $index =
            ($index * 26)
            +
            (
                ord($letters[$i])
                - 64
            );
    }

    return $index;
}

/*
|--------------------------------------------------------------------------
| Cell value
|--------------------------------------------------------------------------
*/

function readProvinceAuditCell(
    XMLReader $reader,
    array $sharedStrings
): mixed {
    $cellType =
        $reader->getAttribute('t');

    $cellDepth =
        $reader->depth;

    $value = null;

    while ($reader->read()) {

        if (
            $reader->nodeType === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'c'
            &&
            $reader->depth === $cellDepth
        ) {
            break;
        }

        if (
            $reader->nodeType === \XMLReader::ELEMENT
            &&
            $reader->localName === 'v'
        ) {
            $raw =
                $reader->readString();

            if ($cellType === 's') {

                $value =
                    $sharedStrings[
                        (int) $raw
                    ] ?? '';

            } elseif ($cellType === 'b') {

                $value =
                    $raw === '1'
                        ? 1
                        : 0;

            } else {

                $value = $raw;
            }

            continue;
        }

        if (
            $reader->nodeType === \XMLReader::ELEMENT
            &&
            $reader->localName === 'is'
        ) {

            $text = '';

            while ($reader->read()) {

                if (
                    $reader->nodeType === \XMLReader::ELEMENT
                    &&
                    $reader->localName === 't'
                ) {
                    $text .=
                        $reader->readString();
                }

                if (
                    $reader->nodeType === \XMLReader::END_ELEMENT
                    &&
                    $reader->localName === 'is'
                ) {
                    break;
                }
            }

            $value = $text;
        }
    }

    return $value;
}

/*
|--------------------------------------------------------------------------
| Open XLSX
|--------------------------------------------------------------------------
*/

$zip =
    new \ZipArchive();

if (
    $zip->open($sourceFile)
    !== true
) {
    throw new RuntimeException(
        'Tidak dapat membuka XLSX.'
    );
}

$sharedStrings =
    loadProvinceAuditSharedStrings(
        $zip
    );

$sheetIndex =
    $zip->locateName(
        'xl/worksheets/sheet1.xml',
        \ZipArchive::FL_NOCASE
    );

if ($sheetIndex === false) {

    $zip->close();

    throw new RuntimeException(
        'Sheet1 tidak ditemukan.'
    );
}

$sheetName =
    $zip->getNameIndex(
        $sheetIndex
    );

$stream =
    $zip->getStream(
        $sheetName
    );

if ($stream === false) {

    $zip->close();

    throw new RuntimeException(
        'Tidak dapat membuka worksheet XML.'
    );
}

$tempSheet =
    tempnam(
        sys_get_temp_dir(),
        'digestex_province_sheet_'
    );

if ($tempSheet === false) {

    fclose($stream);
    $zip->close();

    throw new RuntimeException(
        'Tidak dapat membuat temporary worksheet.'
    );
}

$tempHandle =
    fopen(
        $tempSheet,
        'wb'
    );

if ($tempHandle === false) {

    fclose($stream);
    $zip->close();
    @unlink($tempSheet);

    throw new RuntimeException(
        'Tidak dapat membuat temporary worksheet handle.'
    );
}

stream_copy_to_stream(
    $stream,
    $tempHandle
);

fclose($stream);
fclose($tempHandle);

$zip->close();

/*
|--------------------------------------------------------------------------
| XML Reader
|--------------------------------------------------------------------------
*/

$reader =
    new XMLReader();

if (!$reader->open($tempSheet)) {
    @unlink($tempSheet);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

/*
|--------------------------------------------------------------------------
| Relevant columns
|--------------------------------------------------------------------------
|
| A = HS
| C = country
| D = province
| E = trade point
| F:Q = monthly FOB
| R:AC = monthly volume
|--------------------------------------------------------------------------
*/

$monthlyValueColumns = [
    6, 7, 8, 9, 10, 11,
    12, 13, 14, 15, 16, 17,
];

$monthlyVolumeColumns = [
    18, 19, 20, 21, 22, 23,
    24, 25, 26, 27, 28, 29,
];

$unresolvedRows = [];

$totalSourceRows = 0;

while ($reader->read()) {

    if (
        $reader->nodeType !== \XMLReader::ELEMENT
        ||
        $reader->localName !== 'row'
    ) {
        continue;
    }

    $rowNumber =
        (int) (
            $reader->getAttribute('r')
            ?? 0
        );

    if ($rowNumber < 3) {
        continue;
    }

    $rowDepth =
        $reader->depth;

    $values = [];

    while ($reader->read()) {

        if (
            $reader->nodeType === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'row'
            &&
            $reader->depth === $rowDepth
        ) {
            break;
        }

        if (
            $reader->nodeType !== \XMLReader::ELEMENT
            ||
            $reader->localName !== 'c'
        ) {
            continue;
        }

        $reference =
            (string) (
                $reader->getAttribute('r')
                ?? ''
            );

        $index =
            provinceAuditColumnIndex(
                $reference
            );

        if (
            $index < 1
            ||
            $index > 29
        ) {
            readProvinceAuditCell(
                $reader,
                $sharedStrings
            );

            continue;
        }

        $values[$index] =
            readProvinceAuditCell(
                $reader,
                $sharedStrings
            );
    }

    $hsSource =
        trim(
            (string) (
                $values[1] ?? ''
            )
        );

    $countrySource =
        trim(
            (string) (
                $values[3] ?? ''
            )
        );

    $provinceSource =
        trim(
            (string) (
                $values[4] ?? ''
            )
        );

    $tradePointSource =
        trim(
            (string) (
                $values[5] ?? ''
            )
        );

    if (
        $hsSource === ''
        &&
        $countrySource === ''
        &&
        $provinceSource === ''
        &&
        $tradePointSource === ''
    ) {
        continue;
    }

    $totalSourceRows++;

    $provinceNormalized =
        normalizeProvinceResidual(
            $provinceSource
        );

    $province =
        $provinceLookup[
            $provinceNormalized
        ] ?? null;

    if ($province === null) {

        $aliasCode =
            $provinceAliases[
                $provinceNormalized
            ] ?? null;

        if ($aliasCode !== null) {
            $province =
                $provinceByCode[
                    $aliasCode
                ] ?? null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Only capture genuinely unresolved province rows
    |--------------------------------------------------------------------------
    */

    if ($province !== null) {
        continue;
    }

    $nonZeroValueCount = 0;
    $nonZeroVolumeCount = 0;
    $totalTradeValue = 0.0;
    $totalTradeVolume = 0.0;

    foreach (
        $monthlyValueColumns
        as $index
    ) {

        $value =
            is_numeric(
                $values[$index] ?? null
            )
                ? (float) $values[$index]
                : 0.0;

        if (abs($value) > 0.0000001) {
            $nonZeroValueCount++;
        }

        $totalTradeValue +=
            $value;
    }

    foreach (
        $monthlyVolumeColumns
        as $index
    ) {

        $volume =
            is_numeric(
                $values[$index] ?? null
            )
                ? (float) $values[$index]
                : 0.0;

        if (abs($volume) > 0.0000001) {
            $nonZeroVolumeCount++;
        }

        $totalTradeVolume +=
            $volume;
    }

    $unresolvedRows[] = [
        'row_number' =>
            $rowNumber,

        'hs_source' =>
            $hsSource,

        'country_source' =>
            $countrySource,

        'province_source' =>
            $provinceSource,

        'trade_point_source' =>
            $tradePointSource,

        'non_zero_value_months' =>
            $nonZeroValueCount,

        'non_zero_volume_months' =>
            $nonZeroVolumeCount,

        'total_trade_value' =>
            $totalTradeValue,

        'total_trade_volume' =>
            $totalTradeVolume,
    ];
}

$reader->close();

@unlink($tempSheet);

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

$output =
    fopen(
        $outputFile,
        'wb'
    );

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'row_number',
        'hs_source',
        'country_source',
        'province_source',
        'trade_point_source',
        'non_zero_value_months',
        'non_zero_volume_months',
        'total_trade_value',
        'total_trade_volume',
    ]
);

foreach ($unresolvedRows as $row) {
    fputcsv(
        $output,
        $row
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX TRADE PROVINCE RESIDUAL AUDIT\n";
echo "========================================\n\n";

echo "SOURCE ROWS SCANNED      : "
    . $totalSourceRows
    . PHP_EOL;

echo "UNRESOLVED PROVINCE ROWS : "
    . count($unresolvedRows)
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";