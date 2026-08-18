<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$file = $argv[1] ?? null;

if (!$file || !is_file($file)) {
    fwrite(
        STDERR,
        "Usage:\nphp tools\\inspect_kemendag_header_stream.php \"FULL_PATH.xlsx\"\n"
    );

    exit(1);
}

echo "========================================\n";
echo "DIGESTEX KEMENDAG HEADER INSPECTION\n";
echo "========================================\n\n";

echo "FILE:\n{$file}\n\n";

$zip = new \ZipArchive();

if ($zip->open($file) !== true) {
    throw new RuntimeException(
        'Tidak dapat membuka workbook.'
    );
}

/*
|--------------------------------------------------------------------------
| Load shared strings
|--------------------------------------------------------------------------
*/

$sharedStrings = [];

$sharedIndex =
    $zip->locateName(
        'xl/sharedStrings.xml',
        \ZipArchive::FL_NOCASE
    );

if ($sharedIndex !== false) {

    $sharedName =
        $zip->getNameIndex($sharedIndex);

    $stream =
        $zip->getStream($sharedName);

    if ($stream === false) {
        throw new RuntimeException(
            'Tidak dapat membuka sharedStrings.xml.'
        );
    }

    $temporary =
        tempnam(
            sys_get_temp_dir(),
            'digestex_header_shared_'
        );

    $handle =
        fopen($temporary, 'wb');

    stream_copy_to_stream(
        $stream,
        $handle
    );

    fclose($stream);
    fclose($handle);

    $reader =
        new \XMLReader();

    if (!$reader->open($temporary)) {
        @unlink($temporary);

        throw new RuntimeException(
            'XMLReader gagal membuka sharedStrings.xml.'
        );
    }

    $current = null;

    while ($reader->read()) {

        if (
            $reader->nodeType
                === \XMLReader::ELEMENT
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
                === \XMLReader::ELEMENT
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
                === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'si'
        ) {
            $sharedStrings[] = $current;
            $current = null;
        }
    }

    $reader->close();

    @unlink($temporary);
}

echo "SHARED STRINGS : "
    . count($sharedStrings)
    . "\n\n";

/*
|--------------------------------------------------------------------------
| Read worksheet
|--------------------------------------------------------------------------
*/

$worksheet =
    'xl/worksheets/sheet1.xml';

$sheetIndex =
    $zip->locateName(
        $worksheet,
        \ZipArchive::FL_NOCASE
    );

if ($sheetIndex === false) {
    $zip->close();

    throw new RuntimeException(
        "Worksheet tidak ditemukan: {$worksheet}"
    );
}

$sheetName =
    $zip->getNameIndex($sheetIndex);

$stream =
    $zip->getStream($sheetName);

$temporary =
    tempnam(
        sys_get_temp_dir(),
        'digestex_header_sheet_'
    );

$handle =
    fopen($temporary, 'wb');

stream_copy_to_stream(
    $stream,
    $handle
);

fclose($stream);
fclose($handle);
$zip->close();

/*
|--------------------------------------------------------------------------
| XML reader
|--------------------------------------------------------------------------
*/

$reader =
    new \XMLReader();

if (!$reader->open($temporary)) {
    @unlink($temporary);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

$header = [];

while ($reader->read()) {

    if (
        $reader->nodeType
            !== \XMLReader::ELEMENT
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

    if ($rowNumber !== 2) {
        continue;
    }

    $rowDepth =
        $reader->depth;

    while ($reader->read()) {

        if (
            $reader->nodeType
                === \XMLReader::END_ELEMENT
            &&
            $reader->localName === 'row'
            &&
            $reader->depth === $rowDepth
        ) {
            break;
        }

        if (
            $reader->nodeType
                !== \XMLReader::ELEMENT
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
                        ord($letters[$i])
                        - 64
                    );
            }
        }

        $cellType =
            $reader->getAttribute('t');

        $rawValue = null;

        $cellDepth =
            $reader->depth;

        while ($reader->read()) {

            if (
                $reader->nodeType
                    === \XMLReader::END_ELEMENT
                &&
                $reader->localName === 'c'
                &&
                $reader->depth === $cellDepth
            ) {
                break;
            }

            if (
                $reader->nodeType
                    === \XMLReader::ELEMENT
                &&
                $reader->localName === 'v'
            ) {
                $rawValue =
                    $reader->readString();

                break;
            }
        }

        if ($columnIndex <= 0) {
            continue;
        }

        if (
            $cellType === 's'
            &&
            $rawValue !== null
        ) {
            $index =
                (int) $rawValue;

            $value =
                $sharedStrings[$index]
                ?? "[UNKNOWN SHARED STRING {$index}]";
        } else {
            $value =
                $rawValue ?? '';
        }

        $header[$columnIndex] = [
            'column' => $reference,
            'value' => $value,
        ];
    }

    break;
}

$reader->close();
@unlink($temporary);

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

ksort($header);

echo "HEADER ROW 2:\n\n";

foreach ($header as $column => $item) {
    echo sprintf(
        "%-3s | %s\n",
        $item['column'],
        $item['value']
    );
}

echo "\n========================================\n";
echo "HEADER INSPECTION COMPLETE\n";
echo "========================================\n";