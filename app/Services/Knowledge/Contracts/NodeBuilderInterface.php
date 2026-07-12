<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Contracts;

use App\Services\Knowledge\KnowledgeNode;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Node Builder Interface
 * ==========================================================================
 *
 * Standard contract for all Knowledge Node Builders.
 *
 * Every Node Builder must implement this interface.
 *
 * Examples:
 *
 * CompanyNodeBuilder
 * CountryNodeBuilder
 * RegionNodeBuilder
 * ProductNodeBuilder
 * TechnologyNodeBuilder
 * MachineryNodeBuilder
 * CertificationNodeBuilder
 * SustainabilityNodeBuilder
 * MarketNodeBuilder
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

interface NodeBuilderInterface
{
    /**
     * Build Knowledge Node.
     *
     * @param mixed $source
     */
    public function build(mixed $source): KnowledgeNode;
}