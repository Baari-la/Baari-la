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
 * Product Recommendation Engine
 * ==========================================================================
 *
 * Recommends related products using the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Related products
 * - Upstream products
 * - Downstream products
 * - Similar products
 * - Product ranking
 *
 * This engine works on Master Data only.
 *
 * ==========================================================================
 */
final class ProductRecommendationEngine
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
     * Recommend Products
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

        $recommendations = collect();

        /*
        |--------------------------------------------------------------------------
        | Similar Products
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->similar($nodeId)

        );

        /*
        |--------------------------------------------------------------------------
        | Upstream Products
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->upstream($nodeId)

        );

        /*
        |--------------------------------------------------------------------------
        | Downstream Products
        |--------------------------------------------------------------------------
        */

        $recommendations = $recommendations->merge(

            $this->query
                ->downstream($nodeId)

        );

        return $recommendations

            ->unique(
                fn (GraphNode $node) => $node->id()
            )

            ->map(

                fn (GraphNode $candidate) =>

                    $this->buildRecommendation(

                        $source,

                        $candidate

                    )

            )

            ->sortByDesc('score')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Product Categories
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function productCategories(): Collection
    {
        return $this->query

            ->bySchema(
                'product_categories.php'
            )

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
     * Best Recommendation
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

            'reason' => $this->reason(
                $source,
                $candidate
            ),

        ];
    }

    /**
     * =========================================================================
     * Recommendation Reason
     * =========================================================================
     */
    protected function reason(
        GraphNode $source,
        GraphNode $candidate
    ): string
    {
        if (
            $source->schema() ===
            $candidate->schema()
        ) {
            return 'similar_product';
        }

        if (
            $this->query
                ->upstream($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'upstream_product';
        }

        if (
            $this->query
                ->downstream($source->id())
                ->contains(
                    fn (GraphNode $node) =>
                        $node->id() === $candidate->id()
                )
        ) {
            return 'downstream_product';
        }

        return 'related_product';
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