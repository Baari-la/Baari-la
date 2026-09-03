<?php

declare(strict_types=1);

namespace App\Services\TradeIntelligence\Snapshot;

use App\Services\TradeIntelligence\Period\TradePeriodDatasetBuilder;
use App\Services\Trade\TradeReportingPeriod;

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
    | This class is the response boundary.
    |
    | TradePeriodDatasetBuilder remains responsible for:
    | - period resolution
    | - current dataset
    | - comparison dataset
    | - trend rows
    | - intelligence calculations
    |
    | SnapshotAssembler is responsible for:
    | - metadata
    | - validating the assembled result
    | - projecting the internal result into the
    |   canonical frontend snapshot contract
    | - fallback
    |
    | IMPORTANT:
    |
    | Large internal datasets such as:
    | - period_datasets
    | - current
    | - previous
    | - trend_rows
    |
    | must NOT be exposed to the Inertia response.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Assemble
    |--------------------------------------------------------------------------
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
            return $this->responsePayload(
                $snapshot
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Build Internal Period Data
        |--------------------------------------------------------------------------
        |
        | TradePeriodDatasetBuilder owns the complete
        | internal period composition.
        |
        | It may temporarily contain large raw datasets.
        |
        */

        $periodData =
            $this->periodDatasetBuilder->build(
                $snapshot,
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | 3. Build Canonical Metadata
        |--------------------------------------------------------------------------
        */

        $periodDatasets =
            $periodData['period_datasets']
            ?? [];

        $generatedAt =
            data_get(
                $snapshot,
                'meta.generated_at'
            );

        $meta =
            $this->metadataBuilder->build(
                $period,
                is_array($periodDatasets)
                    ? $periodDatasets
                    : [],
                $generatedAt
            );


        /*
        |--------------------------------------------------------------------------
        | 4. Internal Assembly
        |--------------------------------------------------------------------------
        |
        | Keep the complete result internally long enough
        | for validation.
        |
        */

        $assembled = [
            'meta' =>
                $meta,

            ...$periodData,
        ];


        /*
        |--------------------------------------------------------------------------
        | 5. Validate Complete Internal Snapshot
        |--------------------------------------------------------------------------
        |
        | Validation happens BEFORE response projection.
        |
        | This preserves the existing validation behavior.
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
            return $this->responsePayload(
                $assembled
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Fallback
        |--------------------------------------------------------------------------
        */

        $fallback =
            $this->fallback->apply(
                $assembled,
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | 7. Response Boundary
        |--------------------------------------------------------------------------
        |
        | Fallback is also projected before it reaches
        | the controller / Inertia response.
        |
        */

        return $this->responsePayload(
            $fallback
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Response Payload
    |--------------------------------------------------------------------------
    |
    | This is the ONLY public response projection.
    |
    | The frontend receives the same intelligence-oriented
    | structure as the legacy snapshot contract.
    |
    | Internal period datasets and raw trade rows are excluded.
    |
    */

    protected function responsePayload(
        mixed $snapshot
    ): array {

        if (
            !is_array(
                $snapshot
            )
        ) {
            return [];
        }


        /*
        |--------------------------------------------------------------------------
        | Canonical Frontend Contract
        |--------------------------------------------------------------------------
        |
        | Keep only fields required by the intelligence
        | response.
        |
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'meta' =>
                $snapshot['meta']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'overview' =>
                $snapshot['overview']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Subsector Intelligence
            |--------------------------------------------------------------------------
            */

            'by_subsector' =>
                $snapshot['by_subsector']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Flow Intelligence
            |--------------------------------------------------------------------------
            */

            'by_flow' =>
                $snapshot['by_flow']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Product Intelligence
            |--------------------------------------------------------------------------
            */

            'top_import_products' =>
                $snapshot['top_import_products']
                ?? [],

            'top_export_products' =>
                $snapshot['top_export_products']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Country Intelligence
            |--------------------------------------------------------------------------
            */

            'top_import_origins' =>
                $snapshot['top_import_origins']
                ?? [],

            'top_export_destinations' =>
                $snapshot['top_export_destinations']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Market Share
            |--------------------------------------------------------------------------
            */

            'import_market_share' =>
                $snapshot['import_market_share']
                ?? [],

            'export_market_share' =>
                $snapshot['export_market_share']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | Time Intelligence
            |--------------------------------------------------------------------------
            */

            'monthly_trend' =>
                $snapshot['monthly_trend']
                ?? [],

            'yearly_trend' =>
                $snapshot['yearly_trend']
                ?? [],


            /*
            |--------------------------------------------------------------------------
            | HS-8 Intelligence
            |--------------------------------------------------------------------------
            */

            'hs8_products' =>
                $snapshot['hs8_products']
                ?? [],
        ];
    }
}