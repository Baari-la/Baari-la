<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Market → Buyer Edge Builder
 * ==========================================================================
 *
 * Example:
 *
 * European Union
 *      │
 *      ├── served_by ─────► Adidas
 *      ├── served_by ─────► H&M
 *      ├── served_by ─────► Zara
 *      └── served_by ─────► Decathlon
 *
 */

class MarketBuyerEdgeBuilder extends AbstractEdgeBuilder
{
    protected function relationship(): string
    {
        return 'served_by';
    }

    protected function weight(): float
    {
        return 0.92;
    }

    protected function confidence(): float
    {
        return 96;
    }

    /**
     * Expected source:
     *
     * [
     *      'id' => 'eu',
     *      'buyers' => [
     *          'adidas',
     *          'hm',
     *          'zara',
     *          'decathlon'
     *      ]
     * ]
     */
    public function build(mixed $source): iterable
    {
        $buyers = $source['buyers'] ?? [];

        if (empty($buyers)) {
            return [];
        }

        $edges = [];

        foreach ($buyers as $buyer) {

            $metadata = [];

            if (is_array($buyer)) {

                $metadata = [

                    'buyer_type'
                        => $buyer['buyer_type'] ?? null,

                    'priority'
                        => $buyer['priority'] ?? 0,

                    'preferred_supplier'
                        => $buyer['preferred_supplier'] ?? false,

                    'annual_volume'
                        => $buyer['annual_volume'] ?? null,

                    'notes'
                        => $buyer['notes'] ?? null,

                ];

                $buyer = $buyer['id'];
            }

            $edges[] = $this->edge(

                from: $source['id'],

                to: $buyer,

                metadata: $metadata

            );
        }

        return $edges;
    }
}