<?php

declare(strict_types=1);

namespace App\Repositories\Trade;

use App\Models\TradeIntelligenceSnapshot;
use Illuminate\Support\Facades\DB;

class TradeIntelligenceSnapshotRepository
{
    public function latestValid(
        string $snapshotKey
    ): ?array {
        $snapshot = TradeIntelligenceSnapshot::query()
            ->where('snapshot_key', $snapshotKey)
            ->where('status', 'validated')
            ->orderByDesc('version')
            ->orderByDesc('validated_at')
            ->first();

        return $snapshot?->payload;
    }

public function deleteBySnapshotKey(
    string $snapshotKey
): int {
    return TradeIntelligenceSnapshot::query()
        ->where('snapshot_key', $snapshotKey)
        ->delete();
}


    public function saveValidated(
        string $snapshotKey,
        array $payload,
        array $meta = []
    ): TradeIntelligenceSnapshot {
        $latest = TradeIntelligenceSnapshot::query()
            ->where('snapshot_key', $snapshotKey)
            ->orderByDesc('version')
            ->first();

        $version = $latest
            ? ((int) $latest->version + 1)
            : 1;

        $json = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES |
            JSON_THROW_ON_ERROR
        );

        $attributes = [
            'snapshot_key' => $snapshotKey,
            'snapshot_type' => $meta['snapshot_type'] ?? 'trade',
            'sector' => $meta['sector'] ?? null,
            'period_key' => $meta['period_key'] ?? 'unknown',
            'version' => $version,
            'status' => 'validated',
            'payload' => $json,
            'checksum' => hash('sha256', $json),
            'generated_at' => $meta['generated_at'] ?? now(),
            'validated_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $id = DB::table('trade_intelligence_snapshots')
            ->insertGetId($attributes);

        $snapshot = new TradeIntelligenceSnapshot();

        $snapshot->setRawAttributes(
            [
                ...$attributes,
                'id' => $id,
            ],
            true
        );

        $snapshot->exists = true;

        return $snapshot;
    }

 

}