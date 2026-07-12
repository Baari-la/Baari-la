<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Technology → Machinery Edge Builder
 * ==========================================================================
 *
 * Creates relationships between Technologies and Machinery.
 *
 * Example:
 *
 * Digital Printing
 *      │
 *      ├── implemented_by ───► Epson Monna Lisa
 *      ├── implemented_by ───► Kornit Atlas
 *      └── implemented_by ───► MS JP7
 *
 * Ring Spinning
 *      │
 *      ├── implemented_by ───► Rieter G38
 *      ├── implemented_by ───► Toyota RX300
 *      └── implemented_by ───► Zinser 72XL
 *
 */

class TechnologyMachineryEdgeBuilder extends AbstractEdgeBuilder
{
    /**
     * Relationship name.
     */
    protected function relationship(): string
    {
        return 'implemented_by';
    }

    /**
     * Default relationship weight.
     */
    protected function weight(): float
    {
        return 0.98;
    }

    /**
     * Confidence.
     */
    protected function confidence(): float
    {
        return 100;
    }

    /**
     * Build Technology → Machinery relationships.
     *
     * Expected source:
     *
     * [
     *     'id' => 'digital_printing',
     *     'machineries' => [
     *         'epson_monna_lisa',
     *         'kornit_atlas',
     *         'ms_jp7'
     *     ]
     * ]
     *
     * @return iterable<\App\Services\Knowledge\KnowledgeEdge>
     */
    public function build(mixed $source): iterable
    {
        $machineries = $source['machineries'] ?? [];

        if (empty($machineries)) {
            return [];
        }

        return $this->edges(

            from: $source['id'],

            targets: $machineries,

            resolver: fn (string $machine) => $machine

        );
    }
}