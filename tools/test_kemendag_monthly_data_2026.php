<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagWorkbookHeaderParser;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use XMLReader;

const SOURCE_FILE =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\EXPORT\\ekspor 2026.xlsx';

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
    if (!preg_match('/^([A-Z]+)\d+$/i', $reference, $match)) {
        return 0;
    }

    $letters = strtoupper($match[1]);
    $index = 0;

    for ($i = 0; $i < strlen($letters); $i++) {
        $index =
            ($index * 26)
            + (ord($letters[$i]) - 64);
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
            && $reader->localName === 'c'
            && $reader->depth === $cellDepth
        ) {
            break;
        }

        if (
            $reader->nodeType === XMLReader::ELEMENT
            && $reader->localName === 'v'
        ) {
            $raw = $reader->readString();

            $value = $cellType === 's'
                ? ($sharedStrings[(int) $raw] ?? '')
                : $raw;

            continue;
        }

        if (
            $reader->nodeType === XMLReader::ELEMENT
            && $reader->localName === 'is'
        ) {
            $text = '';

            while ($reader->read()) {
                if (
                    $reader->nodeType === XMLReader::ELEMENT
                    && $reader->localName === 't'
                ) {
                    $text .= $reader->readString();
                }

                if (
                    $reader->nodeType === XMLReader::END_ELEMENT
                    && $reader->localName === 'is'
                ) {
                    break;
                }
            }

            $value = $text;
        }
    }

    return $value;
}

function loadSharedStrings(ZipArchive $zip): array
{
    $index = $zip->locateName(
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

    $temporary = tempnam(
        sys_get_temp_dir(),
        'digestex_data_test_shared_'
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
            'Gagal membuat temporary shared strings file.'
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
            && $reader->localName === 'si'
        ) {
            $current = '';
            continue;
        }

        if (
            $current !== null
            && $reader->nodeType === XMLReader::ELEMENT
            && $reader->localName === 't'
        ) {
            $current .= $reader->readString();
            continue;
        }

        if (
            $current !== null
            && $reader->nodeType === XMLReader::END_ELEMENT
            && $reader->localName === 'si'
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
echo "DIGESTEX KEMENDAG DATA-LEVEL TEST 2026\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;
echo PHP_EOL;

$parser = app(KemendagWorkbookHeaderParser::class);
$mapping = $parser->parse(SOURCE_FILE);

echo "PERIOD MAPPING:\n";

foreach ($mapping['periods'] as $period => $data) {
    echo sprintf(
        "  %s -> FOB %d | VOLUME %d\n",
        $period,
        $data['fob_column'],
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

$sharedStrings = loadSharedStrings($zip);

$worksheet = $zip->getStream('xl/worksheets/sheet1.xml');

if ($worksheet === false) {
    $zip->close();
    throw new RuntimeException(
        'Worksheet sheet1.xml tidak dapat dibuka.'
    );
}

/*
 * IMPORTANT:
 * Copy the worksheet stream BEFORE closing the ZIP archive.
 * A ZipArchive stream becomes invalid when its parent archive closes.
 */
$tempSheet = tempnam(
    sys_get_temp_dir(),
    'digestex_data_test_sheet_'
);

if ($tempSheet === false) {
    fclose($worksheet);
    $zip->close();
    throw new RuntimeException(
        'Gagal membuat temporary worksheet.'
    );
}

$tempHandle = fopen($tempSheet, 'wb');

if ($tempHandle === false) {
    fclose($worksheet);
    $zip->close();
    @unlink($tempSheet);
    throw new RuntimeException(
        'Gagal membuat temporary worksheet file.'
    );
}

stream_copy_to_stream($worksheet, $tempHandle);

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

foreach ($mapping['periods'] as $period => $data) {
    $monthlyTotals[$period] = [
        'trade_value' => 0.0,
        'trade_volume' => 0.0,
        'non_zero_volume_rows' => 0,
        'non_zero_value_rows' => 0,
    ];
}

$rowCount = 0;

while ($reader->read()) {
    if (
        $reader->nodeType !== XMLReader::ELEMENT
        || $reader->localName !== 'row'
    ) {
        continue;
    }

    $sourceRow = (int) ($reader->getAttribute('r') ?? 0);

    if ($sourceRow < 3) {
        continue;
    }

    $rowDepth = $reader->depth;
    $values = [];

    while ($reader->read()) {
        if (
            $reader->nodeType === XMLReader::END_ELEMENT
            && $reader->localName === 'row'
            && $reader->depth === $rowDepth
        ) {
            break;
        }

        if (
            $reader->nodeType !== XMLReader::ELEMENT
            || $reader->localName !== 'c'
        ) {
            continue;
        }

        $reference = (string) ($reader->getAttribute('r') ?? '');
        $column = xlsxColumnIndex($reference);

        if ($column < 1) {
            continue;
        }

        $values[$column] = readCellValue(
            $reader,
            $sharedStrings
        );
    }

    if (
        trim((string) ($values[$mapping['static']['hs']] ?? '')) === ''
    ) {
        continue;
    }

    $rowCount++;

    foreach ($mapping['periods'] as $period => $data) {
        $tradeValue = toNumber(
            $values[$data['fob_column']] ?? 0
        );

        $tradeVolume = toNumber(
            $values[$data['volume_column']] ?? 0
        );

        $monthlyTotals[$period]['trade_value'] += $tradeValue;
        $monthlyTotals[$period]['trade_volume'] += $tradeVolume;

        if ($tradeValue != 0.0) {
            $monthlyTotals[$period]['non_zero_value_rows']++;
        }

        if ($tradeVolume != 0.0) {
            $monthlyTotals[$period]['non_zero_volume_rows']++;
        }
    }

    if ($rowCount <= 5) {
        echo "SAMPLE ROW {$sourceRow}:\n";

        foreach ($mapping['periods'] as $period => $data) {
            $tradeValue = toNumber(
                $values[$data['fob_column']] ?? 0
            );

            $tradeVolume = toNumber(
                $values[$data['volume_column']] ?? 0
            );

            echo sprintf(
                "  %s | FOB=%.3f | VOL=%.3f\n",
                $period,
                $tradeValue,
                $tradeVolume
            );
        }

        echo PHP_EOL;
    }
}

$reader->close();
@unlink($tempSheet);

echo "MONTHLY SOURCE TOTALS:\n";

$overallVolume = 0.0;
$overallValue = 0.0;
$passed = true;

foreach ($monthlyTotals as $period => $totals) {
    $overallValue += $totals['trade_value'];
    $overallVolume += $totals['trade_volume'];

    $volumePass = $totals['trade_volume'] > 0;
    $status = $volumePass ? 'PASS' : 'FAIL';

    echo sprintf(
        "  %s | VALUE=%0.3f | VOLUME=%0.3f | non-zero volume rows=%d | %s\n",
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
echo "  Source rows scanned : " . $rowCount . PHP_EOL;
echo "  TOTAL TRADE VALUE   : " . number_format($overallValue, 3, '.', '') . PHP_EOL;
echo "  TOTAL TRADE VOLUME  : " . number_format($overallVolume, 3, '.', '') . PHP_EOL;
echo PHP_EOL;

echo "DATABASE CHECK:\n";
$dbCount = DB::table('trade_statistics')
    ->where('year', 2026)
    ->count();

echo "  Existing 2026 rows : " . $dbCount . PHP_EOL;
echo PHP_EOL;

echo "========================================\n";
echo $passed
    ? "MONTHLY DATA-LEVEL TEST : PASS\n"
    : "MONTHLY DATA-LEVEL TEST : FAIL\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";