<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Candidate;

use App\Models\Company;
use App\Services\Company\Intelligence\BusinessRoleService;
use App\Services\Company\Relationship\BusinessRelationshipService;
use App\Services\Recommendation\SupplyChain\SupplyChainDepthService;
use Illuminate\Support\Collection;
/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Candidate Evaluator
 * ==========================================================================
 *
 * Relationship-aware candidate evaluation for DRIE.
 *
 * Responsibilities:
 *
 * - Normalize candidate company data
 * - Resolve company and candidate business roles
 * - Evaluate canonical and specific role relationships
 * - Determine candidate eligibility
 * - Preserve relationship intelligence for downstream scoring
 *
 * This service DOES NOT calculate recommendation scores.
 *
 * Version:
 * 3.0
 */
class CandidateEvaluator
{
    public function __construct(
    protected BusinessRoleService $businessRole,
    protected BusinessRelationshipService $relationship,
    protected SupplyChainDepthService $supplyChainDepth,
) {
}

    /**
     * --------------------------------------------------------------------------
     * Evaluate Candidates
     * --------------------------------------------------------------------------
     */
    public function evaluate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        /*
        |--------------------------------------------------------------------------
        | Source Company Classification
        |--------------------------------------------------------------------------
        */

        $companyClassification =
            $this->businessRole->classify($company);

        $companyRoles =
            $this->classificationRoles($companyClassification);

