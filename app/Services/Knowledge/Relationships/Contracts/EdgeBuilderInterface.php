<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships\Contracts;

use App\Services\Knowledge\KnowledgeEdge;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Edge Builder Interface
 * ==========================================================================
 *
 * Standard contract for all Knowledge Edge Builders.
 *
 * Every Edge Builder must implement this interface.
 *
 * Examples:
 *
 * BusinessRoleTechnologyEdgeBuilder
 * TechnologyMachineryEdgeBuilder
 * ProductTechnologyEdgeBuilder
 * TechnologyCertificationEdgeBuilder
 * CertificationMarketEdgeBuilder
 * MarketBuyerEdgeBuilder
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

interface EdgeBuilderInterface
{
    /**
     * Build Knowledge Edge.
     *
     * @param mixed $source
     *
     * @return KnowledgeEdge[]
     */
    public function build(mixed $source): array;
}