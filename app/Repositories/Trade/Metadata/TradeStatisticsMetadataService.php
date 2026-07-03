<?php

declare(strict_types=1);

namespace App\Services\Trade\Metadata;

use App\Repositories\Trade\Metadata\TradeMetadataRepository;
use App\Services\Support\CacheService;

class TradeStatisticsMetadataService
{
    protected string $cacheKey = 'digestex.trade.metadata';

    protected int $cacheTtl = 86400;

    public function __construct(
        protected TradeMetadataRepository $repository,
    ) {
    }

    /**
     * Get Metadata
     */
    public function get(): array
    {
        return CacheService::remember(
            $this->cacheKey,
            $this->cacheTtl,
            fn () => $this->build()
        );
    }

    /**
     * Build Metadata
     */
    public function build(): array
    {
        return array_merge(

            $this->repository->metadata(),

            [

                'generated_at' => now()->toDateTimeString(),

            ]

        );
    }
}