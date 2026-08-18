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
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\IMPORT\\impor 2026.xlsx';

function toNumber(mixed $value): float
{
    if ($value === null) {
        return 0.0;
    }

    $value = trim((string) $value);

    if ($value === '') {
        return 0.0;
    }

    $value = str_replace(',', '', $value);

    return is_numeric($value)
        ? (float) $value
        : 0.0;
}

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

    $letters = strtoupper($match[1]);
    $index = 0;

    for ($i = 0; $i < strlen($letters); $i++) {
        $index =
            ($index * 26)
            +
            (
                ord($letters[$i]) - 64
            );
    }

    return $index;
}

function readCellValue(
    XMLReader $reader,
    array $sharedStrings
): mixed {
    $cellType = $reader->getAttribute('t');
    $cellDepth = $reader->depth;
    $value = null;

    while ($reader->read()) {

        if (
            $reader->nodeType === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'c'
            &&
            $reader->depth === $cellDepth
        ) {
            break;
        }

        if (
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 'v'
        ) {
            $raw = $reader->readString();

            $value =
                $cellType === 's'
                    ? (
                        $sharedStrings[(int) $raw]
                        ?? ''
                    )
                    : $raw;

            continue;
        }

        if (
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 'is'
        ) {
            $text = '';

            while ($reader->read()) {

                if (
                    $reader->nodeType === XMLReader::ELEMENT
                    &&
                    $reader->localName === 't'
                ) {
                    $text .= $reader->readString();
                }

                if (
                    $reader->nodeType === XMLReader::END_ELEMENT
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

function loadSharedStrings(
    ZipArchive $zip
): array {
    $index =
        $zip->locateName(
            'xl/sharedStrings.xml',
            ZipArchive::FL_NOCASE
        );

    if ($index === false) {
        return [];
    }

    $name = $zip->getNameIndex($index);
    $stream = $zip->getStream($name);

    if ($stream === false) {
        throw new RuntimeException(
            'Tidak dapat membuka sharedStrings.xml.'
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_import_data_shared_'
        );

    if ($temporary === false) {
        fclose($stream);

        throw new RuntimeException(
            'Gagal membuat temporary shared strings.'
        );
    }

    $handle = fopen($temporary, 'wb');

    if ($handle === false) {
        fclose($stream);
        @unlink($temporary);

        throw new RuntimeException(
            'Gagal membuka temporary shared strings.'
        );
    }

    stream_copy_to_stream($stream, $handle);

    fclose($stream);
    fclose($handle);

    $reader = new XMLReader();

    if (!$reader->open($temporary)) {
        @unlink($temporary);

        throw new RuntimeException(
            'XMLReader gagal membuka sharedStrings.'
        );
    }

    $strings = [];
    $current = null;

    while ($reader->read()) {

        if (
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $current = '';
            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType === XMLReader::ELEMENT
            &&
            $reader->localName === 't'
        ) {
            $current .= $reader->readString();
            continue;
        }

        if (
            $current !== null
            &&
            $reader->nodeType === XMLReader::END_ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $strings[] = $current;
            $current = null;
        }
    }

    $reader->close();
    @unlink($temporary);

    return $strings;
}

echo "========================================\n";
echo "DIGESTEX KEMENDAG IMPORT DATA-LEVEL TEST 2026\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;
echo PHP_EOL;

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

$mapping =
    $parser->parse(SOURCE_FILE);

echo "TRADE FLOW:\n";
echo "  "
    . $mapping['trade_flow']
    . PHP_EOL;

echo "VALUE PREFIX:\n";
echo "  "
    . $mapping['value_prefix']
    . PHP_EOL;

echo PHP_EOL;

echo "PERIOD MAPPING:\n";

foreach (
    $mapping['periods'] as $period => $data
) {
    echo sprintf(
        "  %s -> CIF %d | VOLUME %d\n",
        $period,
        $data['value_column'],
        $data['volume_column']
    );
}

echo PHP_EOL;

$zip = new ZipArchive();

if ($zip->open(SOURCE_FILE) !== true) {
    throw new RuntimeException(
        'Tidak dapat membuka workbook.'
    );
}

$sharedStrings =
    loadSharedStrings($zip);

$worksheet =
    $zip->getStream(
        'xl/worksheets/sheet1.xml'
    );

if ($worksheet === false) {
    $zip->close();

    throw new RuntimeException(
        'Worksheet sheet1.xml tidak dapat dibuka.'
    );
}

$tempSheet =
    tempnam(
        sys_get_temp_dir(),
        'digestex_import_data_sheet_'
    );

if ($tempSheet === false) {
    fclose($worksheet);
    $zip->close();

    throw new RuntimeException(
        'Gagal membuat temporary worksheet.'
    );
}

$tempHandle =
    fopen(
        $tempSheet,
        'wb'
    );

if ($tempHandle === false) {
    fclose($worksheet);
    $zip->close();
    @unlink($tempSheet);

    throw new RuntimeException(
        'Gagal membuka temporary worksheet.'
    );
}

/*
 * Copy worksheet while ZIP is still open.
 */
stream_copy_to_stream(
    $worksheet,
    $tempHandle
);

fclose($worksheet);
fclose($tempHandle);
$zip->close();

$reader = new XMLReader();

if (!$reader->open($tempSheet)) {
    @unlink($tempSheet);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

$monthlyTotals = [];

foreach (
    $mapping['periods'] as $period => $data
) {
    $monthlyTotals[$period] = [
        'trade_value' => 0.0,
        'trade_volume' => 0.0,
        'non_zero_value_rows' => 0,
        'non_zero_volume_rows' => 0,
    ];
}

$rowCount = 0;

while ($reader->read()) {

    if (
        $reader->nodeType !== XMLReader::ELEMENT
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

    $rowDepth = $reader->depth;
    $values = [];

    while ($reader->read()) {

        if (
            $reader->nodeType === XMLReader::END_ELEMENT
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

    $hs =
        trim(
            (string) (
                $values[
                    $mapping['static']['hs']
                ] ?? ''
            )
        );

    if ($hs === '') {
        continue;
    }

    $rowCount++;

    /*
     * Show the first 5 source rows as a sanity check.
     */
    if ($rowCount <= 5) {

        echo "SAMPLE ROW {$sourceRow}:\n";

        foreach (
            $mapping['periods'] as $period => $data
        ) {
            $tradeValue =
                toNumber(
                    $values[
                        $data['value_column']
                    ] ?? 0
                );

            $tradeVolume =
                toNumber(
                    $values[
                        $data['volume_column']
                    ] ?? 0
                );

            echo sprintf(
                "  %s | CIF=%.6f | VOL=%.6f\n",
                $period,
                $tradeValue,
                $tradeVolume
            );
        }

        echo PHP_EOL;
    }

    foreach (
        $mapping['periods'] as $period => $data
    ) {
        $tradeValue =
            toNumber(
                $values[
                    $data['value_column']
                ] ?? 0
            );

        $tradeVolume =
            toNumber(
                $values[
                    $data['volume_column']
                ] ?? 0
            );

        $monthlyTotals[$period]['trade_value']
            += $tradeValue;

        $monthlyTotals[$period]['trade_volume']
            += $tradeVolume;

        if ($tradeValue != 0.0) {
            $monthlyTotals[$period][
                'non_zero_value_rows'
            ]++;
        }

        if ($tradeVolume != 0.0) {
            $monthlyTotals[$period][
                'non_zero_volume_rows'
            ]++;
        }
    }
}

$reader->close();
@unlink($tempSheet);

echo "MONTHLY SOURCE TOTALS:\n";

$overallValue = 0.0;
$overallVolume = 0.0;
$passed = true;

foreach (
    $monthlyTotals as $period => $totals
) {
    $overallValue +=
        $totals['trade_value'];

    $overallVolume +=
        $totals['trade_volume'];

    $volumePass =
        $totals['trade_volume'] > 0;

    $status =
        $volumePass
            ? 'PASS'
            : 'FAIL';

    echo sprintf(
        "  %s | CIF=%0.6f | VOLUME=%0.6f | non-zero volume rows=%d | %s\n",
        $period,
        $totals['trade_value'],
        $totals['trade_volume'],
        $totals['non_zero_volume_rows'],
        $status
    );

    if (!$volumePass) {
        $passed = false;
    }
}

echo PHP_EOL;
echo "OVERALL:\n";

echo "  SOURCE ROWS SCANNED : "
    . $rowCount
    . PHP_EOL;

echo "  TOTAL CIF           : "
    . number_format(
        $overallValue,
        6,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TOTAL VOLUME        : "
    . number_format(
        $overallVolume,
        6,
        '.',
        ''
    )
    . PHP_EOL;

echo PHP_EOL;

$tradeStatsCount =
    DB::table('trade_statistics')
        ->count();

$batchCount =
    DB::table('trade_import_batches')
        ->count();

echo "DATABASE CHECK:\n";

echo "  trade_statistics     : "
    . $tradeStatsCount
    . PHP_EOL;

echo "  trade_import_batches : "
    . $batchCount
    . PHP_EOL;

echo PHP_EOL;
echo "========================================\n";

if ($passed) {
    echo "IMPORT DATA-LEVEL TEST : PASS\n";
} else {
    echo "IMPORT DATA-LEVEL TEST : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";