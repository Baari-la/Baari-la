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

    /*
    |--------------------------------------------------------------------------
    | Get Latest Valid Snapshot
    |--------------------------------------------------------------------------
    |
    | General snapshot read path.
    |
    | IMPORTANT:
    | Never build a heavy snapshot during a normal user request.
    |
    */

    public function get(
        string $snapshotKey,
        ?Closure $fallbackBuilder = null
    ): ?array {
        $snapshot =
            $this->repository->latestValid(
                $snapshotKey
            );

        if (is_array($snapshot)) {
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


    /*
    |--------------------------------------------------------------------------
    | Get Shared Period Snapshot
    |--------------------------------------------------------------------------
    |
    | Historical / selected periods use their own persistent snapshot key.
    |
    | Example:
    |
    | digestex.trade.sector.garment.period.2024-12-vs-2023-12
    |
    | If another user requests the same period, the existing validated
    | snapshot is reused instead of rebuilding the database intelligence.
    |
    */

    public function getForPeriod(
        string $snapshotKey
    ): ?array {
        return $this->repository->latestValid(
            $snapshotKey
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Save Validated Snapshot
    |--------------------------------------------------------------------------
    */

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

        return $payload;
    }


    /*
    |--------------------------------------------------------------------------
    | Forget Cache
    |--------------------------------------------------------------------------
    */

    public function forget(
    string $snapshotKey
): void {
    CacheService::forget(
        $snapshotKey
    );
}


/*
|--------------------------------------------------------------------------
| Delete Persistent Snapshot
|--------------------------------------------------------------------------
*/

public function deleteBySnapshotKey(
    string $snapshotKey
): int {
    return $this->repository->deleteBySnapshotKey(
        $snapshotKey
    );
}


/*
|--------------------------------------------------------------------------
| Check Valid Snapshot
|--------------------------------------------------------------------------
*/

public function hasValidSnapshot(
    string $snapshotKey
): bool {
    return $this->repository
        ->latestValid($snapshotKey) !== null;
}
}