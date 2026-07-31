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
 * Converts recommendation intelligence into human-readable business reasons.
 *
 * IMPORTANT:
 * This service does not calculate recommendation scores, relationships,
 * supply-chain paths, or company classifications.
 *
 * It explains intelligence already produced by upstream DRIE services.
 *
 * Role semantics:
 *
 * - canonical_role
 *   Primary classified role of the candidate company.
 *
 * - matched_role
 *   Exact candidate capability responsible for the recommendation.
 *
 * - source_matched_role
 *   Exact source-company capability participating in the relationship.
 *
 * Recommendation reasons MUST prioritize matched-role intelligence because
 * multi-role companies may be recommended through a capability different
 * from their canonical role.
 *
 * Version:
 * 2.1
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

                /*
                |--------------------------------------------------------------------------
                | Role Intelligence
                |--------------------------------------------------------------------------
                |
                | canonicalRole:
                | Primary/display classification of the candidate.
                |
                | matchedRole:
                | Exact candidate capability responsible for this recommendation.
                |
                | sourceMatchedRole:
                | Exact source-company capability participating in the match.
                |
                */

                $canonicalRole =
                    $candidate['canonical_role']
                    ?? null;

                $matchedRole =
                    $candidate['matched_role']
                    ?? $candidate['supply_chain_target_role']
                    ?? $canonicalRole;

                $sourceMatchedRole =
                    $candidate['source_matched_role']
                    ?? $candidate['supply_chain_source_role']
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
                | 4. Matched Role Relevance
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                |
                | Explain the capability that actually produced the business
                | relationship, not merely the candidate's canonical role.
                |
                | Example:
                |
                | canonical_role       = dyeing_finishing_mill
                | matched_role         = yarn_spinner
                | source_matched_role  = weaving_mill
                |
                | Reason:
                | Relevant yarn spinner capability
                |
                */

                if (
                    is_string($matchedRole)
                    && trim($matchedRole) !== ''
                ) {

                    $reasons[] = sprintf(
                        'Relevant %s capability',
                        $this->humanizeRole($matchedRole)
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 5. Matched Capability Connection
                |--------------------------------------------------------------------------
                |
                | When both exact roles are known, expose the business-capability
                | connection responsible for the recommendation.
                |
                */

                if (
                    is_string($sourceMatchedRole)
                    && trim($sourceMatchedRole) !== ''
                    && is_string($matchedRole)
                    && trim($matchedRole) !== ''
                    && $sourceMatchedRole !== $matchedRole
                ) {

                    $reasons[] = sprintf(
                        'Business match connects %s with %s capability',
                        $this->humanizeRole($sourceMatchedRole),
                        $this->humanizeRole($matchedRole)
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 6. Multi-Role Intelligence
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
                | 7. Multiple Relationship Evidence
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
                | 8. Relationship Confidence
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
                | 9. Product Intelligence
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
                | 10. Export Market Intelligence
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
                | 11. Certifications
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
                | 12. Machinery Intelligence
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
                | 13. Production Capacity
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
                | 14. Recommendation Strength
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
                | 15. Finalize Reasons
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
     * Normalize Relationship Confidence
     * --------------------------------------------------------------------------
     */
    private function normalizeConfidence(
        mixed $value
    ): float {

        if (! is_numeric($value)) {
            return 0.0;
        }

        return max(
            0.0,
            min(
                1.0,
                (float) $value
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Score
     * --------------------------------------------------------------------------
     */
    private function normalizeScore(
        mixed $value
    ): float {

        if (! is_numeric($value)) {
            return 0.0;
        }

        return max(
            0.0,
            min(
                100.0,
                (float) $value
            )
        );
    }
}