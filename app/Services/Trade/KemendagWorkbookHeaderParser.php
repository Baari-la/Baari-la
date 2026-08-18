<?php

declare(strict_types=1);

namespace App\Services\Trade;

use RuntimeException;
use ZipArchive;
use XMLReader;

final class KemendagWorkbookHeaderParser
{
    /**
     * Parse the first worksheet header and detect monthly FOB/VOLUME columns.
     *
     * Expected source columns:
     *   hs
     *   uraian_hs
     *   negara
     *   provinsi
     *   pelabuhan
     *   fob_MMYY
     *   vol_MMYY
     *
     * Example:
     *   fob_0126 -> 2026-01
     *   vol_0126 -> 2026-01
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

        $sharedStrings = $this->loadSharedStrings($zip);

        $worksheetPath = $this->findFirstWorksheet($zip);

        if ($worksheetPath === null) {
            $zip->close();

            throw new RuntimeException(
                'Worksheet pertama tidak ditemukan.'
            );
        }

        $xml = $zip->getFromName($worksheetPath);

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

            /*
             * Header Kemendag selama ini berada di row 2.
             * Kita gunakan row pertama yang mengandung header
             * canonical seperti "hs".
             */
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

                $value =
                    $this->readCellValue(
                        $reader,
                        $sharedStrings
                    );

                if ($columnIndex > 0) {
                    $headers[$columnIndex] =
                        trim((string) $value);
                }
            }

            /*
             * Once a row contains the canonical source header,
             * stop scanning.
             */
            if (
                in_array(
                    'hs',
                    array_map(
                        static fn ($value) =>
                            strtolower(trim((string) $value)),
                        $headers
                    ),
                    true
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

    /**
     * Find the first worksheet XML path.
     */
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

    /**
     * Build normalized header mapping.
     */
    private function buildMapping(
        array $headers
    ): array {
        $normalizedHeaders = [];

        foreach ($headers as $column => $header) {
            $normalized =
                strtolower(
                    trim((string) $header)
                );

            $normalizedHeaders[$column] =
                $normalized;
        }

        $mapping = [
            'static' => [],
            'periods' => [],
        ];

        /*
        |--------------------------------------------------------------------------
        | Static columns
        |--------------------------------------------------------------------------
        */

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
                    $normalizedHeaders,
                    true
                );

            if ($column === false) {
                throw new RuntimeException(
                    "Header wajib tidak ditemukan: {$required}"
                );
            }

            $mapping['static'][$required] =
                $column;
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly columns
        |--------------------------------------------------------------------------
        */

        foreach (
            $normalizedHeaders as $column => $header
        ) {
            if (
                preg_match(
                    '/^fob_(\d{2})(\d{2})$/i',
                    $header,
                    $match
                )
            ) {
                $month =
                    (int) $match[1];

                $yearShort =
                    (int) $match[2];

                if (
                    $month < 1
                    ||
                    $month > 12
                ) {
                    continue;
                }

                $year =
                    2000 + $yearShort;

                $periodKey =
                    sprintf(
                        '%04d-%02d',
                        $year,
                        $month
                    );

                $mapping['periods'][
                    $periodKey
                ]['year'] = $year;

                $mapping['periods'][
                    $periodKey
                ]['month'] = $month;

                $mapping['periods'][
                    $periodKey
                ]['fob_column'] = $column;

                continue;
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

                $yearShort =
                    (int) $match[2];

                if (
                    $month < 1
                    ||
                    $month > 12
                ) {
                    continue;
                }

                $year =
                    2000 + $yearShort;

                $periodKey =
                    sprintf(
                        '%04d-%02d',
                        $year,
                        $month
                    );

                $mapping['periods'][
                    $periodKey
                ]['year'] = $year;

                $mapping['periods'][
                    $periodKey
                ]['month'] = $month;

                $mapping['periods'][
                    $periodKey
                ]['volume_column'] = $column;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate each detected period
        |--------------------------------------------------------------------------
        */

        foreach (
            $mapping['periods'] as $period => $data
        ) {
            if (
                !isset($data['fob_column'])
                ||
                !isset($data['volume_column'])
            ) {
                throw new RuntimeException(
                    "Period {$period} tidak memiliki pasangan FOB/VOLUME lengkap."
                );
            }
        }

        if (empty($mapping['periods'])) {
            throw new RuntimeException(
                'Tidak ditemukan kolom FOB/VOLUME bulanan.'
            );
        }

        ksort(
            $mapping['periods']
        );

        return $mapping;
    }

    /**
     * Load XLSX shared strings.
     */
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
                'digestex_header_shared_'
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
                $strings[] = $current;
                $current = null;
            }
        }

        $reader->close();

        @unlink($temporary);

        return $strings;
    }

    /**
     * Read XLSX cell.
     */
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

    /**
     * Convert XLSX cell reference to 1-based column index.
     */
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