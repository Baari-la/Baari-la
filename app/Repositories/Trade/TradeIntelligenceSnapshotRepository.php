<?php

declare(strict_types=1);

namespace App\Repositories\Trade;

use App\Models\TradeIntelligenceSnapshot;

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

        return TradeIntelligenceSnapshot::create([
            'snapshot_key' => $snapshotKey,
            'snapshot_type' => $meta['snapshot_type'] ?? 'trade',
            'sector' => $meta['sector'] ?? null,
            'period_key' => $meta['period_key'] ?? 'unknown',
            'version' => $version,
            'status' => 'validated',
            'payload' => $payload,
            'checksum' => hash('sha256', $json),
            'generated_at' => $meta['generated_at'] ?? now(),
            'validated_at' => now(),
        ]);
    }
}