<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Core;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Pieces Conversion Service
 * ==========================================================================
 *
 * Converts trade volume (Kg) into estimated pieces.
 *
 * Responsible for:
 * - HS Code conversion multiplier
 * - Estimated Pieces calculation
 * - Conversion lookup
 *
 * Used by:
 * - Executive Report
 * - Home Dashboard
 * - Market Intelligence
 * - AI Summary
 * - Executive PDF
 *
 * Future:
 * - Load multipliers from Master Data (mst_hs_piece_conversions)
 */
class PiecesConversionService
{
    /**
     * Estimated pieces multiplier by HS Prefix.
     */
    protected array $multipliers = [

        '6109' => 5.5,
        '6110' => 2.5,

        '6203' => 1.8,
        '6204' => 1.8,

        '6111' => 8.0,
        '6209' => 8.0,

    ];

    /**
     * Default multiplier.
     */
    protected float $defaultMultiplier = 4.0;

    /**
     * Get multiplier by HS Code.
     */
    public function multiplier(string|int $hsCode): float
{
    $hsCode = trim((string) $hsCode);

    foreach ($this->multipliers as $prefix => $factor) {

        if (str_starts_with($hsCode, (string) $prefix)) {
            return $factor;
        }

    }

    return $this->defaultMultiplier;
}

    /**
 * Generic conversion.
 */
public function convert(
    string|int $hsCode,
    float $quantity
): float {

    return $quantity * $this->multiplier($hsCode);

}

    /**
 * Convert Kg to Estimated Pieces.
 */
public function toPieces(
    string|int $hsCode,
    float $kg
): float {

    return $this->convert(
        $hsCode,
        $kg
    );

}

    /**
 * Check whether multiplier exists.
 */
public function hasMultiplier(
    string|int $hsCode
): bool {

    $hsCode = trim((string) $hsCode);

    foreach (array_keys($this->multipliers) as $prefix) {

        if (str_starts_with(
            $hsCode,
            (string) $prefix
        )) {
            return true;
        }

    }

    return false;
}

    /**
     * Get all conversion multipliers.
     */
    public function all(): array
    {
        return $this->multipliers;
    }

    /**
     * Get default multiplier.
     */
    public function defaultMultiplier(): float
    {
        return $this->defaultMultiplier;
    }

    /**
 * --------------------------------------------------------------------------
 * Calculate Total Estimated Pieces
 * --------------------------------------------------------------------------
 *
 * Used by:
 * - Home Dashboard
 * - Executive Report
 */
public function totalPieces(iterable $rows): float
{
    $total = 0;

    foreach ($rows as $row) {

        if (($row->tipe_arus ?? '') !== 'ekspor') {
            continue;
        }

        $total += $this->toPieces(
            $row->hs_code,
            (float) $row->vol_2025
        );

    }

    return $total;
}
}