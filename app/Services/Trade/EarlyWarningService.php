<?php

namespace App\Services\Trade;

use App\Repositories\Trade\TradeStatisticsRepository;

class EarlyWarningService
{
    public function __construct(
        protected TradeStatisticsRepository $repository
    ) {
    }

    /**
     * Detect abnormal trade movement
     */
    public function detect(array $filters = []): array
    {
        return [

            'importSpike' => $this->repository->detectImportSpike(
                $filters
            ),

            'exportDrop' => $this->repository->detectExportDrop(
                $filters
            ),

            'newMarkets' => $this->repository->detectNewMarkets(
                $filters
            ),

            'lostMarkets' => $this->repository->detectLostMarkets(
                $filters
            ),

            'fastGrowingProducts' => $this->repository->detectFastGrowingProducts(
                $filters
            ),

        ];
    }
}