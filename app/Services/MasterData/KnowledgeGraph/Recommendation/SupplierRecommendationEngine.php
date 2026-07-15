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
 * Supplier Recommendation Engine
 * ==========================================================================
 *
 * Recommends supplier profiles using the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Supplier recommendation
 * - Supplier discovery
 * - Preferred supplier ranking
 * - Upstream recommendation
 *
 * This engine works on Master Data only.
 * Company-level recommendation belongs to CompanyMatchEngine.
 *
 * ==========================================================================
 */
final class SupplierRecommendationEngine
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
     * Recommend Suppliers
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

            ->upstream($nodeId)

            ->map(fn (GraphNode $supplier) =>

                $this->buildRecommendation(
                    $source,
                    $supplier
                )

            )

            ->sortByDesc('score')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Supplier Profiles
     * =========================================================================
     *
     * Returns supplier business roles.
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function supplierProfiles(): Collection
    {
        return $this->query

            ->bySchema('Business/business_roles.php')

            ->filter(function (GraphNode $node) {

                $id = strtolower($node->id());

                return str_contains($id, 'supplier')
                    || str_contains($id, 'producer')
                    || str_contains($id, 'manufacturer')
                    || str_contains($id, 'converter');

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
     * Best Supplier
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

            'reason' => 'upstream_supplier',

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