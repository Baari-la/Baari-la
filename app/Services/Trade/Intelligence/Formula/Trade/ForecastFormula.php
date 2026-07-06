<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Formula\Trade;

use App\Services\Trade\Intelligence\Forecast\TradeForecastService;
use App\Services\Trade\Intelligence\Formula\Contracts\FormulaEngine;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Forecast Formula
 * ==========================================================================
 */
class ForecastFormula implements FormulaEngine
{
    public function __construct(
        protected TradeForecastService $forecast,
    ) {
    }

    public function calculate(array $filters = []): array
    {
        return [

            'confidence' => 75.0,

            'forecast' => [],

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}