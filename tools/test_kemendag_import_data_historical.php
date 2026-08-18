<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagTradeWorkbookHeaderParser;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| CLI
|--------------------------------------------------------------------------
*/

$options = getopt('', ['year:']);

if (
    !isset($options['year'])
    ||
    !preg_match('/^\d{4}$/', (string) $options['year'])
) {
    throw new RuntimeException(
        'Parameter --year wajib diisi, contoh: --year=2020'
    );
}

$targetYear =
    (int) $options['year'];

if (
    $targetYear < 2019
    ||
    $targetYear > 2025
) {
    throw new RuntimeException(
        'Historical import hanya mendukung tahun 2019-2025.'
    );
}

$sourceFile =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\IMPORT\\impor '
    . $targetYear
    . '.xlsx';

if (!is_file($sourceFile)) {
    throw new RuntimeException(
        'Source file tidak ditemukan: '
        . $sourceFile
    );
}

/*
|--------------------------------------------------------------------------
| XLSX helpers
|--------------------------------------------------------------------------
*/

function xlsxColumnIndex(string $reference): int
{
    if (
        !preg_match(
            '/^([A-Z]+)\d+$/i',
            $reference,
            $match
        )
    ) {
        return 0;
    }

    $letters =
        strtoupper($match[1]);

    $index = 0;

    for (
        $i = 0;
        $i < strlen($letters);
        $i++
    ) {
        $index =
            ($index * 26)
            + (ord($letters[$i]) - 64);
    }

    return $index;
}

function toTradeNumber(mixed $value): float
{
    if ($value === null) {
        return 0.0;
    }

    $value =
        trim((string) $value);

    if ($value === '') {
        return 0.0;
    }

    $value =
        str_replace(',', '', $value);

    return is_numeric($value)
        ? (float) $value
        : 0.0;
}

