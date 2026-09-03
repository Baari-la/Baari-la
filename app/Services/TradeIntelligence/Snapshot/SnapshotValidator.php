<?php

namespace App\Services\TradeIntelligence\Snapshot;

use App\Services\Trade\TradeReportingPeriod;

class SnapshotValidator
{
    /*
    |--------------------------------------------------------------------------
    | Validate Snapshot
    |--------------------------------------------------------------------------
    |
    | Validate the persisted snapshot before it is assembled
    | for a requested reporting period.
    |
    */

    public function isValid(
        array $snapshot
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | Period Dataset Snapshot
        |--------------------------------------------------------------------------
        |
        | New snapshot format.
        |
        */

        if (
            $this->hasPeriodDatasets(
                $snapshot
            )
        ) {
            return $this->hasValidPeriodDatasets(
                $snapshot
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Legacy Snapshot Structure
        |--------------------------------------------------------------------------
        |
        | Keep backward compatibility with snapshots generated
        | before period_datasets was introduced.
        |
        */

        if (
            !isset(
                $snapshot['meta']
            )
            ||
            !isset(
                $snapshot['overview']
            )
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Import / Export Structure
        |--------------------------------------------------------------------------
        */

        $import =
            $snapshot['overview']['import']
            ?? null;

        $export =
            $snapshot['overview']['export']
            ?? null;

        if (
            !is_array($import)
            ||
            !is_array($export)
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Trade Value Structure
        |--------------------------------------------------------------------------
        |
        | Zero is valid.
        |
        | What matters here is that the current value keys
        | actually exist.
        |
        */

        $hasTradeData =
            array_key_exists(
                'current',
                $import
            )
            &&
            array_key_exists(
                'current',
                $export
            );

        if (!$hasTradeData) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Record Count
        |--------------------------------------------------------------------------
        */

        $recordCount =
            (int) (
                $snapshot['meta']['record_count']
                ?? 0
            );

        if (
            $recordCount <= 0
        ) {
            return false;
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Period Dataset Snapshot
    |--------------------------------------------------------------------------
    */

    protected function hasValidPeriodDatasets(
        array $snapshot
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | Metadata
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
                $snapshot['meta']
            )
            ||
            !is_array(
                $snapshot['meta']
            )
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Period Datasets
        |--------------------------------------------------------------------------
        */

        $periodDatasets =
            $snapshot['period_datasets']
            ?? null;

        if (
            !is_array(
                $periodDatasets
            )
            ||
            empty(
                $periodDatasets
            )
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Record Count
        |--------------------------------------------------------------------------
        */

        $recordCount =
            (int) (
                $snapshot['meta']['record_count']
                ?? 0
            );

        if (
            $recordCount <= 0
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Dataset Contents
        |--------------------------------------------------------------------------
        |
        | At least one persisted dataset must contain data.
        |
        */

        $hasData =
            false;

        foreach (
            $periodDatasets as $dataset
        ) {
            if (
                is_array($dataset)
                &&
                !empty($dataset)
            ) {
                $hasData = true;

                break;
            }
        }

        return $hasData;
    }


    /*
    |--------------------------------------------------------------------------
    | Snapshot Period Validation
    |--------------------------------------------------------------------------
    */

    public function matchesPeriod(
        array $snapshot,
        TradeReportingPeriod $period
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | New Period Dataset Format
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasPeriodDatasets(
                $snapshot
            )
        ) {
            return $this->matchesPeriodDatasets(
                $snapshot,
                $period
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Legacy Snapshot Format
        |--------------------------------------------------------------------------
        */

        $snapshotPeriodKey =
            data_get(
                $snapshot,
                'meta.snapshot_period_key'
            );

        if (
            $snapshotPeriodKey ===
            $period->snapshotKey()
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | Backward Compatibility
        |--------------------------------------------------------------------------
        */

        $currentPeriod =
            data_get(
                $snapshot,
                'meta.current_period'
            );

        $comparisonPeriod =
            data_get(
                $snapshot,
                'meta.comparison_period'
            );

        return
            $currentPeriod ===
                $period->currentPeriod()
            &&
            $comparisonPeriod ===
                $period->comparisonPeriod();
    }


    /*
    |--------------------------------------------------------------------------
    | Match Period Datasets
    |--------------------------------------------------------------------------
    |
    | The validator only checks that the required descriptors
    | exist.
    |
    | It does NOT build or resolve descriptors itself.
    |
    */

    protected function matchesPeriodDatasets(
        array $snapshot,
        TradeReportingPeriod $period
    ): bool {
        $periodDatasets =
            $snapshot['period_datasets']
            ?? [];

        $descriptors =
            $this->requiredDescriptors(
                $period
            );

        foreach (
            $descriptors as $descriptor
        ) {
            if (
                !array_key_exists(
                    $descriptor,
                    $periodDatasets
                )
            ) {
                return false;
            }
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Required Descriptors
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Descriptor construction remains in the Period layer.
    |
    | SnapshotValidator should not duplicate the descriptor
    | generation rules.
    |
    | Therefore this method reads persisted descriptor metadata
    | when available.
    |
    */

    protected function requiredDescriptors(
        TradeReportingPeriod $period
    ): array {
        $current =
            $this->descriptor(
                $period->publicThroughYear,
                $period->publicThroughMonth,
                $period->mode,
            );

        $comparison =
            $this->descriptor(
                $period->comparisonYear,
                $period->comparisonThroughMonth,
                $period->mode,
            );

        return array_values(
            array_unique(
                [
                    $current,
                    $comparison,
                ]
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Descriptor
    |--------------------------------------------------------------------------
    |
    | This mirrors the persisted period-dataset identity contract.
    |
    */

    protected function descriptor(
        int $year,
        int $throughMonth,
        string $mode
    ): string {
        $startMonth =
            $mode === 'monthly'
                ? $throughMonth
                : 1;

        $endMonth =
            $mode === 'full_year'
                ? 12
                : $throughMonth;

        return sprintf(
            '%04d-%02d..%02d:%s',
            $year,
            $startMonth,
            $endMonth,
            $mode,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Has Period Datasets
    |--------------------------------------------------------------------------
    */

    protected function hasPeriodDatasets(
        array $snapshot
    ): bool {
        return
            is_array(
                $snapshot['period_datasets']
                ?? null
            )
            &&
            !empty(
                $snapshot['period_datasets']
            );
    }
}