<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

use App\Models\Company;
use App\Services\Knowledge\Relationships\BusinessRoleTechnologyEdgeBuilder;
use App\Services\Knowledge\Relationships\CertificationMarketEdgeBuilder;
use App\Services\Knowledge\Relationships\MarketBuyerEdgeBuilder;
use App\Services\Knowledge\Relationships\ProductTechnologyEdgeBuilder;
use App\Services\Knowledge\Relationships\TechnologyCertificationEdgeBuilder;
use App\Services\Knowledge\Relationships\TechnologyMachineryEdgeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Graph Relationship Builder
 * ==========================================================================
 *
 * Responsible for constructing all Knowledge Graph relationships.
 *
 * Responsibilities
 * ----------------
 * • Build all graph edges
 * • Merge all relationship builders
 * • Remove duplicated edges
 * • Validate references
 * • Produce graph statistics
 *
 * Used by
 * --------
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class GraphRelationshipBuilder
{
    /**
     * All graph edges.
     *
     * @var KnowledgeEdge[]
     */
    protected array $edges = [];

    public function __construct(

        protected BusinessRoleTechnologyEdgeBuilder $businessRoleTechnology,

        protected TechnologyMachineryEdgeBuilder $technologyMachinery,

        protected ProductTechnologyEdgeBuilder $productTechnology,

        protected TechnologyCertificationEdgeBuilder $technologyCertification,

        protected CertificationMarketEdgeBuilder $certificationMarket,

        protected MarketBuyerEdgeBuilder $marketBuyer,

    ) {
    }

    /**
     * ===============================================================
     * Build Graph Relationships
     * ===============================================================
     */
    public function build(array $graph): array
    {
        $this->edges = [];

        $this->buildBusinessRoleEdges($graph);

        $this->buildTechnologyEdges($graph);

        $this->buildProductEdges($graph);

        $this->buildCertificationEdges($graph);

        $this->buildMarketEdges($graph);

        return $this->uniqueEdges();
    }

    /*
    |--------------------------------------------------------------------------
    | Business Role
    |--------------------------------------------------------------------------
    */

    protected function buildBusinessRoleEdges(array $graph): void
    {
        if (!empty($graph['business_role'])) {

            $this->append(

                $this->businessRoleTechnology
                    ->build($graph['business_role'])

            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Technology
    |--------------------------------------------------------------------------
    */

    protected function buildTechnologyEdges(array $graph): void
    {
        foreach ($graph['technologies'] ?? [] as $technology) {

            $this->append(

                $this->technologyMachinery
                    ->build($technology)

            );

            $this->append(

                $this->technologyCertification
                    ->build($technology)

            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

    protected function buildProductEdges(array $graph): void
    {
        foreach ($graph['products'] ?? [] as $product) {

            $this->append(

                $this->productTechnology
                    ->build($product)

            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Certification
    |--------------------------------------------------------------------------
    */

    protected function buildCertificationEdges(array $graph): void
    {
        foreach ($graph['certifications'] ?? [] as $certification) {

            $this->append(

                $this->certificationMarket
                    ->build($certification)

            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Market
    |--------------------------------------------------------------------------
    */

    protected function buildMarketEdges(array $graph): void
    {
        foreach ($graph['markets'] ?? [] as $market) {

            $this->append(

                $this->marketBuyer
                    ->build($market)

            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function append(iterable $edges): void
    {
        foreach ($edges as $edge) {

            $this->edges[] = $edge;

        }
    }

    /**
     * Remove duplicated relationships.
     *
     * @return KnowledgeEdge[]
     */
    protected function uniqueEdges(): array
    {
        $unique = [];

        foreach ($this->edges as $edge) {

            $key = implode('|', [

                $edge->from(),

                $edge->relationship(),

                $edge->to(),

            ]);

            $unique[$key] = $edge;

        }

        return array_values($unique);
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'total_edges' => count($this->edges),

            'unique_edges' => count($this->uniqueEdges()),

        ];
    }
}