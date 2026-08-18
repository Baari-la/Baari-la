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

const SOURCE_FILE =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\IMPORT\\impor 2019.xlsx';

const TARGET_YEAR = 2019;

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
            +
            (
                ord($letters[$i])
                - 64
            );
    }

    return $index;
}

function toTradeNumber(mixed $value): float
{
    if ($value === null) {
        return 0.0;
    }

    $value =
        trim(
            (string) $value
        );

    if ($value === '') {
        return 0.0;
    }

    $value =
        str_replace(
            ',',
            '',
            $value
        );

    return is_numeric($value)
        ? (float) $value
        : 0.0;
}

/*
|--------------------------------------------------------------------------
| Load shared strings
|--------------------------------------------------------------------------
*/

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
            'digestex_test_shared_'
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

/*
|--------------------------------------------------------------------------
| Extract worksheet
|--------------------------------------------------------------------------
*/

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
        $zip->getStream(
            $worksheet
        );

    if ($stream === false) {
        $zip->close();

        throw new RuntimeException(
            'Tidak dapat membaca worksheet.'
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_test_sheet_'
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
| Production-compatible readCellValue()
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
| Production-compatible readRow()
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
            xlsxColumnIndex(
                $reference
            );

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
| MAIN
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX KEMENDAG IMPORT DATA-LEVEL TEST 2019\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;

echo PHP_EOL;

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

$mapping =
    $parser->parse(
        SOURCE_FILE
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
| Open production-compatible worksheet
|--------------------------------------------------------------------------
*/

$sharedStrings =
    loadSharedStrings(
        SOURCE_FILE
    );

$sheetXml =
    extractWorksheet(
        SOURCE_FILE
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
    !$reader->open(
        $sheetXml
    )
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
| EXACT production row traversal
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

    /*
     * IMPORTANT:
     * This is the same readRow model used
     * by production ingestion.
     */
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

    /*
     * Same source-row rule as production:
     * invalid HS is skipped before sourceRows++.
     */
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

        /*
         * Same production rule:
         * zero value + zero volume = no monthly record.
         */
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

        /*
         * Samples
         */
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

    if (
        $rowHasActiveMonth
    ) {
        $activeSourceRows++;

    } else {
        $zeroActivityRows++;
    }
}

$reader->close();

@unlink($sheetXml);

/*
|--------------------------------------------------------------------------
| Samples
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
            static fn (array $row): int =>
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
echo "IMPORT DATA-LEVEL TEST 2019 : PASS\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";