function loadSharedStrings(
    string $xlsxFile
): array {
    $zip =
        new ZipArchive();

    if (
        $zip->open($xlsxFile)
        !== true
    ) {
        throw new RuntimeException(
            'Tidak dapat membuka workbook.'
        );
    }

    $index =
        $zip->locateName(
            'xl/sharedStrings.xml',
            ZipArchive::FL_NOCASE
        );

    if ($index === false) {
        $zip->close();
        return [];
    }

    $name =
        $zip->getNameIndex($index);

    $stream =
        $zip->getStream($name);

    if ($stream === false) {
        $zip->close();

        throw new RuntimeException(
            'Tidak dapat membuka sharedStrings.xml.'
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_hist_shared_'
        );

    if ($temporary === false) {
        fclose($stream);
        $zip->close();

        throw new RuntimeException(
            'Gagal membuat temporary shared strings.'
        );
    }

    $handle =
        fopen(
            $temporary,
            'wb'
        );

    if ($handle === false) {
        fclose($stream);
        $zip->close();
        @unlink($temporary);

        throw new RuntimeException(
            'Gagal membuat temporary shared strings file.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);
    $zip->close();

    $reader =
        new XMLReader();

    if (!$reader->open($temporary)) {
        @unlink($temporary);

        throw new RuntimeException(
            'XMLReader gagal membuka shared strings.'
        );
    }

    $strings = [];
    $current = null;

    while ($reader->read()) {

        if (
            $reader->nodeType
            === XMLReader::ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $current = '';
            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType
            === XMLReader::ELEMENT
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
            $reader->nodeType
            === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $strings[] =
                $current;

            $current = null;
        }
    }

    $reader->close();

    @unlink($temporary);

    return $strings;
}

function extractWorksheet(
    string $xlsxFile
): string {
    $zip =
        new ZipArchive();

    if (
        $zip->open($xlsxFile)
        !== true
    ) {
        throw new RuntimeException(
            'Tidak dapat membuka workbook.'
        );
    }

    $worksheet = null;

    for (
        $index = 0;
        $index < $zip->numFiles;
        $index++
    ) {
        $name =
            $zip->getNameIndex($index);

        if (
            $name !== false
            &&
            preg_match(
                '#^xl/worksheets/sheet1\.xml$#i',
                $name
            )
        ) {
            $worksheet = $name;
            break;
        }
    }

    if ($worksheet === null) {
        $zip->close();

        throw new RuntimeException(
            'xl/worksheets/sheet1.xml tidak ditemukan.'
        );
    }

    $stream =
        $zip->getStream($worksheet);

    if ($stream === false) {
        $zip->close();

        throw new RuntimeException(
            'Tidak dapat membaca worksheet.'
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_hist_sheet_'
        );

    if ($temporary === false) {
        fclose($stream);
        $zip->close();

        throw new RuntimeException(
            'Gagal membuat temporary worksheet.'
        );
    }

    $handle =
        fopen(
            $temporary,
            'wb'
        );

    if ($handle === false) {
        fclose($stream);
        $zip->close();
        @unlink($temporary);

        throw new RuntimeException(
            'Gagal membuat temporary worksheet file.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);
    $zip->close();

    return $temporary;
}

/*
|--------------------------------------------------------------------------
| Production-compatible cell reader
|--------------------------------------------------------------------------
*/

function readCellValue(
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
            $reader->nodeType
            === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'c'
            &&
            $reader->depth === $cellDepth
        ) {
            break;
        }

        if (
            $reader->nodeType
            === XMLReader::ELEMENT
            &&
            $reader->localName === 'v'
        ) {
            $raw =
                $reader->readString();

            $value =
                $cellType === 's'
                    ? (
                        $sharedStrings[
                            (int) $raw
                        ] ?? ''
                    )
                    : $raw;

            continue;
        }

        if (
            $reader->nodeType
            === XMLReader::ELEMENT
            &&
            $reader->localName === 'is'
        ) {
            $text = '';

            while ($reader->read()) {

                if (
                    $reader->nodeType
                    === XMLReader::ELEMENT
                    &&
                    $reader->localName === 't'
                ) {
                    $text .=
                        $reader->readString();
                }

                if (
                    $reader->nodeType
                    === XMLReader::END_ELEMENT
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
| Production-compatible row reader
|--------------------------------------------------------------------------
*/

function readRow(
    XMLReader $reader,
    array $sharedStrings
): array {
    $rowDepth =
        $reader->depth;

    $values = [];

    while ($reader->read()) {

        if (
            $reader->nodeType
            === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'row'
            &&
            $reader->depth === $rowDepth
        ) {
            break;
        }

        if (
            $reader->nodeType !== XMLReader::ELEMENT
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

        $column =
            xlsxColumnIndex($reference);

        if ($column < 1) {
            continue;
        }

        $values[$column] =
            readCellValue(
                $reader,
                $sharedStrings
            );
    }

    return $values;
}

/*
|--------------------------------------------------------------------------
| Main
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX KEMENDAG IMPORT DATA-LEVEL TEST {$targetYear}\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo $sourceFile . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Generic header parser
|--------------------------------------------------------------------------
*/

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

$mapping =
    $parser->parse(
        $sourceFile
    );

$tradeFlow =
    $mapping['trade_flow'];

$valuePrefix =
    $mapping['value_prefix'];

$static =
    $mapping['static'];

$periods =
    $mapping['periods'];

if ($tradeFlow !== 'import') {
    throw new RuntimeException(
        'Trade flow bukan import.'
    );
}

if ($valuePrefix !== 'cif_') {
    throw new RuntimeException(
        'Value prefix bukan cif_.'
    );
}

if (count($periods) !== 12) {
    throw new RuntimeException(
        'Historical import harus memiliki 12 periode.'
    );
}

echo "TRADE FLOW:\n";
echo "  {$tradeFlow}\n";

echo "VALUE PREFIX:\n";
echo "  {$valuePrefix}\n\n";

echo "STATIC COLUMNS:\n";

echo "  hs           : "
    . $static['hs']
    . PHP_EOL;

echo "  uraian_hs    : "
    . $static['uraian_hs']
    . PHP_EOL;

echo "  negara       : "
    . $static['negara']
    . PHP_EOL;

echo "  provinsi     : "
    . $static['provinsi']
    . PHP_EOL;

echo "  pelabuhan    : "
    . $static['pelabuhan']
    . PHP_EOL;

echo PHP_EOL;

echo "PERIOD MAPPING:\n";

foreach (
    $periods as $periodKey => $period
) {
    echo "  {$periodKey}"
        . " -> CIF "
        . $period['value_column']
        . " | VOLUME "
        . $period['volume_column']
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Validate year range
|--------------------------------------------------------------------------
*/

$expectedFirst =
    $targetYear . '-01';

$expectedLast =
    $targetYear . '-12';

$periodKeys =
    array_keys($periods);

if (
    ($periodKeys[0] ?? null)
    !==
    $expectedFirst
) {
    throw new RuntimeException(
        "First period bukan {$expectedFirst}."
    );
}

if (
    ($periodKeys[count($periodKeys) - 1] ?? null)
    !==
    $expectedLast
) {
    throw new RuntimeException(
        "Last period bukan {$expectedLast}."
    );
}

/*
|--------------------------------------------------------------------------
| Open streaming worksheet
|--------------------------------------------------------------------------
*/

$sharedStrings =
    loadSharedStrings(
        $sourceFile
    );

$sheetXml =
    extractWorksheet(
        $sourceFile
    );

echo "SHARED STRINGS : "
    . count($sharedStrings)
    . PHP_EOL;

echo "WORKSHEET       : "
    . $sheetXml
    . PHP_EOL;

echo PHP_EOL;

$reader =
    new XMLReader();

if (
    !$reader->open($sheetXml)
) {
    @unlink($sheetXml);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

/*
|--------------------------------------------------------------------------
| Aggregates
|--------------------------------------------------------------------------
*/

$monthly = [];

foreach (
    $periods as $periodKey => $period
) {
    $monthly[$periodKey] = [
        'trade_value' => 0.0,
        'trade_volume' => 0.0,
        'non_zero_volume_rows' => 0,
        'active_rows' => 0,
    ];
}

$sourceRows = 0;
$activeSourceRows = 0;
$zeroActivityRows = 0;

$sampleRows = [
    3,
    4,
    5,
    6,
    7,
];

$sampleData = [];

/*
|--------------------------------------------------------------------------
| Production-compatible streaming traversal
|--------------------------------------------------------------------------
*/

while ($reader->read()) {

    if (
        $reader->nodeType
        !== XMLReader::ELEMENT
        ||
        $reader->localName !== 'row'
    ) {
        continue;
    }

    $sourceRow =
        (int) (
            $reader->getAttribute('r')
            ?? 0
        );

    if ($sourceRow < 3) {
        continue;
    }

    $row =
        readRow(
            $reader,
            $sharedStrings
        );

    $hsCode =
        trim(
            (string) (
                $row[
                    $static['hs']
                ] ?? ''
            )
        );

    if ($hsCode === '') {
        continue;
    }

    $sourceRows++;

    $rowHasActiveMonth = false;

    foreach (
        $periods as $periodKey => $period
    ) {

        $tradeValue =
            toTradeNumber(
                $row[
                    $period['value_column']
                ] ?? 0
            );

        $tradeVolume =
            toTradeNumber(
                $row[
                    $period['volume_column']
                ] ?? 0
            );

        if (
            $tradeValue == 0
            &&
            $tradeVolume == 0
        ) {
            continue;
        }

        $rowHasActiveMonth = true;

        $monthly[
            $periodKey
        ]['trade_value'] +=
            $tradeValue;

        $monthly[
            $periodKey
        ]['trade_volume'] +=
            $tradeVolume;

        $monthly[
            $periodKey
        ]['active_rows']++;

        if (
            $tradeVolume != 0
        ) {
            $monthly[
                $periodKey
            ]['non_zero_volume_rows']++;
        }

        if (
            in_array(
                $sourceRow,
                $sampleRows,
                true
            )
        ) {
            $sampleData[
                $sourceRow
            ][
                $periodKey
            ] = [
                'trade_value' =>
                    $tradeValue,
                'trade_volume' =>
                    $tradeVolume,
            ];
        }
    }

    if ($rowHasActiveMonth) {
        $activeSourceRows++;
    } else {
        $zeroActivityRows++;
    }
}

$reader->close();

@unlink($sheetXml);

/*
|--------------------------------------------------------------------------
| Sample rows
|--------------------------------------------------------------------------
*/

foreach (
    $sampleRows as $sourceRow
) {

    echo "SAMPLE ROW {$sourceRow}:\n";

    foreach (
        $periods as $periodKey => $period
    ) {

        $sample =
            $sampleData[
                $sourceRow
            ][
                $periodKey
            ]
            ??
            [
                'trade_value' => 0.0,
                'trade_volume' => 0.0,
            ];

        echo sprintf(
            "  %s | CIF=%.6f | VOL=%.6f\n",
            $periodKey,
            $sample['trade_value'],
            $sample['trade_volume']
        );
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Monthly totals
|--------------------------------------------------------------------------
*/

$totalCif = 0.0;
$totalVolume = 0.0;

echo "MONTHLY SOURCE TOTALS:\n";

foreach (
    $monthly as $periodKey => $data
) {

    $totalCif +=
        $data['trade_value'];

    $totalVolume +=
        $data['trade_volume'];

    echo sprintf(
        "  %s | CIF=%.6f | VOLUME=%.6f | non-zero volume rows=%d | PASS\n",
        $periodKey,
        $data['trade_value'],
        $data['trade_volume'],
        $data['non_zero_volume_rows']
    );
}

echo PHP_EOL;

$monthlyRecords =
    array_sum(
        array_map(
            static fn(array $row): int =>
                $row['active_rows'],
            $monthly
        )
    );

echo "OVERALL:\n";

echo "  SOURCE ROWS SCANNED : "
    . $sourceRows
    . PHP_EOL;

echo "  ACTIVE SOURCE ROWS  : "
    . $activeSourceRows
    . PHP_EOL;

echo "  ZERO ACTIVITY ROWS  : "
    . $zeroActivityRows
    . PHP_EOL;

echo "  MONTHLY RECORDS     : "
    . $monthlyRecords
    . PHP_EOL;

echo "  TOTAL CIF           : "
    . number_format(
        $totalCif,
        6,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TOTAL VOLUME        : "
    . number_format(
        $totalVolume,
        6,
        '.',
        ''
    )
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Database check
|--------------------------------------------------------------------------
*/

$tradeStatisticsCount =
    DB::table(
        'trade_statistics'
    )->count();

$tradeImportBatchesCount =
    DB::table(
        'trade_import_batches'
    )->count();

echo "DATABASE CHECK:\n";

echo "  trade_statistics     : "
    . $tradeStatisticsCount
    . PHP_EOL;

echo "  trade_import_batches : "
    . $tradeImportBatchesCount
    . PHP_EOL;

echo PHP_EOL;

echo "========================================\n";
echo "IMPORT DATA-LEVEL TEST {$targetYear} : PASS\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";