<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();



$fileArgument = $argv[1] ?? null;

if ($fileArgument === null || trim($fileArgument) === '') {
    throw new RuntimeException(
        'Usage: php tools\inspect_kemendag_import_header_stream.php "PATH_TO_XLSX"'
    );
}

$sourceFile = trim($fileArgument);

if (!is_file($sourceFile)) {
    throw new RuntimeException(
        "File tidak ditemukan:\n{$sourceFile}"
    );
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
            'digestex_import_header_shared_'
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

    stream_copy_to_stream(
        $stream,
        $handle
    );

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
echo "DIGESTEX KEMENDAG IMPORT HEADER INSPECTION\n";
echo "========================================\n\n";

echo "FILE:\n";
echo $sourceFile . PHP_EOL;
echo PHP_EOL;

$zip = new ZipArchive();

if ($zip->open($sourceFile) !== true) {
    throw new RuntimeException(
        'Tidak dapat membuka workbook.'
    );
}

$sharedStrings =
    loadSharedStrings($zip);

echo "SHARED STRINGS : "
    . count($sharedStrings)
    . PHP_EOL;

echo PHP_EOL;

$worksheetName = null;

for (
    $i = 0;
    $i < $zip->numFiles;
    $i++
) {
    $name = $zip->getNameIndex($i);

    if (
        $name !== false
        &&
        preg_match(
            '#^xl/worksheets/sheet\d+\.xml$#i',
            $name
        )
    ) {
        $worksheetName = $name;
        break;
    }
}

if ($worksheetName === null) {
    $zip->close();

    throw new RuntimeException(
        'Worksheet tidak ditemukan.'
    );
}

$worksheetStream =
    $zip->getStream($worksheetName);

if ($worksheetStream === false) {
    $zip->close();

    throw new RuntimeException(
        "Tidak dapat membuka worksheet: {$worksheetName}"
    );
}

$temporarySheet =
    tempnam(
        sys_get_temp_dir(),
        'digestex_import_header_sheet_'
    );

if ($temporarySheet === false) {
    fclose($worksheetStream);
    $zip->close();

    throw new RuntimeException(
        'Gagal membuat temporary worksheet.'
    );
}

$temporaryHandle =
    fopen(
        $temporarySheet,
        'wb'
    );

if ($temporaryHandle === false) {
    fclose($worksheetStream);
    $zip->close();
    @unlink($temporarySheet);

    throw new RuntimeException(
        'Gagal membuka temporary worksheet.'
    );
}

stream_copy_to_stream(
    $worksheetStream,
    $temporaryHandle
);

fclose($worksheetStream);
fclose($temporaryHandle);
$zip->close();

$reader = new XMLReader();

if (!$reader->open($temporarySheet)) {
    @unlink($temporarySheet);

    throw new RuntimeException(
        'XMLReader gagal membuka worksheet.'
    );
}

$headers = [];
$headerRow = null;

while ($reader->read()) {

    if (
        $reader->nodeType !== XMLReader::ELEMENT
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

    /*
     * Inspect the first several rows so we can see
     * where the actual import header is located.
     */
    if ($rowNumber > 10) {
        break;
    }

    $rowDepth = $reader->depth;
    $rowValues = [];

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

        $rowValues[$column] =
            trim(
                (string) readCellValue(
                    $reader,
                    $sharedStrings
                )
            );
    }

    /*
     * Print every inspected row.
     * This is intentionally read-only.
     */
    echo "ROW {$rowNumber}:\n";

    if (empty($rowValues)) {
        echo "  (empty)\n";
    } else {
        foreach (
            $rowValues as $column => $value
        ) {
            $letter = '';

            $n = $column;

            while ($n > 0) {
                $n--;
                $letter =
                    chr(
                        65 + ($n % 26)
                    )
                    . $letter;

                $n =
                    intdiv(
                        $n,
                        26
                    );
            }

            echo sprintf(
                "  %-4s | %s\n",
                $letter . $rowNumber,
                $value
            );
        }
    }

    echo PHP_EOL;

    /*
     * Heuristic header detection.
     */
    $lowerValues =
        array_map(
            static fn ($value): string =>
                strtolower(
                    trim((string) $value)
                ),
            $rowValues
        );

    foreach (
        [
            'hs',
            'uraian_hs',
            'negara',
            'provinsi',
            'pelabuhan',
            'fob_',
            'cif_',
            'vol_',
        ] as $marker
    ) {
        foreach ($lowerValues as $value) {
            if (
                $marker === 'fob_'
                || $marker === 'cif_'
                || $marker === 'vol_'
            ) {
                if (
                    str_starts_with(
                        $value,
                        $marker
                    )
                ) {
                    $headerRow =
                        $rowNumber;

                    break 2;
                }
            }

            if ($value === $marker) {
                $headerRow =
                    $rowNumber;

                break 2;
            }
        }
    }
}

$reader->close();
@unlink($temporarySheet);

echo "========================================\n";

if ($headerRow !== null) {
    echo "POSSIBLE HEADER ROW : "
        . $headerRow
        . PHP_EOL;
} else {
    echo "POSSIBLE HEADER ROW : NOT DETECTED\n";
}

echo "WORKSHEET           : "
    . $worksheetName
    . PHP_EOL;

echo "DATABASE             : NOT MODIFIED\n";

echo "========================================\n";