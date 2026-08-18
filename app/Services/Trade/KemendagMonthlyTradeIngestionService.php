<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\TradeImportBatch;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use ZipArchive;
use XMLReader;

final class KemendagMonthlyTradeIngestionService
{
    public function __construct(
    private readonly KemendagTradeWorkbookHeaderParser $headerParser,
    private readonly CountryResolverService $countryResolver,
    private readonly ProvinceResolverService $provinceResolver,
    private readonly TradePointResolverService $tradePointResolver,
) {
}

    /**
     * Ingest available periods from a Kemendag workbook.
     *
     * Examples:
     *
     *   ingest($file, 2026, 'regular');
     *   ingest($file, 2026, 'revision', ['2026-03']);
     *
     * The parser determines the real FOB/VOLUME columns from headers.
     */
    public function ingest(
        string $xlsxFile,
        ?int $targetYear = null,
        string $batchType = 'regular',
        ?array $periodFilter = null,
    ): TradeImportBatch {
        $batchType =
            strtolower(
                trim($batchType)
            );

        if (
            !in_array(
                $batchType,
                ['regular', 'revision'],
                true
            )
        ) {
            throw new RuntimeException(
                "batchType harus 'regular' atau 'revision'."
            );
        }

       $mapping =
    $this->headerParser->parse(
        $xlsxFile
    );

$tradeFlow =
    $mapping['trade_flow'];

$valuePrefix =
    $mapping['value_prefix'];

if (
    $tradeFlow === 'export'
    && $valuePrefix !== 'fob_'
) {
    throw new RuntimeException(
        'EXPORT workbook harus menggunakan value prefix fob_.'
    );
}

if (
    $tradeFlow === 'import'
    && $valuePrefix !== 'cif_'
) {
    throw new RuntimeException(
        'IMPORT workbook harus menggunakan value prefix cif_.'
    );
}
        
        $periods =
            $this->filterPeriods(
                $mapping['periods'],
                $targetYear,
                $periodFilter,
            );

        if (empty($periods)) {
            throw new RuntimeException(
                'Tidak ada period yang sesuai untuk ingestion.'
            );
        }

        $hsLookup =
            DB::table('mst_hscode')
                ->where(
                    'is_active',
                    true
                )
                ->pluck(
                    'id_hs',
                    'hs_code'
                )
                ->toArray();

        if (empty($hsLookup)) {
            throw new RuntimeException(
                'Canonical HS master mst_hscode kosong.'
            );
        }
if ($batchType === 'regular') {
    $this->assertRegularPeriodsAreNew(
        $periods,
        $tradeFlow
    );
}
        
        $batch =
            TradeImportBatch::create([
                'source' =>
                    'Kemendag',

                'filename' =>
                    basename($xlsxFile),

                'trade_flow' =>
                $tradeFlow,

                /*
                 * Batch year tetap merupakan tahun target.
                 * Untuk multi-year workbook, targetYear harus
                 * ditentukan secara eksplisit.
                 */
                'year' =>
                    $this->resolveBatchYear(
                        $periods,
                        $targetYear,
                    ),

                'release_date' =>
                    now(),

                'total_rows' =>
                    0,

                'inserted_rows' =>
                    0,

                'updated_rows' =>
                    0,

                'skipped_rows' =>
                    0,

                'failed_rows' =>
                    0,

                'status' =>
                    'processing',

                'remarks' =>
                    sprintf(
                        'Kemendag %s monthly ingestion. Periods: %s.',
                        $batchType,
                        implode(
                            ', ',
                            array_keys($periods)
                        )
                    ),

                'created_by' =>
                    auth()->id() ?? null,
            ]);

        $stats = [
            'source_rows' => 0,
            'active_source_rows' => 0,
            'zero_activity_rows' => 0,
            'monthly_records' => 0,
            'inserted_rows' => 0,
            'updated_rows' => 0,
            'skipped_rows' => 0,
            'fully_resolved' => 0,
            'partially_resolved' => 0,
            'hs_unresolved' => 0,
            'country_unresolved' => 0,
            'province_unresolved' => 0,
            'trade_point_unresolved' => 0,
        ];

        $startedAt =
            microtime(true);

        $reader = null;
        $sheetXml = null;

        try {
            $sharedStrings =
                $this->loadSharedStrings(
                    $xlsxFile
                );

            $sheetXml =
                $this->extractWorksheet(
                    $xlsxFile
                );

            $reader =
                new XMLReader();

            if (
                !$reader->open($sheetXml)
            ) {
                throw new RuntimeException(
                    'XMLReader gagal membuka worksheet.'
                );
            }

            $batchBuffer = [];

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

                /*
                 * Kemendag header berada di row 2.
                 * Data dimulai dari row 3.
                 */
                if ($sourceRow < 3) {
                    continue;
                }

                $row =
                    $this->readRow(
                        $reader,
                        $sharedStrings
                    );

                $hsCode =
                    trim(
                        (string) (
                            $row[
                                $mapping['static']['hs']
                            ] ?? ''
                        )
                    );

                if ($hsCode === '') {
                    $stats['skipped_rows']++;
                    continue;
                }

                $stats['source_rows']++;

                $hsDescription =
                    trim(
                        (string) (
                            $row[
                                $mapping['static']['uraian_hs']
                            ] ?? ''
                        )
                    );

                $countryName =
                    trim(
                        (string) (
                            $row[
                                $mapping['static']['negara']
                            ] ?? ''
                        )
                    );

                $provinceName =
                    trim(
                        (string) (
                            $row[
                                $mapping['static']['provinsi']
                            ] ?? ''
                        )
                    );

                $portName =
                    trim(
                        (string) (
                            $row[
                                $mapping['static']['pelabuhan']
                            ] ?? ''
                        )
                    );

                $hsId =
                    $hsLookup[
                        $hsCode
                    ] ?? null;

                if ($hsId === null) {
                    $stats['hs_unresolved']++;
                }

                $country =
                    $this->countryResolver->resolve(
                        $countryName,
                        'KEMENDAG'
                    );

                $province =
                    $this->provinceResolver->resolve(
                        $provinceName
                    );

                $tradePoint =
                    $this->tradePointResolver->resolve(
                        $portName,
                        'KEMENDAG'
                    );

                if ($country === null) {
                    $stats['country_unresolved']++;
                }

                if ($province === null) {
                    $stats['province_unresolved']++;
                }

                if ($tradePoint === null) {
                    $stats['trade_point_unresolved']++;
                }

                $rowHasActiveMonth = false;

                foreach (
                    $periods as $periodKey => $period
                ) {
                    $tradeValue =
                    $this->toTradeNumber(
                        $row[
                            $period['value_column']
                        ] ?? 0
                    );

                    $tradeVolume =
                        $this->toTradeNumber(
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

                    $stats['monthly_records']++;

                    $tradeIdentity =
                        hash(
                            'sha256',
                            implode(
                                '|',
                                [
                                    'Kemendag',
                                    $tradeFlow,
                                    $period['year'],
                                    $period['month'],
                                    $this->normalize(
                                        $hsCode
                                    ),
                                    $this->normalize(
                                        $countryName
                                    ),
                                    $this->normalize(
                                        $provinceName
                                    ),
                                    $this->normalize(
                                        $portName
                                    ),
                                ]
                            )
                        );

                    $allResolved =
                        $hsId !== null
                        &&
                        $country !== null
                        &&
                        $province !== null
                        &&
                        $tradePoint !== null;

                    if ($allResolved) {
                        $stats['fully_resolved']++;
                    } else {
                        $stats['partially_resolved']++;
                    }

                    $now =
                        now();

                    $batchBuffer[] = [

                        'import_batch_id' =>
                            $batch->id,

                        'source' =>
                            'Kemendag',

                        'trade_flow' =>
                        $tradeFlow,

                        'year' =>
                            $period['year'],

                        'month' =>
                            $period['month'],

                        'dimension' =>
                            'country',

                        'product' =>
                            null,

                        'product_category' =>
                            null,

                        'industry_segment' =>
                            null,

                        'hs_code' =>
                            $hsCode,

                        'hs_description' =>
                            $hsDescription,

                        'hs_id' =>
                            $hsId,

                        'country_code' =>
                            $country?->country_code,

                        'country_name' =>
                            $countryName,

                        'country_id' =>
                            $country?->id,

                        'province_code' =>
                            $province['code']
                            ?? null,

                        'province_name' =>
                            $provinceName,

                        'province_id' =>
                            $province['id']
                            ?? null,

                        'port_code' =>
                            $tradePoint['code']
                            ?? null,

                        'port_name' =>
                            $portName,

                        'trade_point_id' =>
                            $tradePoint['id']
                            ?? null,

                        'trade_point_type_id' =>
                            $tradePoint[
                                'trade_point_type_id'
                            ] ?? null,

                        'trade_identity' =>
                            $tradeIdentity,

                        'trade_value' =>
                            $tradeValue,

                        'currency' =>
                            'USD',

                        'trade_volume' =>
                            $tradeVolume,

                        'volume_unit' =>
                            'KG',

                        'release_date' =>
                            $now,

                        'remarks' =>
                            null,

                        'created_at' =>
                            $now,

                        'updated_at' =>
                            $now,
                    ];

                    if (
                        count($batchBuffer)
                        >= 1000
                    ) {
                        $this->flush(
                            $batchBuffer,
                            $stats,
                            $batchType,
                        );
                    }
                }

                if ($rowHasActiveMonth) {
                    $stats[
                        'active_source_rows'
                    ]++;
                } else {
                    $stats[
                        'zero_activity_rows'
                    ]++;
                }
            }

            if (!empty($batchBuffer)) {
                $this->flush(
                    $batchBuffer,
                    $stats,
                    $batchType,
                );
            }

            $duration =
                round(
                    microtime(true)
                    - $startedAt,
                    2
                );

            $batch->update([
                'total_rows' =>
                    $stats['monthly_records'],

                'inserted_rows' =>
                    $stats['inserted_rows'],

                'updated_rows' =>
                    $stats['updated_rows'],

                'skipped_rows' =>
                    $stats['skipped_rows'],

                'failed_rows' =>
                    0,

                'status' =>
                    'completed',

                'remarks' =>
                    sprintf(
                        'Kemendag %s monthly ingestion completed. Periods: %s. Source rows: %d, active source rows: %d, monthly records: %d, fully resolved: %d, partially resolved: %d, inserted: %d, updated: %d, duration: %.2f seconds.',
                        $batchType,
                        implode(
                            ', ',
                            array_keys($periods)
                        ),
                        $stats['source_rows'],
                        $stats['active_source_rows'],
                        $stats['monthly_records'],
                        $stats['fully_resolved'],
                        $stats['partially_resolved'],
                        $stats['inserted_rows'],
                        $stats['updated_rows'],
                        $duration
                    ),
            ]);

            return $batch;

        } catch (\Throwable $e) {

            if (
                $batch !== null
            ) {
                $batch->update([
                    'total_rows' =>
                        $stats['monthly_records'],

                    'inserted_rows' =>
                        $stats['inserted_rows'],

                    'updated_rows' =>
                        $stats['updated_rows'],

                    'skipped_rows' =>
                        $stats['skipped_rows'],

                    'failed_rows' =>
                        1,

                    'status' =>
                        'failed',

                    'remarks' =>
                        mb_substr(
                            'FAILED: '
                            . $e::class
                            . ' | '
                            . $e->getMessage(),
                            0,
                            1000
                        ),
                ]);
            }

            throw $e;

        } finally {

            if (
                $reader instanceof XMLReader
            ) {
                try {
                    $reader->close();
                } catch (\Throwable) {
                }
            }

            if (
                $sheetXml !== null
                &&
                is_file($sheetXml)
            ) {
                @unlink($sheetXml);
            }
        }
    }

