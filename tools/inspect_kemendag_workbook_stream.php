<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use ZipArchive;
use XMLReader;

$file = $argv[1] ?? null;

if (!$file || !is_file($file)) {
    fwrite(
        STDERR,
        "Usage:\nphp tools\\inspect_kemendag_workbook_stream.php \"FULL_PATH.xlsx\"\n"
    );

    exit(1);
}

echo "========================================\n";
echo "DIGESTEX KEMENDAG WORKBOOK STREAM INSPECTION\n";
echo "========================================\n\n";

echo "FILE:\n{$file}\n\n";

$zip = new ZipArchive();

if ($zip->open($file) !== true) {
    throw new RuntimeException(
        "Tidak dapat membuka workbook."
    );
}

/*
|--------------------------------------------------------------------------
| List workbook parts
|--------------------------------------------------------------------------
*/

$worksheetParts = [];

for ($i = 0; $i < $zip->numFiles; $i++) {

    $name = $zip->getNameIndex($i);

    if (
        str_starts_with(
            $name,
            'xl/worksheets/'
        )
        &&
        str_ends_with(
            strtolower($name),
            '.xml'
        )
    ) {
        $worksheetParts[] = $name;
    }
}

echo "WORKSHEETS FOUND: "
    . count($worksheetParts)
    . "\n\n";

/*
|--------------------------------------------------------------------------
| Inspect workbook XML
|--------------------------------------------------------------------------
*/

foreach ($worksheetParts as $sheetIndex => $sheetPart) {

    echo "SHEET #" . ($sheetIndex + 1) . ": {$sheetPart}\n";

    $stream =
        $zip->getStream($sheetPart);

    if ($stream === false) {
        echo "  ERROR: cannot open sheet stream.\n\n";
        continue;
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_inspect_sheet_'
        );

    if ($temporary === false) {
        fclose($stream);

        throw new RuntimeException(
            'Gagal membuat temporary file.'
        );
    }

    $handle =
        fopen(
            $temporary,
            'wb'
        );

    if ($handle === false) {
        fclose($stream);
        @unlink($temporary);

        throw new RuntimeException(
            'Gagal membuka temporary file.'
        );
    }

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);

    /*
    |--------------------------------------------------------------------------
    | Read XML
    |--------------------------------------------------------------------------
    */

    $reader = new XMLReader();

    if (!$reader->open($temporary)) {
        @unlink($temporary);

        throw new RuntimeException(
            "XMLReader gagal membuka {$sheetPart}"
        );
    }

    $dimension = null;
    $rowCount = 0;
    $sampleRows = [];

    while ($reader->read()) {

        /*
        |--------------------------------------------------------------------------
        | Dimension
        |--------------------------------------------------------------------------
        */

        if (
            $reader->nodeType
                === XMLReader::ELEMENT
            &&
            $reader->localName === 'dimension'
        ) {
            $dimension =
                $reader->getAttribute('ref');
        }

        /*
        |--------------------------------------------------------------------------
        | Rows
        |--------------------------------------------------------------------------
        */

        if (
            $reader->nodeType
                !== XMLReader::ELEMENT
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
                $reader->nodeType
                    !== XMLReader::ELEMENT
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

            $columnIndex = 0;

            if (
                preg_match(
                    '/^([A-Z]+)\d+$/i',
                    $reference,
                    $match
                )
            ) {
                $letters =
                    strtoupper($match[1]);

                for (
                    $i = 0;
                    $i < strlen($letters);
                    $i++
                ) {
                    $columnIndex =
                        ($columnIndex * 26)
                        +
                        (
                            ord(
                                $letters[$i]
                            ) - 64
                        );
                }
            }

            $cellType =
                $reader->getAttribute('t');

            $value = '';

            $cellDepth =
                $reader->depth;

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
                    $value =
                        $reader->readString();

                    break;
                }

                if (
                    $reader->nodeType
                        === XMLReader::ELEMENT
                    &&
                    $reader->localName === 't'
                ) {
                    $value =
                        $reader->readString();

                    break;
                }
            }

            if ($columnIndex > 0) {
                $values[$columnIndex] =
                    $value;
            }
        }

        $rowCount++;

        if (count($sampleRows) < 10) {
            $sampleRows[$rowNumber] =
                $values;
        }
    }

    $reader->close();

    @unlink($temporary);

    echo "  DIMENSION : "
        . ($dimension ?? 'UNKNOWN')
        . "\n";

    echo "  ROWS SCANNED: "
        . $rowCount
        . "\n";

    echo "  FIRST ROWS:\n";

    foreach (
        $sampleRows as $rowNumber => $values
    ) {

        ksort($values);

        echo "    ROW {$rowNumber}: ";

        $parts = [];

        foreach (
            $values as $column => $value
        ) {
            $parts[] =
                $column
                . '='
                . trim(
                    (string) $value
                );
        }

        echo implode(
            ' | ',
            $parts
        );

        echo "\n";
    }

    echo "\n";
}

$zip->close();

echo "========================================\n";
echo "STREAM INSPECTION COMPLETE\n";
echo "========================================\n";