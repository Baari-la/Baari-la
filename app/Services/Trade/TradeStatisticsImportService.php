<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\TradeImportBatch;
use App\Models\TradeStatistic;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TradeStatisticsImportService
{
    protected array $buffer = [];

protected int $bufferSize = 1000;
    protected ?TradeImportBatch $batch = null;

    protected array $summary = [

        'processed_workbooks' => 1,
        'processed_sheets'    => 0,
        'processed_rows'      => 0,
        'skipped_rows'        => 0,
        'error_rows'          => 0,
        'duration'            => 0,
        'errors'              => [],

    ];

    protected array $months = [

        'JAN' => 1,
        'FEB' => 2,
        'MAR' => 3,
        'APR' => 4,
        'MEI' => 5,
        'JUN' => 6,
        'JUL' => 7,
        'AGU' => 8,
        'SEP' => 9,
        'OKT' => 10,
        'NOV' => 11,
        'DES' => 12,

    ];

    protected float $startedAt;

    /**
     * Import Workbook
     */
    public function import(string $filePath): array
{
    $this->startedAt = microtime(true);

    // Import file besar tidak dibatasi timeout
    set_time_limit(0);

    ini_set('memory_limit', '1024M');

    try {

        /*
        |--------------------------------------------------------------------------
        | Create Import Batch
        |--------------------------------------------------------------------------
        */

        $this->createBatch($filePath);

        /*
        |--------------------------------------------------------------------------
        | Reader
        |--------------------------------------------------------------------------
        */

        $reader = IOFactory::createReaderForFile($filePath);

        $reader->setReadDataOnly(true);

        /*
        |--------------------------------------------------------------------------
        | Read Worksheet Names
        |--------------------------------------------------------------------------
        */

        $sheetNames = $reader->listWorksheetNames($filePath);

        if (empty($sheetNames)) {

            throw new \RuntimeException(
                'Workbook tidak memiliki worksheet.'
            );

        }

        \Log::info('Workbook Sheets', [

            'count'  => count($sheetNames),

            'sheets' => $sheetNames,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Import Each Sheet
        |--------------------------------------------------------------------------
        */

        foreach ($sheetNames as $sheetName) {

            \Log::info('Import Sheet', [

                'sheet' => $sheetName,

            ]);

            DB::transaction(function () use (

                $reader,
                $filePath,
                $sheetName

            ) {

                $this->importSheet(

                    $reader,
                    $filePath,
                    $sheetName

                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Duration
        |--------------------------------------------------------------------------
        */

        $this->summary['duration'] = round(

            microtime(true) - $this->startedAt,

            2

        );

        /*
        |--------------------------------------------------------------------------
        | Update Batch
        |--------------------------------------------------------------------------
        */

        $this->updateBatch();

        return $this->summary;

    } catch (\Throwable $e) {

        $this->failBatch($e);

        throw $e;

    }
}

    /**
     * Create Import Batch
     */
    protected function createBatch(string $filePath): void
    {
        $this->batch = TradeImportBatch::create([

            'source'         => 'Kemendag',
            'filename'       => basename($filePath),
            'trade_flow'     => 'mixed',
            'year'           => 0,
            'release_date'   => now(),
            'total_rows'     => 0,
            'inserted_rows'  => 0,
            'updated_rows'   => 0,
            'skipped_rows'   => 0,
            'failed_rows'    => 0,
            'status'         => 'processing',
            'remarks'        => null,
            'created_by'     => auth()->id() ?? null,

        ]);
    }

    /**
     * Import One Worksheet
     */
    protected function importSheet(
    Xlsx $reader,
    string $filePath,
    string $sheetName
): void
{
    \Log::info('IMPORT SHEET START', [
        'sheet' => $sheetName,
    ]);

    $reader->setLoadSheetsOnly($sheetName);

    $spreadsheet = $reader->load($filePath);

    $sheet = $spreadsheet->getActiveSheet();

    if ($sheet->getHighestRow() <= 1) {

        \Log::info('EMPTY SHEET', [
            'sheet' => $sheetName,
        ]);

        $spreadsheet->disconnectWorksheets();

        unset($sheet, $spreadsheet);

        gc_collect_cycles();

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Sheet Information
    |--------------------------------------------------------------------------
    */

    $sheetInfo = $this->detectSheetInfo(
        $sheet->getTitle()
    );

    \Log::info('SHEET INFO', $sheetInfo);

    /*
    |--------------------------------------------------------------------------
    | Read Worksheet
    |--------------------------------------------------------------------------
    */

    $rows = $sheet->toArray(
        null,
        true,
        true,
        true
    );

    \Log::info('TOTAL ROWS', [
        'rows' => count($rows),
    ]);

    /*
    |--------------------------------------------------------------------------
    | Detect Header Row
    |--------------------------------------------------------------------------
    */

    $headerRow = $this->detectHeaderRow($rows);

    \Log::info('HEADER ROW', [
        'row' => $headerRow,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Log Original Header
    |--------------------------------------------------------------------------
    */

    \Log::info('HEADER', $rows[$headerRow]);

    /*
    |--------------------------------------------------------------------------
    | Detect Month Mapping
    |--------------------------------------------------------------------------
    */

    $monthMapping = $this->detectMonthMapping(
        $rows[$headerRow]
    );

    \Log::info('MONTH MAPPING', $monthMapping);

    /*
    |--------------------------------------------------------------------------
    | Process Rows (Streaming)
    |--------------------------------------------------------------------------
    */

    $this->processRows(
        $rows,
        $headerRow,
        $sheetInfo,
        $monthMapping
    );

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    $this->summary['processed_sheets']++;

    /*
    |--------------------------------------------------------------------------
    | Release Memory
    |--------------------------------------------------------------------------
    */

    $spreadsheet->disconnectWorksheets();

    $reader->setLoadSheetsOnly(null);

    unset(
        $sheet,
        $rows,
        $spreadsheet
    );

    gc_collect_cycles();

    \Log::info('IMPORT SHEET FINISHED', [
        'sheet' => $sheetName,
    ]);
}

    /**
     * Detect Sheet Information
     */
    protected function detectSheetInfo(
        string $sheetName
    ): array
    {
        $name = strtoupper(trim($sheetName));

        return [

            'trade_flow' => str_contains($name, 'IMPOR')
                ? 'import'
                : 'export',

            'year' => preg_match('/20\d{2}/', $name, $match)
                ? (int) $match[0]
                : 0,

        ];
    }

    /**
     * Detect Header Row
     */
    protected function detectHeaderRow(array $rows): int
    {
        foreach ($rows as $index => $row) {

            $first = strtoupper(trim($row['A'] ?? ''));

            $second = strtoupper(trim($row['B'] ?? ''));

            if (
                $first === 'HS' &&
                str_contains($second, 'URAIAN')
            ) {
                return $index;
            }
        }

        throw new \RuntimeException(
            'Header Kemendag tidak ditemukan.'
        );
    }

    /**
     * Detect Month Mapping
     */
    protected function detectMonthMapping(
        array $header
    ): array
    {
        $mapping = [];

        foreach ($header as $column => $title) {

            $title = strtoupper(trim((string) $title));

            if (preg_match(
                '/^(JAN|FEB|MAR|APR|MEI|JUN|JUL|AGU|SEP|OKT|NOV|DES)_VALUE$/',
                $title,
                $match
            )) {

                $mapping[
                    $this->months[$match[1]]
                ]['value'] = $column;
            }

            if (preg_match(
                '/^(JAN|FEB|MAR|APR|MEI|JUN|JUL|AGU|SEP|OKT|NOV|DES)_VOLUME$/',
                $title,
                $match
            )) {

                $mapping[
                    $this->months[$match[1]]
                ]['volume'] = $column;
            }
        }

        return $mapping;
    }
        /**
     * Transform Excel Rows
     */
   protected function processRows(
    array $rows,
    int $headerRow,
    array $sheetInfo,
    array $monthMapping
): void
{
    \Log::info('PROCESS ROWS START');

    $now = now();

    foreach (array_slice($rows, $headerRow + 1, null, true) as $index => $row) {

        // Progress setiap 1000 baris
        if ($index % 1000 === 0) {
            \Log::info('PROCESSING ROW', [
                'row'    => $index,
                'memory' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
                'peak'   => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
            ]);
        }

        $hsCode = trim((string)($row['A'] ?? ''));

        if ($hsCode === '') {
            continue;
        }

        foreach ($monthMapping as $month => $column) {

            $tradeValue = $this->toNumber(
                $row[$column['value']] ?? 0
            );

            $tradeVolume = $this->toNumber(
                $row[$column['volume']] ?? 0
            );

            if ($tradeValue == 0 && $tradeVolume == 0) {
                continue;
            }

            $this->buffer[] = [

                'import_batch_id'   => $this->batch?->id,

                'source'            => 'Kemendag',

                'trade_flow'        => $sheetInfo['trade_flow'],

                'year'              => $sheetInfo['year'],

                'month'             => $month,

                'dimension'         => 'country',

                'product'           => null,

                'product_category'  => null,

                'industry_segment'  => null,

                'hs_code'           => $hsCode,

                'hs_description'    => trim((string)($row['B'] ?? '')),

                'country_code'      => trim((string)($row['C'] ?? '')),

                'country_name'      => trim((string)($row['D'] ?? '')),

                'trade_value'       => $tradeValue,

                'currency'          => 'USD',

                'trade_volume'      => $tradeVolume,

                'volume_unit'       => 'KG',

                'release_date'      => $now,

                'remarks'           => null,

                'created_at'        => $now,

                'updated_at'        => $now,

            ];

            // Simpan setiap 1000 record
            if (count($this->buffer) >= $this->bufferSize) {

                $this->saveRecords($this->buffer);

                $this->buffer = [];
            }
        }
    }

    // Simpan sisa buffer
    if (!empty($this->buffer)) {

        $this->saveRecords($this->buffer);

        $this->buffer = [];
    }

    \Log::info('PROCESS ROWS FINISHED');
}
    /**
     * Save Records
     */
   protected function saveRecords(array $records): void
{
    if (empty($records)) {
        return;
    }

    foreach (array_chunk($records, 1000) as $chunk) {

        TradeStatistic::upsert(

            $chunk,

            [
                'source',
                'trade_flow',
                'year',
                'month',
                'hs_code',
                'country_code',
            ],

            [
                'import_batch_id',
                'hs_description',
                'country_name',
                'trade_value',
                'trade_volume',
                'currency',
                'volume_unit',
                'release_date',
                'remarks',
                'updated_at',
            ]

        );

        $count = count($chunk);

        $this->summary['processed_rows'] =
            ($this->summary['processed_rows'] ?? 0) + $count;

        $this->summary['total_rows'] =
            ($this->summary['total_rows'] ?? 0) + $count;

        /*
        |--------------------------------------------------------------------------
        | Upsert tidak membedakan inserted / updated.
        | Untuk sementara seluruh record dihitung sebagai processed.
        |--------------------------------------------------------------------------
        */

        $this->summary['processed'] =
            ($this->summary['processed'] ?? 0) + $count;
    }
}

    /**
     * Update Import Batch
     */
    protected function updateBatch(): void
    {
        if (!$this->batch) {
            return;
        }

        $this->batch->update([

            'total_rows' => $this->summary['processed_rows'],

            // sementara memakai processed_rows
            'inserted_rows' => $this->summary['processed_rows'],

            'updated_rows' => 0,

            'skipped_rows' => $this->summary['skipped_rows'],

            'failed_rows' => $this->summary['error_rows'],

            'status' => 'completed',

            'remarks' => sprintf(
                'Processed %d workbook(s), %d worksheet(s) in %.2f second(s)',
                $this->summary['processed_workbooks'],
                $this->summary['processed_sheets'],
                $this->summary['duration']
            ),

        ]);
    }

    /**
     * Mark Batch as Failed
     */
    protected function failBatch(
        \Throwable $e
    ): void
    {
        if (!$this->batch) {
            return;
        }

        $this->batch->update([

            'status' => 'failed',

            'remarks' => $e->getMessage(),

        ]);
    }

    /**
 * Convert Excel Number
 */
protected function toNumber($value): float
{
    if ($value === null) {
        return 0;
    }

    $value = trim((string) $value);

    if ($value === '') {
        return 0;
    }

    $value = str_replace(',', '', $value);

    if (!is_numeric($value)) {
        return 0;
    }

    return (float) $value;
}
}