        return $candidates
            ->map(function (Company $candidate) use (
                $companyRoles
            ) {

                /*
                |--------------------------------------------------------------------------
                | Candidate Classification
                |--------------------------------------------------------------------------
                */

                $classification =
                    $this->businessRole->classify($candidate);

                $candidateRoles =
                    $this->classificationRoles($classification);

                /*
                |--------------------------------------------------------------------------
                | Business Relationships
                |--------------------------------------------------------------------------
                */

                $relationships =
                    $this->resolveRelationships(
                        $companyRoles,
                        $candidateRoles,
                    );
                /*
                |--------------------------------------------------------------------------
                | Multi-Hop Supply Chain Fallback
                |--------------------------------------------------------------------------
                |
                | Direct semantic relationships remain authoritative.
                |
                | Multi-hop discovery is used only when no direct business relationship
                | exists between any source-role / candidate-role combination.
                |
                | Default recommendation discovery supports:
                |
                | depth 1 = direct
                | depth 2 = strategic
                | depth 3 = extended
                |
                */

                if ($relationships->isEmpty()) {

                    $relationships =
                        $this->resolveMultiHopRelationships(
                            companyRoles: $companyRoles,
                            candidateRoles: $candidateRoles,
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Eligibility
                |--------------------------------------------------------------------------
                */

                $eligible =
                    $relationships->isNotEmpty();

                $eligibilityReasons =
                    $eligible
                        ? $this->buildEligibilityReasons(
                            $relationships
                        )
                        : [
                            'No recognized business relationship between company and candidate roles.',
                        ];

                /*
                |--------------------------------------------------------------------------
                | Primary Relationship
                |--------------------------------------------------------------------------
                */

                $primaryRelationship =
                    $this->primaryRelationship(
                        $relationships
                    );
/*
|--------------------------------------------------------------------------
| Supply Chain Depth Intelligence
|--------------------------------------------------------------------------
|
| Resolve supply-chain distance using the exact role pair responsible
| for the primary business relationship.
|
| IMPORTANT:
| Do not use canonical_role here because a multi-role company may have
| been matched through one of its specific roles.
|
*/

if (
    $primaryRelationship !== null &&
    isset($primaryRelationship['supply_chain_depth'])
) {

    $supplyChain = [

        'reachable' =>
            $primaryRelationship[
                'supply_chain_reachable'
            ] ?? true,

        'depth' =>
            $primaryRelationship[
                'supply_chain_depth'
            ],

        'direction' =>
            $primaryRelationship[
                'supply_chain_direction'
            ] ?? null,

        'path' =>
            $primaryRelationship[
                'supply_chain_path'
            ] ?? [],

        'source_role' =>
            $primaryRelationship[
                'supply_chain_source_role'
            ] ?? null,

        'target_role' =>
            $primaryRelationship[
                'supply_chain_target_role'
            ] ?? null,

        'depth_class' =>
            $primaryRelationship[
                'supply_chain_depth_class'
            ] ?? null,

        'depth_score' =>
            $primaryRelationship[
                'supply_chain_depth_score'
            ] ?? 0,
    ];

} elseif ($primaryRelationship !== null) {

    $sourceRole =
        $primaryRelationship['company_role']
        ?? null;

    $targetRole =
        $primaryRelationship['candidate_role']
        ?? null;

    if (
        is_string($sourceRole) &&
        $sourceRole !== '' &&
        is_string($targetRole) &&
        $targetRole !== ''
    ) {

        $supplyChain =
            $this->supplyChainDepth->resolve(
                $sourceRole,
                $targetRole
            );
    }
}
                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Identity
                    |--------------------------------------------------------------------------
                    */

                    'company' => $candidate,

                    'company_id' => $candidate->id,

                    'company_name' =>
                        $candidate->nama_perusahaan,

                    /*
                    |--------------------------------------------------------------------------
                    | Business
                    |--------------------------------------------------------------------------
                    */

                    'category' => $candidate->category,

                    'membership' =>
                        $candidate->membership_type,

                    'country' =>
                        $candidate->country_name,

                    'city' => $candidate->city,

                    /*
                    |--------------------------------------------------------------------------
                    | Business Role Intelligence
                    |--------------------------------------------------------------------------
                    */

                    'ecosystem_role' =>
                        $classification['ecosystem_role']
                        ?? null,

                    'canonical_role' =>
                        $classification['canonical_role']
                        ?? null,

                    'specific_roles' =>
                        $classification['specific_roles']
                        ?? [],

                    'role_confidence' =>
                        $classification['confidence']
                        ?? 0,

                    'role_source' =>
                        $classification['source']
                        ?? null,

                    'role_evidence' =>
                        $classification['evidence']
                        ?? [],

                    'role_scores' =>
                        $classification['role_scores']
                        ?? [],

                    /*
                    |--------------------------------------------------------------------------
                    | Relationship Intelligence
                    |--------------------------------------------------------------------------
                    */

                    'business_relationships' =>
                        $relationships
                            ->values()
                            ->all(),

                    'primary_relationship' =>
                        $primaryRelationship,

                    'business_relationship' =>
                        $primaryRelationship[
                            'business_relationship'
                        ] ?? null,

                    'inverse_relationship' =>
                        $primaryRelationship[
                            'inverse_relationship'
                        ] ?? null,

                    'semantic_type' =>
                        $primaryRelationship[
                            'semantic_type'
                        ] ?? null,

                    'relationship_direction' =>
                        $primaryRelationship[
                            'direction'
                        ] ?? null,

                    'discovery_mode' =>
                        $primaryRelationship[
                            'discovery_mode'
                        ] ?? null,

                    'relationship_confidence' =>
                        $primaryRelationship[
                            'confidence'
                        ] ?? 0,

                    /*
                    |--------------------------------------------------------------------------
                    | Supply Chain Intelligence
                    |--------------------------------------------------------------------------
                    */

                    'supply_chain_reachable' =>
                        (bool) (
                            $supplyChain['reachable']
                            ?? false
                        ),

                    'supply_chain_depth' =>
                        $supplyChain['depth']
                        ?? null,

                    'supply_chain_direction' =>
                        $supplyChain['direction']
                        ?? null,

                    'supply_chain_path' =>
                        $supplyChain['path']
                        ?? [],

                    'supply_chain_source_role' =>
                        $supplyChain['source_role']
                        ?? null,

                    'supply_chain_target_role' =>
                        $supplyChain['target_role']
                        ?? null,

                     'supply_chain_depth_class' =>
                    $supplyChain['depth_class']
                    ?? (
                        match ($supplyChain['depth'] ?? null) {

                            1 => 'direct',

                            2 => 'strategic',

                            3 => 'extended',

                            default => null,
                        }
                    ),

                'supply_chain_depth_score' =>
                    $supplyChain['depth_score']
                    ?? (
                        match ($supplyChain['depth'] ?? null) {

                            1 => 100.0,

                            2 => 80.0,

                            3 => 60.0,

                            default => 0.0,
                        }
                    ),   
                                                            
                    /*
                    |--------------------------------------------------------------------------
                    | Relations
                    |--------------------------------------------------------------------------
                    */

                    'products' =>
                        $candidate->products,

                    'markets' =>
                        $candidate->markets,

                    'certifications' =>
                        $candidate->certifications,

                    'machines' =>
                        $candidate->machines,

                    'capacities' =>
                        $candidate->capacities,

                    /*
                    |--------------------------------------------------------------------------
                    | Recommendation
                    |--------------------------------------------------------------------------
                    */

                    'eligible' => $eligible,

                    'eligibility_reasons' =>
                        $eligibilityReasons,

                    'compatibility_score' => 0,

                    'recommendation_score' => 0,

                    'confidence_score' => 0,

                    'ranking_score' => 0,

                    'reasons' => [],
                ];

            })
            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Classification Roles
     * --------------------------------------------------------------------------
     *
     * Build the complete role set used for relationship evaluation.
     *
     * Canonical role is always included even when specific_roles
     * does not contain it.
     */
    protected function classificationRoles(
        array $classification
    ): array {

        return collect([
            $classification['canonical_role']
                ?? null,

            ...(
                $classification['specific_roles']
                ?? []
            ),
        ])
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Resolve Relationships
     * --------------------------------------------------------------------------
     *
     * Evaluate every source-role / candidate-role combination.
     */
    protected function resolveRelationships(
        array $companyRoles,
        array $candidateRoles,
    ): Collection {

        $relationships = collect();

        foreach ($companyRoles as $companyRole) {

            foreach ($candidateRoles as $candidateRole) {

                /*
                |--------------------------------------------------------------------------
                | Ignore Identical Roles
                |--------------------------------------------------------------------------
                |
                | Same-role companies are not automatically considered
                | business partners.
                |
                */

                if ($companyRole === $candidateRole) {
                    continue;
                }

                $relationship =
                    $this->relationship->resolve(
                        $companyRole,
                        $candidateRole,
                    );

                if ($relationship === null) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Preserve Role Pair
                |--------------------------------------------------------------------------
                */

                $relationships->push([
                    ...$relationship,

                    'company_role' =>
                        $companyRole,

                    'candidate_role' =>
                        $candidateRole,
                ]);
            }
        }

        return $relationships
            ->unique(function (array $relationship) {

                return implode('|', [

                    $relationship[
                        'company_role'
                    ] ?? '',

                    $relationship[
                        'candidate_role'
                    ] ?? '',

                    $relationship[
                        'semantic_type'
                    ] ?? '',

                    $relationship[
                        'business_relationship'
                    ] ?? '',

                    $relationship[
                        'discovery_mode'
                    ] ?? '',

                ]);
            })
            ->values();
    }

/**
 * --------------------------------------------------------------------------
 * Resolve Multi-Hop Relationships
 * --------------------------------------------------------------------------
 *
 * Fallback discovery for companies that do not have an explicit direct
 * semantic business relationship.
 *
 * SupplyChainDepthService remains responsible for graph reachability.
 * CandidateEvaluator translates reachable paths into recommendation
 * relationship intelligence.
 *
 * Only depth 2 and depth 3 are synthesized here.
 *
 * Depth 1 should normally be represented by BusinessRelationshipService.
 */
protected function resolveMultiHopRelationships(
    array $companyRoles,
    array $candidateRoles,
): Collection {

    $relationships = collect();

    foreach ($companyRoles as $companyRole) {

        foreach ($candidateRoles as $candidateRole) {

            /*
            |--------------------------------------------------------------------------
            | Ignore Identical Roles
            |--------------------------------------------------------------------------
            */

            if ($companyRole === $candidateRole) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Resolve Supply Chain Discovery
            |--------------------------------------------------------------------------
            */

            $supplyChain =
                $this->supplyChainDepth->resolveDiscovery(
                    sourceRole: $companyRole,
                    targetRole: $candidateRole,
                    maxDepth: 3,
                );

            if (! ($supplyChain['reachable'] ?? false)) {
                continue;
            }

            if (! ($supplyChain['discovery_eligible'] ?? false)) {
                continue;
            }

            $depth =
                (int) ($supplyChain['depth'] ?? 0);

            /*
            |--------------------------------------------------------------------------
            | Direct Relationships Belong to BusinessRelationshipService
            |--------------------------------------------------------------------------
            */

            if ($depth < 2 || $depth > 3) {
                continue;
            }

            $direction =
                $supplyChain['direction'] ?? null;

            if (! in_array(
                $direction,
                ['upstream', 'downstream'],
                true
            )) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Relationship Semantics
            |--------------------------------------------------------------------------
            |
            | Source perspective:
            |
            | downstream candidate:
            | source supplies candidate
            | candidate is potential buyer
            |
            | upstream candidate:
            | candidate supplies source
            | candidate is potential supplier
            |
            */

            if ($direction === 'downstream') {

                $businessRelationship = 'supplier';
                $inverseRelationship = 'buyer';
                $discoveryMode = 'buyer_discovery';

            } else {

                $businessRelationship = 'buyer';
                $inverseRelationship = 'supplier';
                $discoveryMode = 'supplier_discovery';
            }

            /*
            |--------------------------------------------------------------------------
            | Confidence
            |--------------------------------------------------------------------------
            |
            | Multi-hop confidence intentionally decays with distance.
            |
            */

            $confidence = match ($depth) {

                2 => 0.80,

                3 => 0.60,

                default => 0.0,
            };

            /*
            |--------------------------------------------------------------------------
            | Build Synthetic Relationship
            |--------------------------------------------------------------------------
            */

            $relationships->push([

                'source_role' =>
                    $companyRole,

                'target_role' =>
                    $candidateRole,

                'semantic_type' =>
                    'material_flow',

                'source_relationship' =>
                    $businessRelationship,

                'target_relationship' =>
                    $inverseRelationship,

                'business_relationship' =>
                    $businessRelationship,

                'inverse_relationship' =>
                    $inverseRelationship,

                'direction' =>
                    $direction,

                'discovery_mode' =>
                    $discoveryMode,

                'resolved_via' =>
                    'supply_chain_multi_hop',

                'confidence' =>
                    $confidence,

                'company_role' =>
                    $companyRole,

                'candidate_role' =>
                    $candidateRole,

                'supply_chain_reachable' =>
                    true,

                'supply_chain_depth' =>
                    $depth,

                'supply_chain_direction' =>
                    $direction,

                'supply_chain_path' =>
                    $supplyChain['path'] ?? [],

                'supply_chain_source_role' =>
                    $supplyChain['source_role']
                    ?? $companyRole,

                'supply_chain_target_role' =>
                    $supplyChain['target_role']
                    ?? $candidateRole,

                'supply_chain_depth_class' =>
                    $supplyChain['depth_class']
                    ?? null,

                'supply_chain_depth_score' =>
                    $supplyChain['depth_score']
                    ?? 0,
            ]);
        }
    }

    return $relationships
        ->unique(function (array $relationship) {

            return implode('|', [

                $relationship['company_role']
                    ?? '',

                $relationship['candidate_role']
                    ?? '',

                $relationship['direction']
                    ?? '',

                $relationship['discovery_mode']
                    ?? '',

                $relationship['supply_chain_depth']
                    ?? '',
            ]);
        })
        ->values();
}



    /**
     * --------------------------------------------------------------------------
     * Primary Relationship
     * --------------------------------------------------------------------------
     *
     * Select the strongest relationship for downstream recommendation
     * services while preserving every relationship separately.
     */
   
    protected function primaryRelationship(
        Collection $relationships
    ): ?array {

        if ($relationships->isEmpty()) {
            return null;
        }

        return $relationships
            ->sortByDesc(
                fn (array $relationship) =>
                    $this->relationshipPriority(
                        $relationship
                    )
            )
            ->first();
    }

    /**
     * --------------------------------------------------------------------------
     * Relationship Priority
     * --------------------------------------------------------------------------
     */
    protected function relationshipPriority(
        array $relationship
    ): float {

        $score = 0;

        /*
        |--------------------------------------------------------------------------
        | Relationship Confidence
        |--------------------------------------------------------------------------
        */

        $score +=
            ((float) (
                $relationship['confidence']
                ?? 0
            )) * 100;

        /*
        |--------------------------------------------------------------------------
        | Semantic Priority
        |--------------------------------------------------------------------------
        */

        $score += match (
            $relationship['semantic_type']
            ?? null
        ) {

            'material_flow' =>
                30,

            'technology_solution' =>
                25,

            'solution_supply' =>
                20,

            default =>
                0,
        };

        /*
        |--------------------------------------------------------------------------
        | Discovery Priority
        |--------------------------------------------------------------------------
        */

        $score += match (
            $relationship['discovery_mode']
            ?? null
        ) {

            'supplier_discovery' =>
                20,

            'buyer_discovery' =>
                15,

            'technology_partner_discovery' =>
                12,

            'solution_partner_discovery' =>
                10,

            default =>
                0,
        };

        return $score;
    }

    /**
     * --------------------------------------------------------------------------
     * Eligibility Reasons
     * --------------------------------------------------------------------------
     */
    protected function buildEligibilityReasons(
        Collection $relationships
    ): array {

        return $relationships
            ->map(function (array $relationship) {

                $companyRole =
                    $relationship['company_role']
                    ?? 'unknown';

                $candidateRole =
                    $relationship['candidate_role']
                    ?? 'unknown';

                $businessRelationship =
                    $relationship[
                        'business_relationship'
                    ]
                    ?? 'business_relationship';

                $discoveryMode =
                    $relationship[
                        'discovery_mode'
                    ]
                    ?? 'relationship_discovery';

                return sprintf(
                    '%s → %s: %s (%s)',
                    $companyRole,
                    $candidateRole,
                    $businessRelationship,
                    $discoveryMode,
                );
            })
            ->unique()
            ->values()
            ->all();
    }
}