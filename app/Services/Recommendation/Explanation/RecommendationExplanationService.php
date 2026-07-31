<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Explanation;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Explanation Service
 * ==========================================================================
 *
 * Converts deterministic recommendation intelligence into concise,
 * human-readable business explanations.
 *
 * IMPORTANT:
 *
 * This service DOES NOT:
 *
 * - classify companies
 * - resolve business relationships
 * - calculate supply-chain paths
 * - calculate compatibility
 * - calculate recommendation scores
 * - calculate confidence
 * - calculate ranking
 *
 * It only explains intelligence already produced by upstream services.
 *
 * Version:
 * 1.1
 */
class RecommendationExplanationService
{
    /**
     * --------------------------------------------------------------------------
     * Build Explanations
     * --------------------------------------------------------------------------
     */
    public function build(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates
            ->map(function (array $candidate) use ($company, $context): array {

                $candidateName = $this->companyName($candidate);

                $sourceName = $company->nama_perusahaan
                    ?: 'the source company';

                /*
                |--------------------------------------------------------------------------
                | Matched Role Intelligence
                |--------------------------------------------------------------------------
                |
                | canonicalRole:
                | Primary/display classification of the candidate.
                |
                | matchedRole:
                | Exact candidate capability responsible for this recommendation.
                |
                | sourceMatchedRole:
                | Exact source-company capability participating in the relationship.
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

                $direction =
                    $candidate['supply_chain_direction']
                    ?? $candidate['relationship_direction']
                    ?? data_get(
                        $candidate,
                        'primary_relationship.direction'
                    );

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

                $depth = isset($candidate['supply_chain_depth'])
                    ? (int) $candidate['supply_chain_depth']
                    : null;

                $depthClass =
                    $candidate['supply_chain_depth_class']
                    ?? null;

                $relationshipConfidence =
                    $this->normalizeConfidence(
                        $candidate['relationship_confidence']
                        ?? data_get(
                            $candidate,
                            'primary_relationship.confidence',
                            0
                        )
                    );

                $rankingScore =
                    $this->normalizeScore(
                        $candidate['ranking_score'] ?? 0
                    );

                /*
                |--------------------------------------------------------------------------
                | Explanation Components
                |--------------------------------------------------------------------------
                */

                $summary = $this->buildSummary(
                    sourceName: $sourceName,
                    candidateName: $candidateName,
                    matchedRole: $matchedRole,
                    candidateRelationship: $candidateRelationship,
                );

                $relationship = $this->buildRelationshipExplanation(
                    sourceName: $sourceName,
                    candidateName: $candidateName,
                    semanticType: $semanticType,
                    depth: $depth,
                    depthClass: $depthClass,
                    direction: $direction,
                    sourceMatchedRole: $sourceMatchedRole,
                    matchedRole: $matchedRole,
                );

                $evidence = $this->buildEvidenceExplanation(
                    $candidate
                );

                $confidence = $this->buildConfidenceExplanation(
                    $relationshipConfidence
                );

                $strength = $this->buildStrengthExplanation(
                    $rankingScore
                );

                /*
                |--------------------------------------------------------------------------
                | Narrative
                |--------------------------------------------------------------------------
                */

                $narrative = collect([
                    $summary,
                    $relationship,
                    $evidence,
                    $confidence,
                    $strength,
                ])
                    ->filter()
                    ->implode(' ');

                /*
                |--------------------------------------------------------------------------
                | Structured Explanation
                |--------------------------------------------------------------------------
                */

                $candidate['explanation'] = [
                    'headline' => $this->buildHeadline(
                        $candidateName,
                        $candidateRelationship
                    ),

                    'summary' => $summary,

                    'relationship' => $relationship,

                    'evidence' => $evidence,

                    'confidence' => $confidence,

                    'strength' => $strength,

                    'narrative' => $narrative,
                ];

                return $candidate;

            })
            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Build Headline
     * --------------------------------------------------------------------------
     */
    private function buildHeadline(
        string $candidateName,
        mixed $relationship
    ): string {

        $label = match ($relationship) {

            'buyer' =>
                'Potential Buyer',

            'supplier' =>
                'Potential Supplier',

            'solution_partner' =>
                'Solution Partner',

            'technology_partner' =>
                'Technology Partner',

            'partner' =>
                'Business Partner',

            default =>
                'Recommended Company',
        };

        return "{$candidateName} — {$label}";
    }

    /**
     * --------------------------------------------------------------------------
     * Build Summary
     * --------------------------------------------------------------------------
     */
            private function buildSummary(
            string $sourceName,
            string $candidateName,
            mixed $matchedRole,
            mixed $candidateRelationship,
        ): string {

            $role = is_string($matchedRole)
                && trim($matchedRole) !== ''
                    ? $this->humanizeRole($matchedRole)
                    : 'textile ecosystem';

            return match ($candidateRelationship) {

                'buyer' =>
                    "{$candidateName} is identified as a potential buyer for {$sourceName} through its {$role} capability.",

                'supplier' =>
                    "{$candidateName} is identified as a potential supplier for {$sourceName} through its {$role} capability.",

                'solution_partner' =>
                    "{$candidateName} is identified as a relevant solution partner for {$sourceName} through its {$role} capability.",

                'technology_partner' =>
                    "{$candidateName} is identified as a relevant technology partner for {$sourceName} through its {$role} capability.",

                'partner' =>
                    "{$candidateName} is identified as a potential business partner for {$sourceName}.",

                default =>
                    "{$candidateName} is identified as a relevant company for {$sourceName} within the textile ecosystem.",
            };
        }

    /**
     * --------------------------------------------------------------------------
     * Build Relationship Explanation
     * --------------------------------------------------------------------------
     */
    private function buildRelationshipExplanation(
    string $sourceName,
    string $candidateName,
    mixed $semanticType,
    ?int $depth,
    mixed $depthClass,
    mixed $direction,
    mixed $sourceMatchedRole,
    mixed $matchedRole,
): ?string {

    $proximity = match ($depth) {

        1 =>
            'a direct ecosystem relationship',

        2 =>
            'a strategic two-step ecosystem relationship',

        3 =>
            'an extended three-step ecosystem relationship',

        default =>
            is_string($depthClass)
            && trim($depthClass) !== ''
                ? 'a '
                    . $this->humanizeRole($depthClass)
                    . ' ecosystem relationship'
                : null,
    };

    $sourceRoleLabel =
        is_string($sourceMatchedRole)
        && trim($sourceMatchedRole) !== ''
            ? $this->humanizeRole($sourceMatchedRole)
            : null;

    $matchedRoleLabel =
        is_string($matchedRole)
        && trim($matchedRole) !== ''
            ? $this->humanizeRole($matchedRole)
            : null;

    /*
    |--------------------------------------------------------------------------
    | Material Flow
    |--------------------------------------------------------------------------
    */

    if ($semanticType === 'material_flow') {

        if (
            $sourceRoleLabel !== null
            && $matchedRoleLabel !== null
        ) {

            $directionLabel = match ($direction) {

                'upstream' =>
                    'upstream',

                'downstream' =>
                    'downstream',

                default =>
                    null,
            };

            $relationshipLabel =
                $proximity
                ?? 'an ecosystem relationship';

            $directionText =
                $directionLabel !== null
                    ? " {$directionLabel}"
                    : '';

            return "{$sourceName}'s {$sourceRoleLabel} capability and {$candidateName}'s {$matchedRoleLabel} capability are connected through {$relationshipLabel} in the{$directionText} textile material flow.";
        }

        return $proximity !== null
            ? "{$sourceName} and {$candidateName} are connected through {$proximity} within the textile material flow."
            : "{$sourceName} and {$candidateName} are connected through the textile material flow.";
    }

    /*
    |--------------------------------------------------------------------------
    | Solution Supply
    |--------------------------------------------------------------------------
    */

    if ($semanticType === 'solution_supply') {

        if ($matchedRoleLabel !== null) {

            return $proximity !== null
                ? "{$candidateName}'s {$matchedRoleLabel} capability is connected to {$sourceName} through {$proximity} in the textile solution ecosystem."
                : "{$candidateName}'s {$matchedRoleLabel} capability has a relevant solution-supply relationship with {$sourceName}.";
        }

        return $proximity !== null
            ? "{$candidateName} is connected to {$sourceName} through {$proximity} in the textile solution ecosystem."
            : "{$candidateName} has a relevant solution-supply relationship with {$sourceName}.";
    }

    /*
    |--------------------------------------------------------------------------
    | Technology Solution
    |--------------------------------------------------------------------------
    */

    if ($semanticType === 'technology_solution') {

        if ($matchedRoleLabel !== null) {

            return $proximity !== null
                ? "{$candidateName}'s {$matchedRoleLabel} capability is connected to {$sourceName} through {$proximity} in the textile technology ecosystem."
                : "{$candidateName}'s {$matchedRoleLabel} capability has a relevant technology relationship with {$sourceName}.";
        }

        return $proximity !== null
            ? "{$candidateName} is connected to {$sourceName} through {$proximity} in the textile technology ecosystem."
            : "{$candidateName} has a relevant technology relationship with {$sourceName}.";
    }

    return null;
}
    /**
     * --------------------------------------------------------------------------
     * Build Evidence Explanation
     * --------------------------------------------------------------------------
     */
    private function buildEvidenceExplanation(
        array $candidate
    ): ?string {

        $evidence = [];

        /*
        |--------------------------------------------------------------------------
        | Multiple Role Relationships
        |--------------------------------------------------------------------------
        */

        $relationships = collect(
            $candidate['business_relationships'] ?? []
        );

        $sourceRoles = $relationships
            ->pluck('company_role')
            ->filter()
            ->unique()
            ->map(
                fn ($role) =>
                    $this->humanizeRole((string) $role)
            )
            ->values();

        if ($sourceRoles->count() >= 2) {

            $evidence[] =
                'the relationship is supported across '
                . $this->joinLabels($sourceRoles->all())
                . ' operations';
        }

        /*
        |--------------------------------------------------------------------------
        | Product Evidence
        |--------------------------------------------------------------------------
        */

        $products = $candidate['products'] ?? null;

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
                ->reject(
                    fn ($name) =>
                        strtolower(trim((string) $name))
                        === 'trading'
                )
                ->unique()
                ->take(3)
                ->values()
                ->all();

            if ($productNames !== []) {

                $evidence[] =
                    'product evidence includes '
                    . implode(', ', $productNames);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Additional Structured Evidence
        |--------------------------------------------------------------------------
        */

        $certifications =
            $candidate['certifications'] ?? null;

        if (
            $certifications instanceof Collection
            && $certifications->isNotEmpty()
        ) {
            $evidence[] =
                'certification information is available';
        }

        $capacities =
            $candidate['capacities'] ?? null;

        if (
            $capacities instanceof Collection
            && $capacities->isNotEmpty()
        ) {
            $evidence[] =
                'production capacity information is available';
        }

        $markets =
            $candidate['markets'] ?? null;

        if (
            $markets instanceof Collection
            && $markets->isNotEmpty()
        ) {
            $evidence[] =
                'export market evidence is available';
        }

        if ($evidence === []) {
            return null;
        }

        return ucfirst(
            $this->joinLabels($evidence)
        ) . '.';
    }

    /**
     * --------------------------------------------------------------------------
     * Build Confidence Explanation
     * --------------------------------------------------------------------------
     */
    private function buildConfidenceExplanation(
        float $confidence
    ): ?string {

        if ($confidence >= 0.90) {

            return 'The business relationship is supported by high-confidence evidence.';
        }

        if ($confidence >= 0.70) {

            return 'The business relationship is supported by strong evidence.';
        }

        if ($confidence >= 0.50) {

            return 'The business relationship is supported by moderate evidence.';
        }

        return null;
    }

    /**
     * --------------------------------------------------------------------------
     * Build Recommendation Strength
     * --------------------------------------------------------------------------
     */
    private function buildStrengthExplanation(
        float $rankingScore
    ): ?string {

        if ($rankingScore >= 90) {

            return 'Overall intelligence signals indicate a top-tier recommendation.';
        }

        if ($rankingScore >= 80) {

            return 'Overall intelligence signals indicate a strong recommendation.';
        }

        if ($rankingScore >= 70) {

            return 'Overall intelligence signals support a relevant recommendation.';
        }

        return null;
    }

    /**
     * --------------------------------------------------------------------------
     * Company Name
     * --------------------------------------------------------------------------
     */
    private function companyName(
        array $candidate
    ): string {

        $name =
            $candidate['company_name']
            ?? data_get(
                $candidate,
                'company.nama_perusahaan'
            );

        if (
            ! is_string($name)
            || trim($name) === ''
        ) {
            return 'This company';
        }

        return trim($name);
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
     * Join Labels
     * --------------------------------------------------------------------------
     */
    private function joinLabels(
        array $labels
    ): string {

        $labels = array_values(
            array_filter(
                $labels,
                fn ($label) =>
                    is_string($label)
                    && trim($label) !== ''
            )
        );

        $count = count($labels);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $labels[0];
        }

        if ($count === 2) {
            return $labels[0]
                . ' and '
                . $labels[1];
        }

        $last = array_pop($labels);

        return implode(', ', $labels)
            . ', and '
            . $last;
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