    /**
     * Filter detected periods.
     */
    private function filterPeriods(
        array $periods,
        ?int $targetYear,
        ?array $periodFilter
    ): array {
        $result = [];

        foreach (
            $periods as $periodKey => $period
        ) {
            if (
                $targetYear !== null
                &&
                (int) $period['year']
                    !== $targetYear
            ) {
                continue;
            }

            if (
                $periodFilter !== null
                &&
                !in_array(
                    $periodKey,
                    $periodFilter,
                    true
                )
            ) {
                continue;
            }

            $result[$periodKey] =
                $period;
        }

        ksort($result);

        return $result;
    }

    /**
     * Determine batch year.
     */
    private function resolveBatchYear(
        array $periods,
        ?int $targetYear
    ): int {
        if ($targetYear !== null) {
            return $targetYear;
        }

        $years =
            array_values(
                array_unique(
                    array_map(
                        static fn (array $period): int =>
                            (int) $period['year'],
                        $periods
                    )
                )
            );

        if (count($years) !== 1) {
            throw new RuntimeException(
                'targetYear wajib diisi untuk workbook multi-year.'
            );
        }

        return $years[0];
    }

    /**
     * Flush records.
     *
     * Regular release:
     *   no existing period should be touched unexpectedly.
     *
     * Revision:
     *   existing identity may be updated.
     */
    private function flush(
        array &$buffer,
        array &$stats,
        string $batchType
    ): void {
        if (empty($buffer)) {
            return;
        }

        DB::transaction(
            function () use (
                &$buffer,
                &$stats,
                $batchType
            ): void {

                $updateColumns = [
                    'import_batch_id',
                    'source',
                    'trade_flow',
                    'dimension',
                    'product',
                    'product_category',
                    'industry_segment',
                    'hs_code',
                    'hs_description',
                    'hs_id',
                    'country_code',
                    'country_name',
                    'country_id',
                    'province_code',
                    'province_name',
                    'province_id',
                    'port_code',
                    'port_name',
                    'trade_point_id',
                    'trade_point_type_id',
                    'trade_value',
                    'currency',
                    'trade_volume',
                    'volume_unit',
                    'release_date',
                    'remarks',
                    'updated_at',
                ];

                /*
                 * IMPORTANT:
                 *
                 * The unique identity remains:
                 *   trade_identity + year + month
                 *
                 * This allows:
                 *   regular release -> insert new identity
                 *   revision       -> update same identity
                 */
                DB::table('trade_statistics')
                    ->upsert(
                        $buffer,
                        [
                            'trade_identity',
                            'year',
                            'month',
                        ],
                        $updateColumns
                    );

                if (
                    $batchType === 'revision'
                ) {
                    $stats['updated_rows'] +=
                        count($buffer);

                } else {
                    $stats['inserted_rows'] +=
                        count($buffer);
                }

                $buffer = [];
            }
        );
    }

