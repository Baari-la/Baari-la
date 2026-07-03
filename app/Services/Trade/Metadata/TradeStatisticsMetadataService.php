<?php

declare(strict_types=1);

namespace App\Services\Trade\Metadata;

use App\Repositories\Trade\TradeStatisticsRepository;
use App\Services\Support\CacheService;
use App\Repositories\Trade\Metadata\TradeMetadataRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Trade Statistics Metadata Service
 * ==========================================================================
 *
 * Centralized metadata provider for:
 *
 * - Home Dashboard
 * - Executive Report
 * - Country Intelligence
 * - HS Intelligence
 * - AI Executive Summary
 * - REST API
 * - Mobile Application
 *
 * Metadata is cached to eliminate expensive aggregate queries.
 */
class TradeStatisticsMetadataService
{
    /**
     * Cache Key
     */
    protected string $cacheKey = 'digestex.trade.metadata';

    /**
     * Cache Lifetime
     *
     * 24 Hours
     */
    protected int $cacheTtl = 60 * 60 * 24;

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

    /**
     * Refresh Metadata Cache
     */
    public function refresh(): array
    {
        $this->forget();

        return $this->get();
    }

    /**
     * Remove Cache
     */
    public function forget(): void
    {
        CacheService::forget($this->cacheKey);
    }

    /**
     * Warmup Cache
     *
     * Called after Trade Import completed.
     */
    public function warmup(): array
    {
        return $this->refresh();
    }

    /**
     * Cache Exists?
     */
    public function exists(): bool
    {
        return CacheService::has($this->cacheKey);
    }

    /**
     * Trade Data Last Updated
     *
     * Timestamp from trade_statistics table.
     */
    public function lastUpdated(): ?string
    {
        return $this->get()['last_updated'] ?? null;
    }

    /**
     * Cache Generated Time
     */
    public function generatedAt(): ?string
    {
        return $this->get()['generated_at'] ?? null;
    }

    /**
     * Metadata Statistics
     *
     * Used by Admin Dashboard.
     */
    public function statistics(): array
    {
        $metadata = $this->get();

        return [

            'cache_key'      => $this->cacheKey,
            'cache_exists'   => $this->exists(),
            'latest_year'    => $metadata['latest_year'] ?? null,
            'oldest_year'    => $metadata['oldest_year'] ?? null,
            'latest_month'   => $metadata['latest_month'] ?? null,
            'latest_period'  => $metadata['latest_period'] ?? null,
            'records'        => $metadata['total_records'] ?? 0,
            'countries'      => $metadata['total_countries'] ?? 0,
            'hs_codes'       => $metadata['total_hs_codes'] ?? 0,
            'export_value'   => $metadata['export_value'] ?? 0,
            'import_value'   => $metadata['import_value'] ?? 0,
            'last_updated'   => $metadata['last_updated'] ?? null,
            'generated_at'   => $metadata['generated_at'] ?? null,

        ];
    }
}