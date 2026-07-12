<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

class BusinessNeedService
{
    /**
     * ==========================================================================
     * DIGESTEX CORE
     * ==========================================================================
     * Business Need Service
     * ==========================================================================
     *
     * Converts a Business Ecosystem into Business Needs.
     *
     * Used by:
     *
     * • Smart Business Matching
     * • Build My Supply Chain™
     * • Buyer Discovery
     * • Executive AI
     *
     * This service does NOT search companies.
     * It prepares structured business requirements.
     *
     * Version : 1.0
     */

    /**
     * Build business needs from ecosystem.
     */
    public function build(array $ecosystem): array
    {
        return [

            'role' => $ecosystem['role'] ?? null,

            'company_type' => $ecosystem['name'] ?? null,

            'high_priority' => $this->filterByPriority(
                $ecosystem,
                'High'
            ),

            'medium_priority' => $this->filterByPriority(
                $ecosystem,
                'Medium'
            ),

            'standard_priority' => $this->filterByPriority(
                $ecosystem,
                'Standard'
            ),

            'all' => $ecosystem['needs'] ?? [],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Filter Needs by Priority
     * --------------------------------------------------------------------------
     */
    protected function filterByPriority(
        array $ecosystem,
        string $priority
    ): array {

        return collect(

            $ecosystem['needs'] ?? []

        )

        ->where(
            'priority',
            $priority
        )

        ->values()

        ->all();

    }

    /**
     * --------------------------------------------------------------------------
     * Flatten all business categories
     * --------------------------------------------------------------------------
     */
    public function categories(
        array $ecosystem
    ): array {

        return collect(

            $ecosystem['needs'] ?? []

        )

        ->pluck('key')

        ->values()

        ->all();

    }

    /**
     * --------------------------------------------------------------------------
     * Get One Business Need
     * --------------------------------------------------------------------------
     */
    public function find(
        array $ecosystem,
        string $key
    ): ?array {

        return collect(

            $ecosystem['needs'] ?? []

        )

        ->firstWhere(
            'key',
            $key
        );

    }

    /**
     * --------------------------------------------------------------------------
     * Build Empty Matching Payload
     * --------------------------------------------------------------------------
     */
    public function matchingPayload(
        array $ecosystem
    ): array {

        return collect(

            $ecosystem['needs'] ?? []

        )

        ->map(function ($need) {

            return [

                'category' => $need['key'],

                'title' => $need['title'],

                'priority' => $need['priority'],

                'description' => $need['description'],

                /*
                 * Filled later by CompanyMatchingService
                 */
                'companies' => [],

            ];

        })

        ->values()

        ->all();

    }
}