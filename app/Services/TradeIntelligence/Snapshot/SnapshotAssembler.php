<?php

namespace App\Services\TradeIntelligence\Snapshot;

use App\Services\TradeIntelligence\Period\TradePeriodDatasetBuilder;
use App\Services\TradeIntelligence\Period\TradeReportingPeriod;

class SnapshotAssembler
{
    public function __construct(
        protected TradePeriodDatasetBuilder $periodDatasetBuilder,
        protected SnapshotMetadataBuilder $metadataBuilder,
        protected SnapshotValidator $validator,
        protected SnapshotFallback $fallback,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Assemble Snapshot
    |--------------------------------------------------------------------------
    |
    | This class is intentionally an orchestration layer only.
    |
    | Responsibilities delegated to:
    |
    | TradePeriodDatasetBuilder
    |     -> builds period datasets
    |
    | SnapshotMetadataBuilder
    |     -> owns snapshot metadata contract
    |
    | SnapshotValidator
    |     -> validates snapshot structure
    |
    | SnapshotFallback
    |     -> handles unavailable / invalid snapshot fallback
    |
    */


    public function assemble(
        array $snapshot,
        TradeReportingPeriod $period
    ): array {
        /*
        |--------------------------------------------------------------------------
        | 1. Validate Existing Snapshot
        |--------------------------------------------------------------------------
        */

        if (
            $this->validator->isValid(
                $snapshot
            )
            &&
            $this->validator->matchesPeriod(
                $snapshot,
                $period
            )
        ) {
            return $snapshot;
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Build Period Datasets
        |--------------------------------------------------------------------------
        |
        | Descriptor creation, current/previous selection and trendRows
        | are owned entirely by TradePeriodDatasetBuilder.
        |
        */

        $periodData =
            $this->periodDatasetBuilder->build(
                $snapshot,
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | 3. Extract Period Datasets
        |--------------------------------------------------------------------------
        */

        $periodDatasets =
            $periodData['period_datasets']
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | 4. Build Canonical Metadata
        |--------------------------------------------------------------------------
        |
        | Metadata is created only once and only here through
        | SnapshotMetadataBuilder.
        |
        */

        $generatedAt =
            data_get(
                $snapshot,
                'meta.generated_at'
            );

        $meta =
            $this->metadataBuilder->build(
                $period,
                $periodDatasets,
                $generatedAt
            );


        /*
        |--------------------------------------------------------------------------
        | 5. Assemble
        |--------------------------------------------------------------------------
        */

        $assembled = [
            'meta' =>
                $meta,

            ...$periodData,
        ];


        /*
        |--------------------------------------------------------------------------
        | 6. Validate Newly Assembled Snapshot
        |--------------------------------------------------------------------------
        |
        | The assembler does not implement validation rules.
        |
        */

        if (
            $this->validator->isValid(
                $assembled
            )
            &&
            $this->validator->matchesPeriod(
                $assembled,
                $period
            )
        ) {
            return $assembled;
        }


        /*
        |--------------------------------------------------------------------------
        | 7. Fallback
        |--------------------------------------------------------------------------
        |
        | Fallback logic is delegated completely to SnapshotFallback.
        |
        */

        return $this->fallback->apply(
            $assembled,
            $period
        );
    }
}