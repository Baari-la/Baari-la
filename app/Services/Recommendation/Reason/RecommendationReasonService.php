<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Reason;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Reason Service
 * ==========================================================================
 *
 * Converts recommendation intelligence produced by upstream services into
 * deterministic, evidence-based, human-readable explanations.
 *
 * IMPORTANT:
 *
 * This service DOES NOT:
 *
 * - calculate recommendation scores
 * - infer business relationships
 * - classify companies
 * - calculate supply-chain paths
 *
 * It explains intelligence already produced by the recommendation pipeline.
 *
 * Version:
 * 3.0
 */
class RecommendationReasonService
{
    /**
     * --------------------------------------------------------------------------
     * Build Recommendation Reasons
     * --------------------------------------------------------------------------
     */
    public function build(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates
            ->map(function (array $candidate) use ($company, $context): array {

                $reasons = [];

                /*
                |--------------------------------------------------------------------------
                | Core Intelligence
                |--------------------------------------------------------------------------
                */

                $candidateRelationship =
                    $candidate['inverse_relationship']
                    ?? data_get(
                        $candidate,
                        'primary_relationship.target_relationship'
                    );

                $semanticType =
                    $candidate['semantic_type']
                    ?? data_get(
                        $candidate,
                        'primary_relationship.semantic_type'
                    );

                $discoveryMode =
                    $candidate['discovery_mode']
                    ?? data_get(
                        $candidate,
                        'primary_relationship.discovery_mode'
                    );

                $direction =
                    $candidate['supply_chain_direction']
                    ?? $candidate['relationship_direction']
                    ?? data_get(
                        $candidate,
                        'primary_relationship.direction'
                    );

                $canonicalRole =
                    $candidate['canonical_role']
                    ?? null;

                $specificRoles =
                    $candidate['specific_roles']
                    ?? [];

                $relationshipConfidence =
                    $this->normalizeConfidence(
                        $candidate['relationship_confidence']
                        ?? data_get(
                            $candidate,
                            'primary_relationship.confidence',
                            0
                        )
                    );

                /*
                |--------------------------------------------------------------------------
                | 1. Business Relationship
                |--------------------------------------------------------------------------
                */

                if ($candidateRelationship === 'buyer') {

                    $reasons[] =
                        $discoveryMode === 'buyer_discovery'
                            ? 'Relevant potential buyer identified'
                            : 'Buyer relationship identified';

                } elseif ($candidateRelationship === 'supplier') {

                    $reasons[] =
                        $discoveryMode === 'supplier_discovery'
                            ? 'Relevant potential supplier identified'
                            : 'Supplier relationship identified';

                } elseif (
                    in_array(
                        $candidateRelationship,
                        [
                            'solution_partner',
                            'technology_partner',
                            'partner',
                        ],
                        true
                    )
                ) {

                    $reasons[] =
                        'Relevant business solution partner identified';
                }

                /*
                |--------------------------------------------------------------------------
                | 2. Semantic Relationship
                |--------------------------------------------------------------------------
                */

                if ($semanticType === 'material_flow') {

                    if ($direction === 'downstream') {

                        $reasons[] =
                            'Connected through downstream textile material flow';

                    } elseif ($direction === 'upstream') {

                        $reasons[] =
                            'Connected through upstream textile material flow';

                    } else {

                        $reasons[] =
                            'Connected through textile material flow';
                    }

                } elseif ($semanticType === 'solution_supply') {

                    $reasons[] =
                        'Relevant solution capability for textile operations';

                } elseif ($semanticType === 'technology_solution') {

                    $reasons[] =
                        'Relevant technology capability for textile operations';
                }

                /*
                |--------------------------------------------------------------------------
                | 3. Supply-Chain Proximity
                |--------------------------------------------------------------------------
                */

                $depth =
                    isset($candidate['supply_chain_depth'])
                        ? (int) $candidate['supply_chain_depth']
                        : null;

                $depthClass =
                    $candidate['supply_chain_depth_class']
                    ?? null;

                if ($depth !== null && $depth > 0) {

                    if ($depth === 1) {

                        $reasons[] =
                            'Direct ecosystem relationship identified';

                    } elseif ($depth === 2) {

                        $reasons[] =
                            'Strategic two-step ecosystem relationship identified';

                    } elseif ($depth === 3) {

                        $reasons[] =
                            'Extended ecosystem relationship identified';

                    } elseif ($depthClass !== null) {

                        $reasons[] =
                            sprintf(
                                '%s ecosystem relationship identified',
                                ucfirst(
                                    $this->humanizeRole(
                                        (string) $depthClass
                                    )
                                )
                            );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 4. Role Relevance
                |--------------------------------------------------------------------------
                */

                if (
                    is_string($canonicalRole)
                    && $canonicalRole !== ''
                ) {

                    $reasons[] = sprintf(
                        'Relevant %s capability',
                        $this->humanizeRole($canonicalRole)
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 5. Multi-Role Intelligence
                |--------------------------------------------------------------------------
                */

                if (
                    is_array($specificRoles)
                    && count($specificRoles) >= 2
                ) {

                    $roleLabels = collect($specificRoles)
                        ->filter(
                            fn ($role) =>
                                is_string($role)
                                && trim($role) !== ''
                        )
                        ->unique()
                        ->take(3)
                        ->map(
                            fn (string $role) =>
                                $this->humanizeRole($role)
                        )
                        ->values()
                        ->all();

                    if (count($roleLabels) >= 2) {

                        $reasons[] = sprintf(
                            'Multiple capabilities identified: %s',
                            implode(', ', $roleLabels)
                        );

                    } else {

                        $reasons[] =
                            'Multiple textile capabilities identified';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 6. Multiple Relationship Evidence
                |--------------------------------------------------------------------------
                */

                $relationships = collect(
                    $candidate['business_relationships']
                    ?? []
                );

                if ($relationships->count() >= 2) {

                    $reasons[] =
                        'Supported by multiple ecosystem role relationships';
                }

                /*
                |--------------------------------------------------------------------------
                | 7. Relationship Confidence
                |--------------------------------------------------------------------------
                */

                if ($relationshipConfidence >= 0.90) {

                    $reasons[] =
                        'High-confidence business relationship';

                } elseif ($relationshipConfidence >= 0.70) {

                    $reasons[] =
                        'Strong business relationship confidence';

                } elseif ($relationshipConfidence >= 0.50) {

                    $reasons[] =
                        'Moderate business relationship confidence';
                }

                /*
                |--------------------------------------------------------------------------
                | 8. Product Intelligence
                |--------------------------------------------------------------------------
                */

                $products =
                    $candidate['products']
                    ?? null;

                if (
                    $products instanceof Collection
                    && $products->isNotEmpty()
                ) {

                    $productNames = $products
                        ->map(
                            fn ($product) =>
                                $product->product_name
                                ?? null
                        )
                        ->filter()
                        ->unique()
                        ->take(3)
                        ->values()
                        ->all();

                    if ($productNames !== []) {

                        $reasons[] = sprintf(
                            'Product evidence includes %s',
                            implode(', ', $productNames)
                        );

                    } else {

                        $reasons[] =
                            'Structured product portfolio available';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 9. Export Market Intelligence
                |--------------------------------------------------------------------------
                */

                $markets =
                    $candidate['markets']
                    ?? null;

                if (
                    $markets instanceof Collection
                    && $markets->isNotEmpty()
                ) {

                    $marketCount =
                        $markets->count();

                    if ($marketCount >= 5) {

                        $reasons[] =
                            'Broad international market presence identified';

                    } else {

                        $reasons[] =
                            'Export market experience identified';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 10. Certifications
                |--------------------------------------------------------------------------
                */

                $certifications =
                    $candidate['certifications']
                    ?? null;

                if (
                    $certifications instanceof Collection
                    && $certifications->isNotEmpty()
                ) {

                    $reasons[] =
                        'Certification evidence supports company credibility';
                }

                /*
                |--------------------------------------------------------------------------
                | 11. Machinery Intelligence
                |--------------------------------------------------------------------------
                */

                $machines =
                    $candidate['machines']
                    ?? null;

                if (
                    $machines instanceof Collection
                    && $machines->isNotEmpty()
                ) {

                    $reasons[] =
                        'Structured machinery capability data available';
                }

                /*
                |--------------------------------------------------------------------------
                | 12. Production Capacity
                |--------------------------------------------------------------------------
                */

                $capacities =
                    $candidate['capacities']
                    ?? null;

                if (
                    $capacities instanceof Collection
                    && $capacities->isNotEmpty()
                ) {

                    $reasons[] =
                        'Declared production capacity available';
                }

                /*
                |--------------------------------------------------------------------------
                | 13. Recommendation Strength
                |--------------------------------------------------------------------------
                */

                $rankingScore =
                    $this->normalizeScore(
                        $candidate['ranking_score']
                        ?? 0
                    );

                if ($rankingScore >= 90) {

                    $reasons[] =
                        'Top-tier recommendation based on combined intelligence signals';

                } elseif ($rankingScore >= 80) {

                    $reasons[] =
                        'Strong recommendation based on combined intelligence signals';

                } elseif ($rankingScore >= 70) {

                    $reasons[] =
                        'Relevant recommendation supported by combined intelligence signals';
                }

                /*
                |--------------------------------------------------------------------------
                | 14. Finalize Reasons
                |--------------------------------------------------------------------------
                */

                $candidate['reasons'] = collect($reasons)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                return $candidate;

            })
            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Humanize Role
     * --------------------------------------------------------------------------
     */
    private function humanizeRole(
        string $role
    ): string {

        return str_replace(
            '_',
            ' ',
            trim($role)
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Confidence
     * --------------------------------------------------------------------------
     */
    private function normalizeConfidence(
        mixed $confidence
    ): float {

        return max(
            0,
            min(
                1,
                (float) $confidence
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Score
     * --------------------------------------------------------------------------
     */
    private function normalizeScore(
        mixed $score
    ): float {

        return max(
            0,
            min(
                100,
                (float) $score
            )
        );
    }
}