<?php

namespace App\Services\TradeIntelligence\Snapshot;

use App\Services\TradeIntelligence\Support\TradeReportingPeriod;

class SnapshotFallback
{
    /*
    |--------------------------------------------------------------------------
    | Empty Snapshot
    |--------------------------------------------------------------------------
    |
    | Returned when no valid persisted snapshot is available.
    |
    */

    public function empty(
        TradeReportingPeriod $period
    ): array {
        return [

            'meta' => $this->metadata(
                $period
            ),

            'current' => [],

            'previous' => [],

            'overview' => $this->emptyOverview(),

            'by_subsector' => [],

            'by_flow' => [],

            'top_import_products' => [],

            'top_export_products' => [],

            'top_import_origins' => [],

            'top_export_destinations' => [],

            'import_market_share' => [],

            'export_market_share' => [],

            'monthly_trend' => [],

            'yearly_trend' => [],

            'hs8_products' => [],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Period Fallback
    |--------------------------------------------------------------------------
    |
    | A persisted snapshot exists but does not contain the
    | requested period dataset.
    |
    */

    public function forPeriod(
        array $snapshot,
        TradeReportingPeriod $period
    ): array {
        $fallback =
            $this->empty(
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | Preserve Snapshot Identity
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $snapshot['meta']
            )
            &&
            is_array(
                $snapshot['meta']
            )
        ) {
            $fallback['meta'] =
                array_merge(
                    $fallback['meta'],
                    $snapshot['meta']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback Metadata
        |--------------------------------------------------------------------------
        */

        $fallback['meta']['is_fallback'] =
            true;

        $fallback['meta']['fallback_reason'] =
            'REQUESTED_PERIOD_DATASET_NOT_AVAILABLE';

        $fallback['meta']['requested_snapshot_period_key'] =
            $period->snapshotKey();

        $fallback['meta']['requested_period'] =
            $period->currentPeriod();


        return $fallback;
    }


    /*
    |--------------------------------------------------------------------------
    | Metadata
    |--------------------------------------------------------------------------
    */

    protected function metadata(
        TradeReportingPeriod $period
    ): array {
        return [

            'sector' => null,

            'snapshot_key' => null,

            'snapshot_type' => null,

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

            'current_year' =>
                $period->publicThroughYear,

            'comparison_year' =>
                $period->comparisonYear,

            'through_month' =>
                $period->publicThroughMonth,

            'comparison_through_month' =>
                $period->comparisonThroughMonth,

            'buffer_period' =>
                $period->bufferPeriod(),

            'buffer_status' =>
                $period->status,

            'data_status' =>
                $this->dataStatus(
                    $period
                ),

            'generated_at' =>
                null,

            'record_count' =>
                0,

            'snapshot_period_key' =>
                $period->snapshotKey(),

            'hs_codes' => [],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Overview
    |--------------------------------------------------------------------------
    */

    protected function emptyOverview(): array
    {
        return [

            'import' => [

                'current' =>
                    0,

                'previous' =>
                    0,

                'growth_percent' =>
                    0,

                'physical_volume_kg' =>
                    0,

                'previous_physical_volume_kg' =>
                    0,

                'physical_volume_growth_percent' =>
                    0,

                'physical_volume_pcs' =>
                    null,

                'previous_physical_volume_pcs' =>
                    null,

                'physical_volume_coverage_percent' =>
                    0,

                'physical_volume_converted_rows' =>
                    0,

                'physical_volume_total_rows' =>
                    0,
            ],


            'export' => [

                'current' =>
                    0,

                'previous' =>
                    0,

                'growth_percent' =>
                    0,

                'physical_volume_kg' =>
                    0,

                'previous_physical_volume_kg' =>
                    0,

                'physical_volume_growth_percent' =>
                    0,

                'physical_volume_pcs' =>
                    null,

                'previous_physical_volume_pcs' =>
                    null,

                'physical_volume_coverage_percent' =>
                    0,

                'physical_volume_converted_rows' =>
                    0,

                'physical_volume_total_rows' =>
                    0,
            ],
        ];
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