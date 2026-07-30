<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Score;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Confidence Score Service
 * ==========================================================================
 *
 * Measures how strongly available evidence supports a recommendation.
 *
 * IMPORTANT:
 * This service does NOT measure business compatibility.
 * It measures confidence in the underlying evidence and data.
 *
 * Version:
 * 1.0
 */
class ConfidenceScoreService
{
    public function calculate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates->map(function (array $candidate) {

            /*
            |--------------------------------------------------------------------------
            | 1. Role Evidence Quality — 30%
            |--------------------------------------------------------------------------
            */

            $roleConfidence = (float) ($candidate['role_confidence'] ?? 0);

            $roleScore = max(
                0,
                min(100, $roleConfidence * 100)
            );

            /*
            |--------------------------------------------------------------------------
            | 2. Relationship Confidence — 25%
            |--------------------------------------------------------------------------
            */

            $relationshipConfidence = (float) (
                $candidate['relationship_confidence'] ?? 0
            );

            $relationshipScore = max(
                0,
                min(100, $relationshipConfidence * 100)
            );

            /*
            |--------------------------------------------------------------------------
            | 3. Structured Data Quality — 20%
            |--------------------------------------------------------------------------
            */

            $structuredSignals = [
                'products',
                'markets',
                'certifications',
                'machines',
                'capacities',
            ];

            $availableSignals = 0;

            foreach ($structuredSignals as $signal) {

                $value = $candidate[$signal] ?? null;

                if ($value instanceof Collection) {

                    if ($value->isNotEmpty()) {
                        $availableSignals++;
                    }

                    continue;
                }

                if (is_array($value) && ! empty($value)) {
                    $availableSignals++;
                }
            }

            $structuredScore = (
                $availableSignals / count($structuredSignals)
            ) * 100;

            /*
            |--------------------------------------------------------------------------
            | 4. Verification Quality — 15%
            |--------------------------------------------------------------------------
            */

            $companyModel = $candidate['company'] ?? null;

            $verificationStatus = strtolower(
                trim(
                    (string) (
                        $companyModel?->status_verifikasi
                        ?? ''
                    )
                )
            );

            $verificationScore = match ($verificationStatus) {

                'verified',
                'approved',
                'active' => 100,

                'pending' => 50,

                default => 25,
            };

            /*
            |--------------------------------------------------------------------------
            | 5. Evidence Diversity — 10%
            |--------------------------------------------------------------------------
            */

            $evidence = $candidate['role_evidence'] ?? [];

            if (! is_array($evidence)) {
                $evidence = [];
            }

            $evidenceTypes = [];

            foreach ($evidence as $item) {

                if (! is_string($item)) {
                    continue;
                }

                $type = str_contains($item, ':')
                    ? explode(':', $item, 2)[0]
                    : $item;

                $type = strtolower(trim($type));

                if ($type !== '') {
                    $evidenceTypes[$type] = true;
                }
            }

            $diversityCount = count($evidenceTypes);

            $evidenceDiversityScore = min(
                100,
                $diversityCount * 25
            );

            /*
            |--------------------------------------------------------------------------
            | Final Confidence Score
            |--------------------------------------------------------------------------
            */

            $weightedRole =
                $roleScore * 0.30;

            $weightedRelationship =
                $relationshipScore * 0.25;

            $weightedStructured =
                $structuredScore * 0.20;

            $weightedVerification =
                $verificationScore * 0.15;

            $weightedEvidence =
                $evidenceDiversityScore * 0.10;

            $confidenceScore =

                $weightedRole
                + $weightedRelationship
                + $weightedStructured
                + $weightedVerification
                + $weightedEvidence;

            $candidate['confidence_score'] = round(
                min(100, max(0, $confidenceScore)),
                1
            );

            /*
            |--------------------------------------------------------------------------
            | Explainability
            |--------------------------------------------------------------------------
            */

            $candidate['confidence_breakdown'] = [

                'role_evidence' => [
                    'raw' => round($roleScore, 1),
                    'weight' => 30,
                    'weighted' => round($weightedRole, 1),
                ],

                'relationship' => [
                    'raw' => round($relationshipScore, 1),
                    'weight' => 25,
                    'weighted' => round($weightedRelationship, 1),
                ],

                'structured_data' => [
                    'raw' => round($structuredScore, 1),
                    'weight' => 20,
                    'weighted' => round($weightedStructured, 1),
                ],

                'verification' => [
                    'raw' => round($verificationScore, 1),
                    'weight' => 15,
                    'weighted' => round($weightedVerification, 1),
                ],

                'evidence_diversity' => [
                    'raw' => round($evidenceDiversityScore, 1),
                    'weight' => 10,
                    'weighted' => round($weightedEvidence, 1),
                ],

            ];

            return $candidate;
        });
    }
}