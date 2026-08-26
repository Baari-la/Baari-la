<?php

namespace App\Services\TradeIntelligence\Snapshot;

use App\Services\TradeIntelligence\Period\TradeReportingPeriod;
use Carbon\CarbonInterface;

class SnapshotMetadataBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Snapshot Contract
    |--------------------------------------------------------------------------
    |
    | This class is the SINGLE SOURCE OF TRUTH for snapshot metadata.
    |
    | Other snapshot components must NOT redefine:
    |
    | - sector
    | - snapshot_key
    | - snapshot_type
    | - dataset_format
    | - record_count
    | - period_dataset_descriptors
    |
    */

    public function __construct(
        protected string $sector,
        protected string $snapshotKey,
        protected string $snapshotType,
        protected string $datasetFormat = 'period_datasets_v1',
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Build Metadata
    |--------------------------------------------------------------------------
    */

    public function build(
        TradeReportingPeriod $period,
        array $periodDatasets,
        ?CarbonInterface $generatedAt = null,
    ): array {
        return [

            /*
            |--------------------------------------------------------------------------
            | Snapshot Identity
            |--------------------------------------------------------------------------
            */

            'sector' =>
                $this->sector,

            'snapshot_key' =>
                $this->snapshotKey,

            'snapshot_type' =>
                $this->snapshotType,

            'dataset_format' =>
                $this->datasetFormat,


            /*
            |--------------------------------------------------------------------------
            | Period
            |--------------------------------------------------------------------------
            */

            'period' =>
                $period->periodLabel(),

            'period_label_en' =>
                $period->periodLabel(),

            'period_label_id' =>
                $period->periodLabel(),

            'display_period_label_en' =>
                $period->displayPeriodLabelEn(),

            'display_period_label_id' =>
                $period->displayPeriodLabelId(),

            'comparison_period_label_en' =>
                $period->comparisonPeriodLabelEn(),

            'comparison_period_label_id' =>
                $period->comparisonPeriodLabelId(),

            'current_period' =>
                $period->currentPeriod(),

            'comparison_period' =>
                $period->comparisonPeriod(),


            /*
            |--------------------------------------------------------------------------
            | Public Period
            |--------------------------------------------------------------------------
            */

            'current_year' =>
                $period->publicThroughYear,

            'comparison_year' =>
                $period->comparisonYear,

            'through_month' =>
                $period->publicThroughMonth,

            'comparison_through_month' =>
                $period->comparisonThroughMonth,


            /*
            |--------------------------------------------------------------------------
            | Period Status
            |--------------------------------------------------------------------------
            */

            'mode' =>
                $period->mode,

            'buffer_period' =>
                $period->bufferPeriod(),

            'buffer_status' =>
                $period->status,

            'data_status' =>
                $this->dataStatus($period),


            /*
            |--------------------------------------------------------------------------
            | Generation
            |--------------------------------------------------------------------------
            */

            'generated_at' =>
                $generatedAt
                    ?? now(),


            /*
            |--------------------------------------------------------------------------
            | Dataset Contract
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | record_count and descriptors are derived directly from
            | the actual periodDatasets passed to this builder.
            |
            | They are therefore never independently calculated by
            | SnapshotAssembler, Validator, or Fallback.
            |
            */

            'record_count' =>
                $this->recordCount(
                    $periodDatasets
                ),

            'snapshot_period_key' =>
                $period->snapshotKey(),

            'period_dataset_descriptors' =>
                $this->periodDatasetDescriptors(
                    $periodDatasets
                ),


            /*
            |--------------------------------------------------------------------------
            | Canonical HS Codes
            |--------------------------------------------------------------------------
            */

            'hs_codes' =>
                [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Build Empty Metadata
    |--------------------------------------------------------------------------
    |
    | Used by SnapshotFallback.
    |
    | Empty metadata follows exactly the same metadata contract
    | as a normal snapshot.
    |
    */

    public function buildEmpty(
        TradeReportingPeriod $period
    ): array {
        return $this->build(
            $period,
            [],
            null
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Record Count
    |--------------------------------------------------------------------------
    */

    public function recordCount(
        array $periodDatasets
    ): int {
        $count = 0;

        foreach ($periodDatasets as $dataset) {

            if (!is_array($dataset)) {
                continue;
            }

            $count += count($dataset);
        }

        return $count;
    }


    /*
    |--------------------------------------------------------------------------
    | Period Dataset Descriptors
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | The builder does NOT construct descriptors.
    |
    | TradePeriodDatasetBuilder owns descriptor creation.
    |
    | This method only exposes the descriptors actually present
    | in the dataset collection.
    |
    */

    public function periodDatasetDescriptors(
        array $periodDatasets
    ): array {
        return array_values(
            array_keys($periodDatasets)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Dataset Format
    |--------------------------------------------------------------------------
    */

    public function datasetFormat(): string
    {
        return $this->datasetFormat;
    }


    /*
    |--------------------------------------------------------------------------
    | Snapshot Identity
    |--------------------------------------------------------------------------
    */

    public function sector(): string
    {
        return $this->sector;
    }

    public function snapshotKey(): string
    {
        return $this->snapshotKey;
    }

    public function snapshotType(): string
    {
        return $this->snapshotType;
    }


    /*
    |--------------------------------------------------------------------------
    | Data Status
    |--------------------------------------------------------------------------
    */

    protected function dataStatus(
        TradeReportingPeriod $period
    ): string {
        return match (
            $period->status
        ) {
            'buffer_promoted' =>
                'awaiting_latest_data',

            default =>
                'available',
        };
    }
}