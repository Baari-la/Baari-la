<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Trade\TechnicalTextileTradeIntelligenceService;
use App\Services\Trade\TradeReportingPeriodProvider;
use Illuminate\Console\Command;
use Throwable;

class WarmTechnicalTextileSectorSnapshot extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Command Signature
    |--------------------------------------------------------------------------
    */

    protected $signature =
        'digestex:technical-textile-sector-snapshot';


    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    */

    protected $description =
        'Build and cache the Digestex Technical / Industrial Textile Trade Intelligence snapshot.';


    /*
    |--------------------------------------------------------------------------
    | Handle
    |--------------------------------------------------------------------------
    */

    public function handle(
        TechnicalTextileTradeIntelligenceService $service,
        TradeReportingPeriodProvider $periodProvider
    ): int {
        $this->info(
            'Building Technical / Industrial Textile Trade Intelligence snapshot...'
        );

        $startedAt =
            microtime(true);

        try {

            /*
            |--------------------------------------------------------------------------
            | Build / Refresh Snapshot
            |--------------------------------------------------------------------------
            */

            $snapshot =
                $service->refresh();


            /*
            |--------------------------------------------------------------------------
            | Active Reporting Period
            |--------------------------------------------------------------------------
            */

            $period =
                $periodProvider->current();


            /*
            |--------------------------------------------------------------------------
            | Execution Time
            |--------------------------------------------------------------------------
            */

            $executionTime =
                round(
                    microtime(true) - $startedAt,
                    3
                );


            /*
            |--------------------------------------------------------------------------
            | Success Output
            |--------------------------------------------------------------------------
            */

            $this->info(
                'Snapshot generated successfully.'
            );

            $this->line(
                'Execution time: ' .
                $executionTime .
                ' seconds'
            );

            $this->line(
                'Cache: READY'
            );

            $this->line(
                'Sector: Technical / Industrial Textile'
            );

            $this->line(
                'Period: ' .
                $period->periodLabel()
            );

            $this->line(
                'Display: ' .
                $period->displayPeriodLabelEn()
            );

            $this->line(
                'Buffer: ' .
                (
                    $period->bufferPeriod()
                    ?? 'NONE'
                )
            );

            $this->line(
                'Records: ' .
                data_get(
                    $snapshot,
                    'meta.record_count',
                    0
                )
            );

            $this->line(
                'Snapshot Key: ' .
                data_get(
                    $snapshot,
                    'meta.snapshot_period_key',
                    'N/A'
                )
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Failure
            |--------------------------------------------------------------------------
            */

            $this->error(
                'Failed to generate Technical / Industrial Textile snapshot.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}