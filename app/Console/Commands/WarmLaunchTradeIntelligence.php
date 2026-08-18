<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Trade\LaunchTradeIntelligenceService;
use Illuminate\Console\Command;

class WarmLaunchTradeIntelligence extends Command
{
    protected $signature =
        'digestex:trade-launch-snapshot';

    protected $description =
        'Build and cache Digestex Launch Trade Intelligence snapshot';

    public function handle(
        LaunchTradeIntelligenceService $service
    ): int {

        $this->info(
            'Building Launch Trade Intelligence snapshot...'
        );

        $started = microtime(true);

        $service->refresh();

        $elapsed =
            microtime(true)
            - $started;

        $this->info(
            'Snapshot generated.'
        );

        $this->line(
            'Execution time: '
            . number_format(
                $elapsed,
                3
            )
            . ' seconds'
        );

        $this->line(
            'Cache: '
            . (
                $service->isCached()
                    ? 'READY'
                    : 'NOT READY'
            )
        );

        return self::SUCCESS;
    }
}