    /**
     * Read one worksheet row into numeric column indexes.
     */
    private function readRow(
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

            $column =
                $this->xlsxColumnIndex(
                    $reference
                );

            if ($column < 1) {
                $this->consumeCell(
                    $reader
                );

                continue;
            }

            $values[$column] =
                $this->readCellValue(
                    $reader,
                    $sharedStrings
                );
        }

        return $values;
    }

    private function consumeCell(
        XMLReader $reader
    ): void {
        $depth =
            $reader->depth;

        while ($reader->read()) {
            if (
                $reader->nodeType
                    === XMLReader::END_ELEMENT
                &&
                $reader->localName === 'c'
                &&
                $reader->depth === $depth
            ) {
                break;
            }
        }
    }

    /**
     * Normalize source text for identity generation.
     */
    private function normalize(
        ?string $value
    ): string {
        $value =
            trim(
                (string) $value
            );

        if ($value === '') {
            return '';
        }

        return mb_strtoupper(
            preg_replace(
                '/\s+/',
                ' ',
                $value
            ) ?? ''
        );
    }

    /**
     * Numeric parser.
     */
    private function toTradeNumber(
        mixed $value
    ): float {
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

    /**
     * XLSX shared strings.
     */
    private function loadSharedStrings(
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
                'digestex_monthly_shared_'
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
                'Gagal membuat temporary file.'
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

    /**
     * Extract first worksheet.
     */
    private function extractWorksheet(
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
                'digestex_monthly_sheet_'
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

                $value =
                    $text;
            }
        }

        return $value;
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
            strtoupper(
                $match[1]
            );

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
    private function assertRegularPeriodsAreNew(
    array $periods,
    string $tradeFlow
): void {
    foreach ($periods as $periodKey => $period) {

        $exists =
            DB::table('trade_statistics')
                ->where('year', $period['year'])
                ->where('month', $period['month'])
                ->where('trade_flow', $tradeFlow)
                ->exists();

        if ($exists) {
            throw new RuntimeException(
                'REGULAR RELEASE ABORT: period '
                . $periodKey
                . ' untuk trade flow '
                . strtoupper($tradeFlow)
                . ' sudah memiliki data di trade_statistics. '
                . 'Gunakan batchType=revision untuk revisi.'
            );
        }
    }
}

}