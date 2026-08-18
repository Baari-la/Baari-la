<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Repositories\Trade\TradeIntelligenceSnapshotRepository;
use App\Services\Support\CacheService;
use Closure;

class TradeIntelligenceSnapshotService
{
    protected int $cacheTtl = 1800;

    public function __construct(
        protected TradeIntelligenceSnapshotRepository $repository,
    ) {
    }

    /**
     * Read path:
     *
     * 1. Cache
     * 2. Last validated snapshot
     * 3. Builder only when explicitly requested
     */
    public function get(
        string $snapshotKey,
        ?Closure $fallbackBuilder = null
    ): ?array {
        $cached = CacheService::get(
            $snapshotKey
        );

        if (is_array($cached)) {
            return $cached;
        }

        $snapshot =
            $this->repository->latestValid(
                $snapshotKey
            );

        if (is_array($snapshot)) {
            CacheService::put(
                $snapshotKey,
                $snapshot,
                $this->cacheTtl
            );

            return $snapshot;
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Do NOT build a heavy snapshot during user request.
        |--------------------------------------------------------------------------
        */

        return null;
    }

    public function save(
        string $snapshotKey,
        array $payload,
        array $meta = []
    ): array {
        $this->repository->saveValidated(
            $snapshotKey,
            $payload,
            $meta
        );

        CacheService::put(
            $snapshotKey,
            $payload,
            $this->cacheTtl
        );

        return $payload;
    }

    public function forget(
        string $snapshotKey
    ): void {
        CacheService::forget(
            $snapshotKey
        );
    }

    public function hasValidSnapshot(
        string $snapshotKey
    ): bool {
        return $this->repository
            ->latestValid($snapshotKey) !== null;
    }
}