<?php

declare(strict_types=1);

namespace App\Repositories\Trade\Import;

use App\Models\TradeStatistic;
use Illuminate\Http\UploadedFile;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Trade Import Repository
 * ==========================================================================
 *
 * Repository dedicated to Trade Import.
 *
 * Responsible for:
 *
 * - Import Batch
 * - Bulk Insert
 * - Duplicate Detection
 * - Validation Support
 * - Import Statistics
 *
 * NOTE:
 * Repository only performs database operations.
 *
 * Business logic belongs to:
 *
 * - TradeImportService
 * - TradeValidationService
 * - TradeImportLogService
 */
class TradeImportRepository
{
    /**
     * --------------------------------------------------------------------------
     * Bulk Insert
     * --------------------------------------------------------------------------
     */
    public function insert(array $rows): bool
    {
        TradeStatistic::insert($rows);

        return true;
    }

    /**
     * --------------------------------------------------------------------------
     * Bulk Upsert
     * --------------------------------------------------------------------------
     */
    public function upsert(
        array $rows,
        array $uniqueBy,
        array $updateColumns
    ): int {

        TradeStatistic::upsert(
            $rows,
            $uniqueBy,
            $updateColumns
        );

        return count($rows);
    }

    /**
     * --------------------------------------------------------------------------
     * Delete Import Batch
     * --------------------------------------------------------------------------
     */
    public function deleteBatch(
        int $batchId
    ): int {

        return TradeStatistic::query()

            ->where('import_batch_id', $batchId)

            ->delete();

    }

    /**
     * --------------------------------------------------------------------------
     * Batch Statistics
     * --------------------------------------------------------------------------
     */
    public function batchStatistics(
        int $batchId
    ): array {

        return [

            'records' => TradeStatistic::query()
                ->where('import_batch_id', $batchId)
                ->count(),

            'total_value' => (float) TradeStatistic::query()
                ->where('import_batch_id', $batchId)
                ->sum('trade_value'),

            'total_volume' => (float) TradeStatistic::query()
                ->where('import_batch_id', $batchId)
                ->sum('trade_volume'),

        ];

    }

    /**
     * --------------------------------------------------------------------------
     * Check Duplicate
     * --------------------------------------------------------------------------
     */
    public function exists(array $attributes): bool
    {
        return TradeStatistic::query()

            ->where($attributes)

            ->exists();
    }

    /**
     * --------------------------------------------------------------------------
     * Total Imported Records
     * --------------------------------------------------------------------------
     */
    public function totalImported(
        int $batchId
    ): int {

        return TradeStatistic::query()

            ->where('import_batch_id', $batchId)

            ->count();

    }
}