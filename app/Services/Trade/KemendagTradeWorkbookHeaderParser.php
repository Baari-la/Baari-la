<?php

declare(strict_types=1);

namespace App\Services\Trade;

use RuntimeException;
use ZipArchive;
use XMLReader;

final class KemendagTradeWorkbookHeaderParser
{
    /**
     * Parse Kemendag workbook header.
     *
     * Supported value headers:
     *   EXPORT -> fob_MMYY
     *   IMPORT -> cif_MMYY
     *
     * Volume:
     *   vol_MMYY
     */
    public function parse(string $xlsxFile): array
    {
        if (!is_file($xlsxFile)) {
            throw new RuntimeException(
                "Workbook tidak ditemukan:\n{$xlsxFile}"
            );
        }

        $zip = new ZipArchive();

        if ($zip->open($xlsxFile) !== true) {
            throw new RuntimeException(
                "Tidak dapat membuka workbook:\n{$xlsxFile}"
            );
        }

        $sharedStrings =
            $this->loadSharedStrings($zip);

        $worksheetPath =
            $this->findFirstWorksheet($zip);

        if ($worksheetPath === null) {
            $zip->close();

            throw new RuntimeException(
                'Worksheet pertama tidak ditemukan.'
            );
        }

        $xml =
            $zip->getFromName(
                $worksheetPath
            );

        $zip->close();

        if ($xml === false) {
            throw new RuntimeException(
                "Tidak dapat membaca worksheet: {$worksheetPath}"
            );
        }

        $reader = new XMLReader();

        if (!$reader->XML($xml)) {
            throw new RuntimeException(
                'XMLReader gagal membaca worksheet.'
            );
        }

        $headers = [];

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

            if ($rowNumber > 10) {
                break;
            }

            $rowDepth = $reader->depth;

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

                $columnIndex =
                    $this->xlsxColumnIndex(
                        $reference
                    );

                if ($columnIndex < 1) {
                    $this->consumeCell($reader);
                    continue;
                }

                $headers[$columnIndex] =
                    trim(
                        (string) $this->readCellValue(
                            $reader,
                            $sharedStrings
                        )
                    );
            }

