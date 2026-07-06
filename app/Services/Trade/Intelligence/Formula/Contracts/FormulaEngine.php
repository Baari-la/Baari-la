<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Formula\Contracts;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Formula Engine Contract
 * ==========================================================================
 *
 * Standard contract for all business formulas.
 *
 * Formula layer ONLY calculates business metrics.
 *
 * It NEVER:
 *
 * - Calculates score
 * - Determines status
 * - Generates AI narrative
 */
interface FormulaEngine
{
    /**
     * Calculate Business Metric.
     */
    public function calculate(array $filters = []): array;
}