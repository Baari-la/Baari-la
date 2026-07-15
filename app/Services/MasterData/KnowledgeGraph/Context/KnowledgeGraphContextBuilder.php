<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\AI\Context;

use App\Services\MasterData\KnowledgeGraph\Recommendation\BuyerRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\Recommendation\CertificationRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\Recommendation\MarketRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\Recommendation\ProductRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\Recommendation\SupplierRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\Recommendation\SustainabilityRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\Recommendation\TechnologyRecommendationEngine;
use App\Services\MasterData\KnowledgeGraph\GraphQuery;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Knowledge Graph Context Builder
 * ==========================================================================
 *
 * Builds a complete AI context from the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Graph context
 * - Neighbor context
 * - Recommendation context
 * - AI-ready structured payload
 *
 * This class NEVER calls any AI model.
 *
 * ==========================================================================
 */
final class KnowledgeGraphContextBuilder
{
    /**
     * Constructor.
     */
    public function __construct(

        protected GraphQuery $graph,

        protected SupplierRecommendationEngine $supplier,

        protected BuyerRecommendationEngine $buyer,

        protected ProductRecommendationEngine $product,

        protected TechnologyRecommendationEngine $technology,

        protected CertificationRecommendationEngine $certification,

        protected MarketRecommendationEngine $market,

        protected SustainabilityRecommendationEngine $sustainability,

    ) {
    }

    /**
     * =========================================================================
     * Build Context
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function build(
        string $nodeId
    ): array
    {
        $node = $this->graph->find($nodeId);

        if ($node === null) {

            return [];

        }

        return [

            'node' => [

                'id' => $node->id(),

                'label' => $node->label(),

                'schema' => $node->schema(),

                'type' => $node->type(),

                'properties' => $node->properties(),

            ],

            'graph' => [

                'neighbors' =>

                    $this->graph
                        ->neighbors($nodeId)
                        ->values()
                        ->all(),

                'upstream' =>

                    $this->graph
                        ->upstream($nodeId)
                        ->values()
                        ->all(),

                'downstream' =>

                    $this->graph
                        ->downstream($nodeId)
                        ->values()
                        ->all(),

                'reachable' =>

                    $this->graph
                        ->reachable($nodeId)
                        ->values()
                        ->all(),

            ],

            'recommendations' => [

                'suppliers' =>

                    $this->supplier
                        ->recommend($nodeId)
                        ->all(),

                'buyers' =>

                    $this->buyer
                        ->recommend($nodeId)
                        ->all(),

                'products' =>

                    $this->product
                        ->recommend($nodeId)
                        ->all(),

                'technologies' =>

                    $this->technology
                        ->recommend($nodeId)
                        ->all(),

                'certifications' =>

                    $this->certification
                        ->recommend($nodeId)
                        ->all(),

                'markets' =>

                    $this->market
                        ->recommend($nodeId)
                        ->all(),

                'sustainability' =>

                    $this->sustainability
                        ->recommend($nodeId)
                        ->all(),

            ],

            'summary' => [

                'neighbor_count' =>

                    $this->graph
                        ->neighbors($nodeId)
                        ->count(),

                'upstream_count' =>

                    $this->graph
                        ->upstream($nodeId)
                        ->count(),

                'downstream_count' =>

                    $this->graph
                        ->downstream($nodeId)
                        ->count(),

                'reachable_count' =>

                    $this->graph
                        ->reachable($nodeId)
                        ->count(),

            ],

        ];
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(
        string $nodeId
    ): array
    {
        return $this->build($nodeId);
    }
}