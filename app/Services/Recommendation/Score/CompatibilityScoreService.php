<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Score;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Compatibility Score Service
 * ==========================================================================
 *
 * Calculates structural business compatibility between a source company
 * and recommendation candidates.
 *
 * Compatibility represents:
 *
 * - Business relationship relevance
 * - Semantic relationship strength
 * - Relationship confidence
 * - Multi-role relationship strength
 * - Discovery relevance
 * - Graph / supply-chain proximity
 *
 * This service DOES NOT calculate:
 *
 * - Final recommendation ranking
 * - Data confidence
 * - AI opportunity score
 * - Commercial attractiveness
 *
 * Version:
 * 3.0
 */
class CompatibilityScoreService
{
    /**
     * --------------------------------------------------------------------------
     * Calculate Compatibility Score
     * --------------------------------------------------------------------------
     */
    public function calculate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates
            ->map(function (array $candidate) use ($context) {

                /*
                |--------------------------------------------------------------------------
                | Ineligible Candidate
                |--------------------------------------------------------------------------
                */

                if (! ($candidate['eligible'] ?? false)) {

                    $candidate['compatibility_score'] = 0;

                    $candidate['compatibility_breakdown'] = [
                        'relationship' => 0,
                        'semantic' => 0,
                        'confidence' => 0,
                        'multi_role_strength' => 0,
                        'discovery' => 0,
                        'proximity' => 0,
                    ];

                    return $candidate;
                }

                $score = 0;

                $breakdown = [
                    'relationship' => 0,
                    'semantic' => 0,
                    'confidence' => 0,
                    'multi_role_strength' => 0,
                    'discovery' => 0,
                    'proximity' => 0,
                ];

                /*
                |--------------------------------------------------------------------------
                | 1. Business Relationship
                |--------------------------------------------------------------------------
                |
                | Maximum: 30
                |
                */

                $relationshipScore = match (
                    $candidate['business_relationship'] ?? null
                ) {
                    'supplier',
                    'buyer' =>
                        30,

                    'technology_partner' =>
                        28,

                    'solution_partner' =>
                        27,

                    'technology_consumer' =>
                        26,

                    'solution_consumer' =>
                        25,

                    default =>
                        12,
                };

                $score += $relationshipScore;

                $breakdown['relationship'] =
                    $relationshipScore;

                /*
                |--------------------------------------------------------------------------
                | 2. Semantic Relationship
                |--------------------------------------------------------------------------
                |
                | Maximum: 20
                |
                */

                $semanticScore = match (
                    $candidate['semantic_type'] ?? null
                ) {
                    'material_flow' =>
                        20,

                    'technology_solution' =>
                        18,

                    'solution_supply' =>
                        17,

                    default =>
                        8,
                };

                $score += $semanticScore;

                $breakdown['semantic'] =
                    $semanticScore;

                /*
                |--------------------------------------------------------------------------
                | 3. Relationship Confidence
                |--------------------------------------------------------------------------
                |
                | Maximum: 20
                |
                */

                $relationshipConfidence = max(
                    0,
                    min(
                        1,
                        (float) (
                            $candidate['relationship_confidence']
                            ?? 0
                        )
                    )
                );

                $confidenceScore = round(
                    $relationshipConfidence * 20,
                    2
                );

                $score += $confidenceScore;

                $breakdown['confidence'] =
                    $confidenceScore;

                /*
                |--------------------------------------------------------------------------
                | 4. Multi-role Relationship Strength
                |--------------------------------------------------------------------------
                |
                | Maximum: 10
                |
                | Measures how many legitimate role-pair relationships exist
                | between the source company and candidate.
                |
                | This is NOT supply-chain distance.
                |
                */

                $relationships = collect(
                    $candidate['business_relationships']
                    ?? []
                );

                $relationshipCount =
                    $relationships->count();

                $multiRoleScore = match (true) {
                    $relationshipCount >= 4 =>
                        10,

                    $relationshipCount === 3 =>
                        8,

                    $relationshipCount === 2 =>
                        6,

                    $relationshipCount === 1 =>
                        4,

                    default =>
                        0,
                };

                $score += $multiRoleScore;

                $breakdown['multi_role_strength'] =
                    $multiRoleScore;

                /*
                |--------------------------------------------------------------------------
                | 5. Discovery Relevance
                |--------------------------------------------------------------------------
                |
                | Maximum: 10
                |
                */

                $discoveryMode =
                    $candidate['discovery_mode']
                    ?? null;

                $discoveryScore = match (
                    $discoveryMode
                ) {
                    'supplier_discovery',
                    'buyer_discovery' =>
                        10,

                    'technology_partner_discovery' =>
                        9,

                    'solution_partner_discovery' =>
                        8,

                    default =>
                        4,
                };

                /*
                |--------------------------------------------------------------------------
                | Explicit Discovery Intent
                |--------------------------------------------------------------------------
                */

                $requestedDiscovery =
                    $context['discovery_mode']
                    ?? null;

                if (
                    is_string($requestedDiscovery) &&
                    $requestedDiscovery !== '' &&
                    $requestedDiscovery === $discoveryMode
                ) {
                    $discoveryScore = 10;
                }

                $score += $discoveryScore;

                $breakdown['discovery'] =
                    $discoveryScore;

                /*
                |--------------------------------------------------------------------------
                | 6. Graph / Supply-chain Proximity
                |--------------------------------------------------------------------------
                |
                | Maximum: 10
                |
                | Uses the normalized depth intelligence produced by
                | SupplyChainDepthService.
                |
                | Examples:
                |
                | depth 1 = direct     = 100 -> 10
                | depth 2 = strategic  =  80 ->  8
                | depth 3 = extended   =  60 ->  6
                |
                | For technology / solution relationships this represents
                | graph relationship proximity rather than material-flow
                | distance.
                |
                */

                $reachable = (bool) (
                    $candidate['supply_chain_reachable']
                    ?? false
                );

                $rawDepthScore = max(
                    0,
                    min(
                        100,
                        (float) (
                            $candidate[
                                'supply_chain_depth_score'
                            ] ?? 0
                        )
                    )
                );

                $proximityScore = $reachable
                    ? round($rawDepthScore / 10, 2)
                    : 0;

                $score += $proximityScore;

                $breakdown['proximity'] =
                    $proximityScore;

                /*
                |--------------------------------------------------------------------------
                | Final Compatibility
                |--------------------------------------------------------------------------
                */

                $candidate['compatibility_score'] = min(
                    100,
                    round($score, 2)
                );

                $candidate['compatibility_breakdown'] =
                    $breakdown;

                return $candidate;

            })
            ->values();
    }
}