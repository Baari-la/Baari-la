<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Certification → Market Edge Builder
 * ==========================================================================
 *
 * Example:
 *
 * GRS
 *      │
 *      ├── recognized_in ───► European Union
 *      ├── recognized_in ───► USA
 *      └── recognized_in ───► Japan
 *
 */

class CertificationMarketEdgeBuilder extends AbstractEdgeBuilder
{
    protected function relationship(): string
    {
        return 'recognized_in';
    }

    protected function weight(): float
    {
        return 0.99;
    }

    protected function confidence(): float
    {
        return 99;
    }

    /**
     * Expected source:
     *
     * [
     *      'id' => 'grs',
     *      'markets' => [
     *          'eu',
     *          'usa',
     *          'japan'
     *      ]
     * ]
     */
    public function build(mixed $source): iterable
    {
        $markets = $source['markets'] ?? [];

        if (empty($markets)) {
            return [];
        }

        $edges = [];

        foreach ($markets as $market) {

            $metadata = [];

            if (is_array($market)) {

                $metadata = [

                    'mandatory'
                        => $market['mandatory'] ?? false,

                    'buyer_requirement'
                        => $market['buyer_requirement'] ?? true,

                    'effective_date'
                        => $market['effective_date'] ?? null,

                    'notes'
                        => $market['notes'] ?? null,

                ];

                $market = $market['id'];
            }

            $edges[] = $this->edge(

                from: $source['id'],

                to: $market,

                metadata: $metadata

            );
        }

        return $edges;
    }
}