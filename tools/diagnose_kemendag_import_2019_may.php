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
const TARGET_PERIOD = '2019-05';
const TARGET_CIF = 41.0;
const TARGET_VOLUME = 4.0;

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function excelColumnNumber(string $letters): int
{
    $letters = strtoupper(trim($letters));

    $number = 0;

    for ($i = 0; $i < strlen($letters); $i++) {
        $number =
            ($number * 26)
            + (ord($letters[$i]) - 64);
    }

    return $number;
}

function parseExcelCellReference(string $reference): array
{
    if (!preg_match('/^([A-Z]+)(\d+)$/i', $reference, $matches)) {
        throw new RuntimeException(
            "Invalid cell reference: {$reference}"
        );
    }

    return [
        'column' => excelColumnNumber($matches[1]),
        'row' => (int) $matches[2],
    ];
}

function numericValue($value): float
{
    if ($value === null) {
        return 0.0;
    }

    if (is_numeric($value)) {
        return (float) $value;
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

function loadSharedStrings(\ZipArchive $zip): array
{
    $xml = $zip->getFromName('xl/sharedStrings.xml');

    if ($xml === false) {
        return [];
    }

    $reader = new \XMLReader();

    if (!$reader->XML($xml)) {
        throw new RuntimeException(
            'Gagal membaca sharedStrings.xml'
        );
    }

    $strings = [];

    while ($reader->read()) {

        if (
            $reader->nodeType !== \XMLReader::ELEMENT
            ||
            $reader->localName !== 'si'
        ) {
            continue;
        }

        $siXml = $reader->readOuterXml();

        if ($siXml === false) {
            continue;
        }

        $siReader = new \XMLReader();

        if (!$siReader->XML($siXml)) {
            continue;
        }

        $text = '';

        while ($siReader->read()) {

            if (
                $siReader->nodeType === \XMLReader::ELEMENT
                &&
                $siReader->localName === 't'
            ) {
                $text .=
                    (string) $siReader->readInnerXml();
            }
        }

        $siReader->close();

        $strings[] = html_entity_decode(
            strip_tags($text),
            ENT_QUOTES | ENT_XML1,
            'UTF-8'
        );
    }

    $reader->close();

    return $strings;
}

function findWorksheetPath(\ZipArchive $zip): string
{
    $workbookXml =
        $zip->getFromName('xl/workbook.xml');

    if ($workbookXml === false) {
        throw new RuntimeException(
            'xl/workbook.xml tidak ditemukan.'
        );
    }

    $relsXml =
        $zip->getFromName(
            'xl/_rels/workbook.xml.rels'
        );

    if ($relsXml === false) {
        throw new RuntimeException(
            'workbook.xml.rels tidak ditemukan.'
        );
    }

    $workbookReader = new \XMLReader();

    if (!$workbookReader->XML($workbookXml)) {
        throw new RuntimeException(
            'Gagal membaca workbook.xml.'
        );
    }

    $relationshipId = null;

    while ($workbookReader->read()) {

        if (
            $workbookReader->nodeType === \XMLReader::ELEMENT
            &&
            $workbookReader->localName === 'sheet'
        ) {
            $relationshipId =
                $workbookReader->getAttribute('r:id');

            break;
        }
    }

    $workbookReader->close();

    if ($relationshipId === null) {
        throw new RuntimeException(
            'Worksheet pertama tidak ditemukan.'
        );
    }

    $relsReader = new \XMLReader();

    if (!$relsReader->XML($relsXml)) {
        throw new RuntimeException(
            'Gagal membaca workbook.xml.rels.'
        );
    }

    $target = null;

    while ($relsReader->read()) {

        if (
            $relsReader->nodeType !== \XMLReader::ELEMENT
            ||
            $relsReader->localName !== 'Relationship'
        ) {
            continue;
        }

        if (
            $relsReader->getAttribute('Id')
            ===
            $relationshipId
        ) {
            $target =
                $relsReader->getAttribute('Target');

            break;
        }
    }

    $relsReader->close();

    if ($target === null) {
        throw new RuntimeException(
            'Target worksheet tidak ditemukan.'
        );
    }

    $target =
        ltrim(
            str_replace('\\', '/', $target),
            '/'
        );

    if (!str_starts_with($target, 'xl/')) {
        $target = 'xl/' . $target;
    }

    return $target;
}

function readCellValue(
    \XMLReader $reader,
    array $sharedStrings
) {
    $type = $reader->getAttribute('t');

    $xml = $reader->readOuterXml();

    if ($xml === false) {
        return null;
    }

    $cellReader = new \XMLReader();

    if (!$cellReader->XML($xml)) {
        return null;
    }

    $value = null;

    while ($cellReader->read()) {

        if (
            $cellReader->nodeType === \XMLReader::ELEMENT
            &&
            $cellReader->localName === 'v'
        ) {
            $value =
                $cellReader->readInnerXml();

            break;
        }

        if (
            $cellReader->nodeType === \XMLReader::ELEMENT
            &&
            $cellReader->localName === 't'
        ) {
            $value =
                $cellReader->readInnerXml();

            break;
        }
    }

    $cellReader->close();

    if ($value === null || $value === '') {
        return null;
    }

    if ($type === 's') {
        return $sharedStrings[(int) $value] ?? null;
    }

    return $value;
}

/*
|--------------------------------------------------------------------------
| Header mapping
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX KEMENDAG IMPORT 2019 MAY SOURCE DIAGNOSTIC\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;

echo "TARGET PERIOD:\n";
echo "  " . TARGET_PERIOD . PHP_EOL;

echo "TARGET CIF:\n";
echo "  " . number_format(
    TARGET_CIF,
    6,
    '.',
    ''
) . PHP_EOL;

echo "TARGET VOLUME:\n";
echo "  " . number_format(
    TARGET_VOLUME,
    6,
    '.',
    ''
) . PHP_EOL;

echo PHP_EOL;

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

$mapping =
    $parser->parse(
        SOURCE_FILE
    );

$period =
    $mapping['periods'][TARGET_PERIOD]
    ?? null;

if ($period === null) {
    throw new RuntimeException(
        'Period ' . TARGET_PERIOD . ' tidak ditemukan.'
    );
}

$static =
    $mapping['static']
    ?? [];

$cifColumn =
    (int) $period['value_column'];

$volumeColumn =
    (int) $period['volume_column'];

echo "COLUMN MAPPING:\n";
echo "  HS           : "
    . ($static['hs'] ?? 'N/A')
    . PHP_EOL;

echo "  URAIAN_HS    : "
    . ($static['uraian_hs'] ?? 'N/A')
    . PHP_EOL;

echo "  NEGARA       : "
    . ($static['negara'] ?? 'N/A')
    . PHP_EOL;

echo "  PROVINSI     : "
    . ($static['provinsi'] ?? 'N/A')
    . PHP_EOL;

echo "  PELABUHAN    : "
    . ($static['pelabuhan'] ?? 'N/A')
    . PHP_EOL;

echo "  CIF COLUMN   : {$cifColumn}\n";
echo "  VOLUME COLUMN: {$volumeColumn}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Open XLSX
|--------------------------------------------------------------------------
*/

$zip = new \ZipArchive();

if (
    $zip->open(
        SOURCE_FILE,
        \ZipArchive::RDONLY
    ) !== true
) {
    throw new RuntimeException(
        'Gagal membuka XLSX.'
    );
}

$sharedStrings =
    loadSharedStrings($zip);

$worksheetPath =
    findWorksheetPath($zip);

echo "SHARED STRINGS : "
    . count($sharedStrings)
    . PHP_EOL;

echo "WORKSHEET      : "
    . $worksheetPath
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Static columns needed for diagnostic
|--------------------------------------------------------------------------
*/

$requiredColumns = [
    (int) $static['hs'],
    (int) $static['uraian_hs'],
    (int) $static['negara'],
    (int) $static['provinsi'],
    (int) $static['pelabuhan'],
    $cifColumn,
    $volumeColumn,
];

$requiredColumns =
    array_values(
        array_unique($requiredColumns)
    );

/*
|--------------------------------------------------------------------------
| Streaming worksheet
|--------------------------------------------------------------------------
*/

$reader = new \XMLReader();

$worksheetUrl =
    'zip://'
    . SOURCE_FILE
    . '#'
    . $worksheetPath;

if (!$reader->open($worksheetUrl)) {
    $zip->close();

    throw new RuntimeException(
        'Gagal membuka worksheet XML secara streaming.'
    );
}

$matches = [];

$rowsScanned = 0;

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

    $rowXml =
        $reader->readOuterXml();

    if ($rowXml === false) {
        continue;
    }

    $rowReader = new \XMLReader();

    if (!$rowReader->XML($rowXml)) {
        continue;
    }

    $cells = [];

    while ($rowReader->read()) {

        if (
            $rowReader->nodeType !== \XMLReader::ELEMENT
            ||
            $rowReader->localName !== 'c'
        ) {
            continue;
        }

        $reference =
            $rowReader->getAttribute('r');

        if ($reference === null) {
            continue;
        }

        $parsed =
            parseExcelCellReference(
                $reference
            );

        if (
            !in_array(
                $parsed['column'],
                $requiredColumns,
                true
            )
        ) {
            continue;
        }

        $cells[
            $parsed['column']
        ] =
            readCellValue(
                $rowReader,
                $sharedStrings
            );
    }

    $rowReader->close();

    $rowsScanned++;

    $cif =
        numericValue(
            $cells[$cifColumn] ?? null
        );

    $volume =
        numericValue(
            $cells[$volumeColumn] ?? null
        );

    /*
     * Exact numeric diagnostic.
     */
    if (
        abs($cif - TARGET_CIF) < 0.000000001
        &&
        abs($volume - TARGET_VOLUME) < 0.000000001
    ) {

        $matches[] = [
            'source_row' =>
                $rowNumber,

            'hs' =>
                $cells[
                    (int) $static['hs']
                ] ?? null,

            'uraian_hs' =>
                $cells[
                    (int) $static['uraian_hs']
                ] ?? null,

            'country' =>
                $cells[
                    (int) $static['negara']
                ] ?? null,

            'province' =>
                $cells[
                    (int) $static['provinsi']
                ] ?? null,

            'port' =>
                $cells[
                    (int) $static['pelabuhan']
                ] ?? null,

            'cif' =>
                $cif,

            'volume' =>
                $volume,
        ];
    }
}

$reader->close();
$zip->close();

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "ROWS SCANNED:\n";
echo "  {$rowsScanned}\n\n";

echo "MATCHING SOURCE ROWS:\n";

if (empty($matches)) {

    echo "  NONE\n";

} else {

    foreach ($matches as $match) {

        echo "----------------------------------------\n";

        echo "  SOURCE ROW : "
            . $match['source_row']
            . PHP_EOL;

        echo "  HS         : "
            . ($match['hs'] ?? 'NULL')
            . PHP_EOL;

        echo "  URAIAN HS  : "
            . ($match['uraian_hs'] ?? 'NULL')
            . PHP_EOL;

        echo "  COUNTRY    : "
            . ($match['country'] ?? 'NULL')
            . PHP_EOL;

        echo "  PROVINCE   : "
            . ($match['province'] ?? 'NULL')
            . PHP_EOL;

        echo "  PORT       : "
            . ($match['port'] ?? 'NULL')
            . PHP_EOL;

        echo "  CIF        : "
            . number_format(
                $match['cif'],
                6,
                '.',
                ''
            )
            . PHP_EOL;

        echo "  VOLUME     : "
            . number_format(
                $match['volume'],
                6,
                '.',
                ''
            )
            . PHP_EOL;
    }
}

echo PHP_EOL;

echo "MATCH COUNT:\n";
echo "  " . count($matches) . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Production comparison
|--------------------------------------------------------------------------
*/

$dbMatches =
    DB::table('trade_statistics')
        ->where('import_batch_id', 99)
        ->where('month', 5)
        ->where('trade_value', TARGET_CIF)
        ->where('trade_volume', TARGET_VOLUME)
        ->select([
            'id',
            'hs_code',
            'country_name',
            'province_name',
            'port_name',
            'trade_value',
            'trade_volume',
            'trade_identity',
        ])
        ->orderBy('id')
        ->get();

echo "BATCH 99 MATCHING RECORDS:\n";

if ($dbMatches->isEmpty()) {

    echo "  NONE\n";

} else {

    foreach ($dbMatches as $row) {

        echo "----------------------------------------\n";

        echo "  DB ID        : "
            . $row->id
            . PHP_EOL;

        echo "  HS           : "
            . $row->hs_code
            . PHP_EOL;

        echo "  COUNTRY      : "
            . $row->country_name
            . PHP_EOL;

        echo "  PROVINCE     : "
            . $row->province_name
            . PHP_EOL;

        echo "  PORT         : "
            . $row->port_name
            . PHP_EOL;

        echo "  CIF          : "
            . $row->trade_value
            . PHP_EOL;

        echo "  VOLUME       : "
            . $row->trade_volume
            . PHP_EOL;

        echo "  IDENTITY     : "
            . $row->trade_identity
            . PHP_EOL;
    }
}

echo PHP_EOL;

echo "========================================\n";
echo "SOURCE DATABASE DIAGNOSTIC : COMPLETE\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";