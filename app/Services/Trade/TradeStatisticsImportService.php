<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\TradeImportBatch;
use App\Models\TradeStatistic;
use App\Services\Trade\CountryResolverService;
use App\Services\Trade\ProvinceResolverService;
use App\Services\Trade\TradePointResolverService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use RuntimeException;
use Throwable;

class TradeStatisticsImportService
{
    protected array $buffer = [];
    protected int $bufferSize = 1000;
    protected ?TradeImportBatch $batch = null;

    protected CountryResolverService $countryResolver;
    protected ProvinceResolverService $provinceResolver;
    protected TradePointResolverService $tradePointResolver;

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

    public function __construct(
        CountryResolverService $countryResolver,
        ProvinceResolverService $provinceResolver,
        TradePointResolverService $tradePointResolver
    ) {
        $this->countryResolver = $countryResolver;
        $this->provinceResolver = $provinceResolver;
        $this->tradePointResolver = $tradePointResolver;
    }

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
                throw new RuntimeException('Workbook tidak memiliki worksheet.');
            }

            Log::info('Workbook Sheets', [
                'count'  => count($sheetNames),
                'sheets' => $sheetNames,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Import Each Sheet
            |--------------------------------------------------------------------------
            */
            foreach ($sheetNames as $sheetName) {
                Log::info('Import Sheet', [
                    'sheet' => $sheetName,
                ]);

                DB::transaction(function () use ($reader, $filePath, $sheetName) {
                    $this->importSheet($reader, $filePath, $sheetName);
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Duration & Update Batch
            |--------------------------------------------------------------------------
            */
            $this->summary['duration'] = round(microtime(true) - $this->startedAt, 2);
            $this->updateBatch();

            return $this->summary;

        } catch (Throwable $e) {
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
            'source'        => 'Kemendag',
            'filename'      => basename($filePath),
            'trade_flow'    => 'mixed',
            'year'          => 0,
            'release_date'  => now(),
            'total_rows'    => 0,
            'inserted_rows' => 0,
            'updated_rows'  => 0,
            'skipped_rows'  => 0,
            'failed_rows'   => 0,
            'status'        => 'processing',
            'remarks'       => null,
            'created_by'    => auth()->id() ?? null,
        ]);
    }

    /**
     * Import One Worksheet
     */
    protected function importSheet(Xlsx|IOFactory $reader, string $filePath, string $sheetName): void
    {
        Log::info('IMPORT SHEET START', [
            'sheet' => $sheetName,
        ]);

        $reader->setLoadSheetsOnly($sheetName);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        if ($sheet->getHighestRow() <= 1) {
            Log::info('EMPTY SHEET', [
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
        $sheetInfo = $this->detectSheetInfo($sheet->getTitle());
        Log::info('SHEET INFO', $sheetInfo);

        /*
        |--------------------------------------------------------------------------
        | Read Worksheet
        |--------------------------------------------------------------------------
        */
        $rows = $sheet->toArray(null, true, true, true);
        Log::info('TOTAL ROWS', [
            'rows' => count($rows),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Detect Header Row & Month Mapping
        |--------------------------------------------------------------------------
        */
        $headerRow = $this->detectHeaderRow($rows);
        Log::info('HEADER ROW', ['row' => $headerRow]);
        Log::info('HEADER', $rows[$headerRow]);

        $monthMapping = $this->detectMonthMapping($rows[$headerRow]);
        Log::info('MONTH MAPPING', $monthMapping);

        /*
        |--------------------------------------------------------------------------
        | Process Rows (Streaming)
        |--------------------------------------------------------------------------
        */
        $this->processRows($rows, $headerRow, $sheetInfo, $monthMapping);

        /*
        |--------------------------------------------------------------------------
        | Summary & Release Memory
        |--------------------------------------------------------------------------
        */
        $this->summary['processed_sheets']++;

        $spreadsheet->disconnectWorksheets();
        $reader->setLoadSheetsOnly(null);

        unset($sheet, $rows, $spreadsheet);
        gc_collect_cycles();

        Log::info('IMPORT SHEET FINISHED', [
            'sheet' => $sheetName,
        ]);
    }

    /**
     * Detect Sheet Information
     */
    protected function detectSheetInfo(string $sheetName): array
    {
        $name = strtoupper(trim($sheetName));

        return [
            'trade_flow' => str_contains($name, 'IMPOR') ? 'import' : 'export',
            'year'       => preg_match('/20\d{2}/', $name, $match) ? (int) $match[0] : 0,
        ];
    }

    /**
     * Detect Header Row
     */
    protected function detectHeaderRow(array $rows): int
    {
        foreach ($rows as $index => $row) {
            $normalized = array_map(
                fn ($value) => strtolower(trim((string) $value)),
                $row
            );

            $hasHs = in_array('hs', $normalized, true);
            $hasHsDescription = in_array('uraian_hs', $normalized, true);

            $hasFobColumn = false;
            $hasVolumeColumn = false;

            foreach ($normalized as $value) {
                if (preg_match('/^fob_\d{4}$/', $value)) {
                    $hasFobColumn = true;
                }
                if (preg_match('/^vol_\d{4}$/', $value)) {
                    $hasVolumeColumn = true;
                }
            }

            if ($hasHs && $hasHsDescription && $hasFobColumn && $hasVolumeColumn) {
                return $index;
            }
        }

        throw new RuntimeException('Header teknis Kemendag tidak ditemukan.');
    }

    /**
     * Detect Month Mapping
     */
    protected function detectMonthMapping(array $header): array
    {
        $mapping = [];

        foreach ($header as $column => $title) {
            $title = strtolower(trim((string) $title));

            /*
            |--------------------------------------------------------------------------
            | FOB / Trade Value (fob_0124, fob_0224, dst.)
            |--------------------------------------------------------------------------
            */
            if (preg_match('/^fob_(\d{2})(\d{2})$/', $title, $match)) {
                $month = (int) $match[1];
                $year  = 2000 + (int) $match[2];

                if ($month >= 1 && $month <= 12) {
                    $key = sprintf('%04d-%02d', $year, $month);
                    $mapping[$key]['year'] = $year;
                    $mapping[$key]['month'] = $month;
                    $mapping[$key]['value'] = $column;
                }
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Volume (vol_0124, vol_0224, dst.)
            |--------------------------------------------------------------------------
            */
            if (preg_match('/^vol_(\d{2})(\d{2})$/', $title, $match)) {
                $month = (int) $match[1];
                $year  = 2000 + (int) $match[2];

                if ($month >= 1 && $month <= 12) {
                    $key = sprintf('%04d-%02d', $year, $month);
                    $mapping[$key]['year'] = $year;
                    $mapping[$key]['month'] = $month;
                    $mapping[$key]['volume'] = $column;
                }
            }
        }

        return array_filter(
            $mapping,
            fn ($item) => isset($item['value']) && isset($item['volume'])
        );
    }

    /**
     * Process Rows (Streaming)
     */
    protected function processRows(
        array $rows,
        int $headerRow,
        array $sheetInfo,
        array $monthMapping
    ): void {
        Log::info('PROCESS ROWS START');

        $now = now();

        $normalize = function (?string $value): string {
            $value = trim((string) $value);
            $value = preg_replace('/\s+/', ' ', $value) ?? '';
            return mb_strtoupper($value);
        };

        foreach (array_slice($rows, $headerRow + 1, null, true) as $index => $row) {

            if ($index % 1000 === 0) {
                Log::info('PROCESSING ROW', [
                    'row'    => $index,
                    'memory' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
                    'peak'   => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Base dimensions
            |--------------------------------------------------------------------------
            */
            $hsCode        = trim((string) ($row['A'] ?? ''));
            $hsDescription = trim((string) ($row['B'] ?? ''));
            $countryName   = trim((string) ($row['C'] ?? ''));
            $provinceName  = trim((string) ($row['D'] ?? ''));
            $portName      = trim((string) ($row['E'] ?? ''));

            /*
            |--------------------------------------------------------------------------
            | Skip invalid rows
            |--------------------------------------------------------------------------
            */
            if ($hsCode === '') {
                $this->summary['skipped_rows']++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Master Resolution
            |--------------------------------------------------------------------------
            */
            $country    = $this->countryResolver->resolve($countryName, 'KEMENDAG');
            $province   = $this->provinceResolver->resolve($provinceName);
            $tradePoint = $this->tradePointResolver->resolve($portName, 'KEMENDAG');

            /*
            |--------------------------------------------------------------------------
            | Monthly Trade Data
            |--------------------------------------------------------------------------
            */
            foreach ($monthMapping as $period => $columns) {

                $tradeValue  = $this->toNumber($row[$columns['value']] ?? 0);
                $tradeVolume = $this->toNumber($row[$columns['volume']] ?? 0);

                if ($tradeValue == 0 && $tradeVolume == 0) {
                    continue;
                }

                $tradeIdentity = hash(
                    'sha256',
                    implode('|', [
                        'Kemendag',
                        $sheetInfo['trade_flow'],
                        $columns['year'],
                        $columns['month'],
                        $normalize($hsCode),
                        $normalize($countryName),
                        $normalize($provinceName),
                        $normalize($portName),
                    ])
                );

                /*
                |--------------------------------------------------------------------------
                | Append normalized record
                |--------------------------------------------------------------------------
                */
                $this->buffer[] = [
                    'import_batch_id'     => $this->batch?->id,
                    'source'              => 'Kemendag',
                    'trade_flow'          => $sheetInfo['trade_flow'],
                    'trade_identity'      => $tradeIdentity,
                    'year'                => $columns['year'],
                    'month'               => $columns['month'],
                    'dimension'           => 'country',
                    'product'             => null,
                    'product_category'    => null,
                    'industry_segment'    => null,
                    'hs_code'             => $hsCode,
                    'hs_description'      => $hsDescription,

                    // Country Payload
                    'country_code'        => $country?->country_code,
                    'country_name'        => $countryName,
                    'country_id'          => $country?->id,

                    // Province Payload
                    'province_code'       => $province['code'] ?? null,
                    'province_name'       => $provinceName,
                    'province_id'         => $province['id'] ?? null,

                    // Port / Trade Point Payload
                    'port_code'           => $tradePoint['code'] ?? null,
                    'port_name'           => $portName,
                    'trade_point_id'      => $tradePoint['id'] ?? null,
                    'trade_point_type_id' => $tradePoint['trade_point_type_id'] ?? null,

                    // Trade Data
                    'trade_value'         => $tradeValue,
                    'currency'            => 'USD',
                    'trade_volume'        => $tradeVolume,
                    'volume_unit'         => 'KG',

                    // Audit
                    'release_date'        => $now,
                    'remarks'             => null,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];

                /*
                |--------------------------------------------------------------------------
                | Flush Buffer
                |--------------------------------------------------------------------------
                */
                if (count($this->buffer) >= $this->bufferSize) {
                    $this->saveRecords($this->buffer);
                    $this->buffer = [];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save Remaining Buffer
        |--------------------------------------------------------------------------
        */
        if (!empty($this->buffer)) {
            $this->saveRecords($this->buffer);
            $this->buffer = [];
        }

        Log::info('PROCESS ROWS FINISHED');
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
                ['trade_identity'],
                [
                    'import_batch_id',
                    'source',
                    'trade_flow',
                    'year',
                    'month',
                    'dimension',
                    'product',
                    'product_category',
                    'industry_segment',
                    'hs_code',
                    'hs_description',
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
                ]
            );

            $count = count($chunk);

            $this->summary['processed_rows'] = ($this->summary['processed_rows'] ?? 0) + $count;
            $this->summary['total_rows']     = ($this->summary['total_rows'] ?? 0) + $count;
            $this->summary['processed']      = ($this->summary['processed'] ?? 0) + $count;
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
            'total_rows'    => $this->summary['processed_rows'],
            'inserted_rows' => $this->summary['processed_rows'],
            'updated_rows'  => 0,
            'skipped_rows'  => $this->summary['skipped_rows'],
            'failed_rows'   => $this->summary['error_rows'],
            'status'        => 'completed',
            'remarks'       => sprintf(
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
    protected function failBatch(Throwable $e): void
    {
        if (!$this->batch) {
            return;
        }

        $this->batch->update([
            'status'  => 'failed',
            'remarks' => $e->getMessage(),
        ]);
    }

    /**
     * Convert Excel Number
     */
    protected function toNumber(mixed $value): float
    {
        if ($value === null) {
            return 0.0;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return 0.0;
        }

        $value = str_replace(',', '', $value);

        if (!is_numeric($value)) {
            return 0.0;
        }

        return (float) $value;
    }
}