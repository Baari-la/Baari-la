<?php

namespace App\Services\TradeIntelligence\Snapshot;

use App\Services\TradeIntelligence\Historical\HistoricalYearlyDatasetBuilder;
use App\Services\Trade\TradeReportingPeriod;

/**
 * HistoricalSnapshotAssembler
 *
 * Responsible only for assembling the final snapshot structure
 * for historical yearly trade intelligence.
 *
 * Historical aggregation, normalization, country intelligence,
 * overview calculation, and validation remain delegated to
 * dedicated Historical components.
 */
class HistoricalSnapshotAssembler
{
    public function __construct(
        protected HistoricalYearlyDatasetBuilder $datasetBuilder,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Assemble Historical Snapshot
    |--------------------------------------------------------------------------
    |
    | Historical Yearly is an annual aggregate dataset.
    |
    | It does NOT calculate 12 months for every historical year.
    |
    | HistoricalYearlyDatasetBuilder is the single entry point
    | for historical yearly intelligence.
    |
    */

    public function assemble(
        TradeReportingPeriod $period
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Build Historical Dataset
        |--------------------------------------------------------------------------
        |
        | The dataset builder owns:
        |
        | - historical yearly query
        | - yearly trend
        | - country intelligence
        | - overview
        | - validation
        | - historical metadata
        |
        */

        $dataset =
            $this->datasetBuilder->build(
                period: $period,
                sector: 'garment',
                cacheKey: $period->snapshotKey(),
                snapshotType: 'historical_yearly',
            );


        /*
        |--------------------------------------------------------------------------
        | Snapshot Meta
        |--------------------------------------------------------------------------
        |
        | HistoricalYearlyDatasetBuilder already owns the historical
        | metadata contract.
        |
        | Preserve it rather than rebuilding it here.
        |
        */

        $meta =
            $dataset['meta']
            ?? $this->emptyMeta(
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | Final Historical Snapshot
        |--------------------------------------------------------------------------
        */

        return [

            'meta' =>
                $meta,


            /*
            |--------------------------------------------------------------------------
            | Historical Yearly Intelligence
            |--------------------------------------------------------------------------
            */

            'yearly_trend' =>
                $dataset['yearly_trend']
                ?? [],

            'major_export_destinations' =>
                $dataset['major_export_destinations']
                ?? [],

            'major_import_sources' =>
                $dataset['major_import_sources']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'overview' =>
                $dataset['overview']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Historical Country Intelligence
            |--------------------------------------------------------------------------
            */

            'top_import_origins' =>
                $dataset['top_import_origins']
                ?? [],

            'top_export_destinations' =>
                $dataset['top_export_destinations']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Historical Validation
            |--------------------------------------------------------------------------
            |
            | Validation remains available to backend consumers.
            |
            */

            'historical_validation' =>
                $dataset['historical_validation']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Legacy / Detail Sections
            |--------------------------------------------------------------------------
            |
            | Historical yearly aggregation does not currently
            | populate these sections.
            |
            | Keep these keys for response compatibility.
            |
            */

            'dataset' =>
                $dataset['dataset']
                ?? [],

            'current' =>
                $dataset['current']
                ?? [],

            'previous' =>
                $dataset['previous']
                ?? [],

            'by_subsector' =>
                $dataset['by_subsector']
                ?? [],

            'by_flow' =>
                $dataset['by_flow']
                ?? [],

            'top_import_products' =>
                $dataset['top_import_products']
                ?? [],

            'top_export_products' =>
                $dataset['top_export_products']
                ?? [],

            'import_market_share' =>
                $dataset['import_market_share']
                ?? [],

            'export_market_share' =>
                $dataset['export_market_share']
                ?? [],

            'monthly_trend' =>
                $dataset['monthly_trend']
                ?? [],

            'hs8_products' =>
                $dataset['hs8_products']
                ?? [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Meta
    |--------------------------------------------------------------------------
    |
    | Defensive fallback only.
    |
    | Normally HistoricalYearlyDatasetBuilder supplies the complete
    | metadata structure.
    |
    */

    protected function emptyMeta(
        TradeReportingPeriod $period
    ): array {

        return [

            'sector' =>
                'garment',

            'snapshot_key' =>
                $period->snapshotKey(),

            'snapshot_type' =>
                'historical_yearly',

            'dataset_format' =>
                'historical_yearly_v1',


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
                'available',


            /*
            |--------------------------------------------------------------------------
            | Generation
            |--------------------------------------------------------------------------
            */

            'generated_at' =>
                null,


            /*
            |--------------------------------------------------------------------------
            | Dataset Contract
            |--------------------------------------------------------------------------
            */

            'record_count' =>
                0,

            'snapshot_period_key' =>
                $period->snapshotKey(),


            /*
            |--------------------------------------------------------------------------
            | Canonical HS Codes
            |--------------------------------------------------------------------------
            */

            'hs_codes' =>
                [],
        ];
    }
}