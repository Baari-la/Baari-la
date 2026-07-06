<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Contracts;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Intelligence Result
 * ==========================================================================
 *
 * Standard output object for all Intelligence Engines.
 *
 * Every Intelligence service should return this object
 * before being converted into array.
 *
 * Used by:
 *
 * - Trade Radar
 * - Executive Summary
 * - Early Warning
 * - Opportunity
 * - Risk Analysis
 * - Recommendation
 * - AI Executive Summary
 */
final class IntelligenceResult
{
    public function __construct(

        /**
         * Raw calculated value.
         */
        public readonly mixed $value = null,

        /**
         * Executive score (0–100).
         */
        public readonly float $score = 0.0,

        /**
         * Executive status.
         *
         * Example:
         * Excellent
         * Strong
         * Healthy
         * Watch
         * Critical
         */
        public readonly ?string $status = null,

        /**
         * Supporting information.
         */
        public readonly array $details = [],

        /**
         * Engine metadata.
         */
        public readonly array $metadata = [],

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Convert to Array
     * --------------------------------------------------------------------------
     */
    public function toArray(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Raw Value
            |--------------------------------------------------------------------------
            */

            'value' => $this->value,

            /*
            |--------------------------------------------------------------------------
            | Executive Score
            |--------------------------------------------------------------------------
            */

            'score' => round($this->score, 2),

            /*
            |--------------------------------------------------------------------------
            | Executive Status
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            /*
            |--------------------------------------------------------------------------
            | Supporting Information
            |--------------------------------------------------------------------------
            */

            'details' => $this->details,

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'metadata' => array_merge(

                [

                    'generated_at' => now()->toDateTimeString(),

                    'engine_version' => '1.0.0',

                ],

                $this->metadata

            ),

        ];
    }
}