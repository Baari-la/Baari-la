<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Trade\TradeReportingPeriodProvider;
use App\Services\Trade\ThreadTradeIntelligenceService;
use Illuminate\Console\Command;
use Throwable;

class WarmThreadSectorSnapshot extends Command
{
    protected $signature =
        'digestex:thread-sector-snapshot';

    protected $description =
        'Build and cache the Digestex Thread Trade Intelligence snapshot.';

    public function handle(
        ThreadTradeIntelligenceService $service,
        TradeReportingPeriodProvider $periodProvider
    ): int {
        $this->info(
            'Building Thread Trade Intelligence snapshot...'
        );

        $startedAt = microtime(true);

        try {
            $snapshot =
                $service->refresh();

            $period =
                $periodProvider->current();

            $executionTime =
                round(
                    microtime(true) - $startedAt,
                    3
                );

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
                'Sector: Thread'
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
                ($period->bufferPeriod() ?? 'NONE')
            );

            $this->line(
                'Records: ' .
                data_get(
                    $snapshot,
                    'meta.record_count',
                    0
                )
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Failed to generate Thread snapshot.'
            );

            $this->error(
                $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}