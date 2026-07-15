<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Recommendation;

use App\Services\MasterData\KnowledgeGraph\GraphNode;
use App\Services\MasterData\KnowledgeGraph\GraphQuery;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Buyer Recommendation Engine
 * ==========================================================================
 *
 * Recommends buyer profiles using the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Buyer recommendation
 * - Downstream discovery
 * - Business buyer matching
 * - Buyer ranking
 *
 * This engine operates on Master Data.
 * Company-level recommendation belongs to CompanyMatchEngine.
 *
 * ==========================================================================
 */
final class BuyerRecommendationEngine
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphQuery $query,
        protected SimilarityEngine $similarity,
    ) {
    }

    /**
     * =========================================================================
     * Recommend Buyers
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function recommend(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        $source = $this->query->find($nodeId);

        if ($source === null) {

            return collect();

        }

        return $this->query

            ->downstream($nodeId)

            ->map(

                fn (GraphNode $buyer) =>

                    $this->buildRecommendation(

                        $source,

                        $buyer

                    )

            )

            ->sortByDesc('score')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Buyer Profiles
     * =========================================================================
     *
     * Returns buyer business roles.
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function buyerProfiles(): Collection
    {
        return $this->query

            ->bySchema('Business/business_roles.php')

            ->filter(function (GraphNode $node) {

                $id = strtolower(
                    $node->id()
                );

                return

                    str_contains($id, 'buyer')

                    ||

                    str_contains($id, 'brand')

                    ||

                    str_contains($id, 'retailer')

                    ||

                    str_contains($id, 'distributor')

                    ||

                    str_contains($id, 'wholesaler')

                    ||

                    str_contains($id, 'exporter');

            })

            ->map(fn (GraphNode $node) => [

                'id' => $node->id(),

                'label' => $node->label(),

                'schema' => $node->schema(),

                'type' => $node->type(),

            ])

            ->values();
    }

    /**
     * =========================================================================
     * Best Buyer
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function best(
        string $nodeId
    ): ?array
    {
        return $this->recommend(

            $nodeId,

            1

        )->first();
    }

    /**
     * =========================================================================
     * Recommendation Builder
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function buildRecommendation(
        GraphNode $source,
        GraphNode $candidate
    ): array
    {
        return [

            'id' => $candidate->id(),

            'label' => $candidate->label(),

            'schema' => $candidate->schema(),

            'type' => $candidate->type(),

            'score' =>

                $this->similarity

                    ->score(

                        $source,

                        $candidate

                    ),

            'analysis' =>

                $this->similarity

                    ->compare(

                        $source,

                        $candidate

                    ),

            'reason' => 'downstream_buyer',

        ];
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<int,array<string,mixed>>
     */
    public function toArray(
        string $nodeId,
        int $limit = 10
    ): array
    {
        return $this->recommend(

            $nodeId,

            $limit

        )->all();
    }
}