            if (
                $this->looksLikeKemendagHeader(
                    $headers
                )
            ) {
                break;
            }
        }

        $reader->close();

        if (empty($headers)) {
            throw new RuntimeException(
                'Header worksheet tidak berhasil ditemukan.'
            );
        }

        return $this->buildMapping($headers);
    }

    private function buildMapping(
        array $headers
    ): array {
        $normalized = [];

        foreach ($headers as $column => $header) {
            $normalized[$column] =
                strtolower(
                    trim((string) $header)
                );
        }

        $static = [];

        foreach (
            [
                'hs',
                'uraian_hs',
                'negara',
                'provinsi',
                'pelabuhan',
            ] as $required
        ) {
            $column =
                array_search(
                    $required,
                    $normalized,
                    true
                );

            if ($column === false) {
                throw new RuntimeException(
                    "Header wajib tidak ditemukan: {$required}"
                );
            }

            $static[$required] =
                $column;
        }

        $detectedFlows = [];

        foreach ($normalized as $column => $header) {

            if (
                preg_match(
                    '/^(fob|cif)_(\d{2})(\d{2})$/i',
                    $header,
                    $match
                )
            ) {
                $prefix =
                    strtolower(
                        $match[1]
                    );

                $month =
                    (int) $match[2];

                $year =
                    2000
                    +
                    (int) $match[3];

                if (
                    $month < 1
                    ||
                    $month > 12
                ) {
                    continue;
                }

                $periodKey =
                    sprintf(
                        '%04d-%02d',
                        $year,
                        $month
                    );

                $detectedFlows[$prefix] = true;

                $periodData =
                    $this->ensurePeriod(
                        $detectedFlows,
                        $prefix,
                        $periodKey,
                        $year,
                        $month
                    );

                $periods[$prefix][$periodKey] =
                    $periodData;

                $periods[$prefix][$periodKey][
                    'value_column'
                ] = $column;
            }

            if (
                preg_match(
                    '/^vol_(\d{2})(\d{2})$/i',
                    $header,
                    $match
                )
            ) {
                $month =
                    (int) $match[1];

                $year =
                    2000
                    +
                    (int) $match[2];

                if (
                    $month < 1
                    ||
                    $month > 12
                ) {
                    continue;
                }

                $periodKey =
                    sprintf(
                        '%04d-%02d',
                        $year,
                        $month
                    );

                $volumePeriods[$periodKey] = [
                    'year' => $year,
                    'month' => $month,
                    'volume_column' => $column,
                ];
            }
        }

        $availablePrefixes =
            array_keys(
                $detectedFlows
            );

        if (count($availablePrefixes) !== 1) {
            throw new RuntimeException(
                'Workbook harus memiliki tepat satu value schema: fob_* atau cif_*. '
                . 'Terdeteksi: '
                . implode(
                    ', ',
                    $availablePrefixes
                )
            );
        }

        $valuePrefix =
            $availablePrefixes[0];

        $tradeFlow =
            $valuePrefix === 'fob'
                ? 'export'
                : 'import';

        $periodsForFlow =
            $periods[$valuePrefix]
            ?? [];

        foreach (
            $periodsForFlow as $periodKey => &$period
        ) {
            if (
                !isset(
                    $volumePeriods[$periodKey]
                )
            ) {
                throw new RuntimeException(
                    "Period {$periodKey} tidak memiliki vol_MMYY."
                );
            }

            $period['volume_column'] =
                $volumePeriods[$periodKey][
                    'volume_column'
                ];
        }

        unset($period);

        if (empty($periodsForFlow)) {
            throw new RuntimeException(
                "Tidak ditemukan period {$valuePrefix}_MMYY."
            );
        }

        ksort($periodsForFlow);

        return [
            'trade_flow' =>
                $tradeFlow,

            'value_prefix' =>
                $valuePrefix . '_',

            'static' =>
                $static,

            'periods' =>
                $periodsForFlow,
        ];
    }

    private function ensurePeriod(
        array &$detectedFlows,
        string $prefix,
        string $periodKey,
        int $year,
        int $month
    ): array {
        return [
            'year' => $year,
            'month' => $month,
        ];
    }

    private function looksLikeKemendagHeader(
        array $headers
    ): bool {
        $values =
            array_map(
                static fn ($value): string =>
                    strtolower(
                        trim((string) $value)
                    ),
                $headers
            );

        foreach (
            [
                'hs',
                'uraian_hs',
                'negara',
                'provinsi',
                'pelabuhan',
            ] as $required
        ) {
            if (
                !in_array(
                    $required,
                    $values,
                    true
                )
            ) {
                return false;
            }
        }

        foreach ($values as $value) {
            if (
                preg_match(
                    '/^(fob|cif|vol)_\d{4}$/i',
                    $value
                )
            ) {
                return true;
            }
        }

        return false;
    }

    private function findFirstWorksheet(
        ZipArchive $zip
    ): ?string {
        $candidates = [];

        for (
            $index = 0;
            $index < $zip->numFiles;
            $index++
        ) {
            $name =
                $zip->getNameIndex($index);

            if (
                $name === false
                ||
                !preg_match(
                    '#^xl/worksheets/sheet\d+\.xml$#i',
                    $name
                )
            ) {
                continue;
            }

            $candidates[] = $name;
        }

        if (empty($candidates)) {
            return null;
        }

        usort(
            $candidates,
            static function (
                string $a,
                string $b
            ): int {
                preg_match(
                    '/sheet(\d+)\.xml$/i',
                    $a,
                    $ma
                );

                preg_match(
                    '/sheet(\d+)\.xml$/i',
                    $b,
                    $mb
                );

                return ((int) ($ma[1] ?? 999999))
                    <=>
                    ((int) ($mb[1] ?? 999999));
            }
        );

        return $candidates[0];
    }

    private function loadSharedStrings(
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

        $name =
            $zip->getNameIndex($index);

        $stream =
            $zip->getStream($name);

        if ($stream === false) {
            throw new RuntimeException(
                'Tidak dapat membuka sharedStrings.xml.'
            );
        }

        $temporary =
            tempnam(
                sys_get_temp_dir(),
                'digestex_trade_header_shared_'
            );

        if ($temporary === false) {
            fclose($stream);

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
            @unlink($temporary);

            throw new RuntimeException(
                'Gagal membuka temporary shared strings.'
            );
        }

        stream_copy_to_stream(
            $stream,
            $handle
        );

        fclose($stream);
        fclose($handle);

        $reader =
            new XMLReader();

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
                $current .=
                    $reader->readString();

                continue;
            }

            if (
                $current !== null
                &&
                $reader->nodeType === XMLReader::END_ELEMENT
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

    private function readCellValue(
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
                        $text .=
                            $reader->readString();
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

    private function consumeCell(
        XMLReader $reader
    ): void {
        $depth =
            $reader->depth;

        while ($reader->read()) {
            if (
                $reader->nodeType === XMLReader::END_ELEMENT
                &&
                $reader->localName === 'c'
                &&
                $reader->depth === $depth
            ) {
                break;
            }
        }
    }

    private function xlsxColumnIndex(
        string $reference
    ): int {
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
}