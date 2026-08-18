<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Home\HomeExecutiveSnapshotService;
use Illuminate\Console\Command;

class WarmHomeExecutiveSnapshot extends Command
{
    protected $signature = 'digestex:home-executive-snapshot';

    protected $description =
        'Build and cache the Digestex Home Executive Snapshot';

    public function handle(
        HomeExecutiveSnapshotService $service
    ): int {

        $this->info(
            'Building Home Executive Snapshot...'
        );

        $started = microtime(true);

        $snapshot = $service->refresh();

        $elapsed =
            microtime(true)
            - $started;

        $this->info(
            'Snapshot generated successfully.'
        );

        $this->line(
            'Generated at: '
            . ($snapshot['generated_at'] ?? '-')
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
            'Cache status: '
            . (
                $service->isCached()
                    ? 'HIT'
                    : 'MISS'
            )
        );

        return self::SUCCESS;
    }
}