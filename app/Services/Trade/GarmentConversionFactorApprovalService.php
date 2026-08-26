<?php

declare(strict_types=1);

namespace App\Services\Trade;

use InvalidArgumentException;

class GarmentConversionFactorApprovalService
{
    /**
     * Preview an explicit conversion-factor approval decision.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Does not activate a factor.
     * - Requires a validated APPROVED_CANDIDATE.
     * - Requires reviewer identity and role for APPROVE.
     * - Preserves complete factor and evidence provenance.
     */
    public function preview(
        array $candidate,
        string $decision,
        ?string $reviewer = null,
        ?string $reviewerRole = null,
        ?string $notes = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Decision Normalization
        |--------------------------------------------------------------------------
        */

        $decision = strtoupper(
            trim($decision)
        );

        /*
        |--------------------------------------------------------------------------
        | Candidate Status Gate
        |--------------------------------------------------------------------------
        */

        $candidateStatus = strtoupper(
            trim((string) ($candidate['status'] ?? ''))
        );

        if ($candidateStatus !== 'APPROVED_CANDIDATE') {
            throw new InvalidArgumentException(
                'Approval requires a validated APPROVED_CANDIDATE.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Factor Eligibility Gate
        |--------------------------------------------------------------------------
        */

        if (($candidate['factor_eligible'] ?? false) !== true) {
            throw new InvalidArgumentException(
                'Candidate factor is not eligible for approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Activation Guard
        |--------------------------------------------------------------------------
        */

        $activationStatus = strtoupper(
            trim(
                (string) (
                    $candidate['activation_status']
                    ?? 'NOT_ACTIVE'
                )
            )
        );

        if ($activationStatus !== 'NOT_ACTIVE') {
            throw new InvalidArgumentException(
                'Only NOT_ACTIVE candidate factors can enter approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HS-8 Identity
        |--------------------------------------------------------------------------
        */

        $hsCode = trim(
            (string) ($candidate['hs_code'] ?? '')
        );

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 is required for factor approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Candidate Factor
        |--------------------------------------------------------------------------
        */

        $factor = $candidate['candidate_factor'] ?? null;

        if (
            $factor === null
            || !is_numeric($factor)
            || (float) $factor <= 0
        ) {
            throw new InvalidArgumentException(
                'A positive candidate factor is required for approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology
        |--------------------------------------------------------------------------
        */

        $methodology = trim(
            (string) ($candidate['methodology'] ?? '')
        );

        if ($methodology === '') {
            throw new InvalidArgumentException(
                'Methodology is required for factor approval.'
            );
        }
if ($methodology !== 'KG_PER_PCS') {
    throw new InvalidArgumentException(
        'Factor approval requires KG_PER_PCS methodology.'
    );
}

        /*
        |--------------------------------------------------------------------------
        | Evidence
        |--------------------------------------------------------------------------
        */

        $evidenceCount = (int) (
            $candidate['evidence_count'] ?? 0
        );

        $totalSampleSize = (int) (
            $candidate['total_sample_size'] ?? 0
        );

        if ($evidenceCount < 1) {
            throw new InvalidArgumentException(
                'Factor approval requires at least one evidence record.'
            );
        }

        if ($totalSampleSize < 1) {
            throw new InvalidArgumentException(
                'Factor approval requires a positive total sample size.'
            );
        }

        $evidenceReferences =
            $candidate['evidence_references'] ?? [];

        if (!is_array($evidenceReferences)) {
            throw new InvalidArgumentException(
                'Evidence references must be an array.'
            );
        }

        if (count($evidenceReferences) !== $evidenceCount) {
            throw new InvalidArgumentException(
                'Evidence reference count must match evidence count.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Validation Gate
        |--------------------------------------------------------------------------
        */

        foreach ($evidenceReferences as $reference) {
            if (!is_array($reference)) {
                throw new InvalidArgumentException(
                    'Each evidence reference must be an array.'
                );
            }

            $referenceHsCode = trim(
                (string) ($reference['hs_code'] ?? '')
            );

            if (
                $referenceHsCode !== ''
                && $referenceHsCode !== $hsCode
            ) {
                throw new InvalidArgumentException(
                    'Evidence reference HS-8 does not match candidate HS-8.'
                );
            }

            $referenceStatus = strtoupper(
                trim(
                    (string) (
                        $reference['validation_status']
                        ?? ''
                    )
                )
            );

            if ($referenceStatus !== 'VALIDATED') {
                throw new InvalidArgumentException(
                    'All evidence references must have VALIDATED status.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Reviewer Normalization
        |--------------------------------------------------------------------------
        */

        $reviewer = $reviewer !== null
            ? trim($reviewer)
            : null;

        $reviewerRole = $reviewerRole !== null
            ? trim($reviewerRole)
            : null;

        $notes = $notes !== null
            ? trim($notes)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Decision Gate
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $decision,
            ['APPROVE', 'REJECT', 'REVIEW'],
            true
        )) {
            throw new InvalidArgumentException(
                'Unsupported approval decision. Use APPROVE, REJECT, or REVIEW.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | APPROVE — Reviewer Required
        |--------------------------------------------------------------------------
        */

        if ($decision === 'APPROVE') {
            if ($reviewer === null || $reviewer === '') {
                return [
                    'status' =>
                        'REVIEW',

                    'approval_code' =>
                        'REVIEWER_REQUIRED',

                    'decision' =>
                        'APPROVE',

                    'approved' =>
                        false,

                    'hs_code' =>
                        $hsCode,

                    'candidate_factor' =>
                        round((float) $factor, 6),

                    'methodology' =>
                        $methodology,

                    'evidence_type' =>
                        $candidate['evidence_type'] ?? null,

                    'weight_unit' =>
                        $candidate['weight_unit'] ?? null,

                    'evidence_count' =>
                        $evidenceCount,

                    'total_sample_size' =>
                        $totalSampleSize,

                    'evidence_references' =>
                        $evidenceReferences,

                    'calculation_method' =>
                        $candidate['calculation_method'] ?? null,

                    'observed_minimum' =>
                        $candidate['observed_minimum'] ?? null,

                    'observed_maximum' =>
                        $candidate['observed_maximum'] ?? null,

                    'activation_status' =>
                        'NOT_ACTIVE',

                    'reason' =>
                        'An authorized reviewer is required for approval.',
                ];
            }

            if ($reviewerRole === null || $reviewerRole === '') {
                return [
                    'status' =>
                        'REVIEW',

                    'approval_code' =>
                        'REVIEWER_ROLE_REQUIRED',

                    'decision' =>
                        'APPROVE',

                    'approved' =>
                        false,

                    'hs_code' =>
                        $hsCode,

                    'candidate_factor' =>
                        round((float) $factor, 6),

                    'methodology' =>
                        $methodology,

                    'evidence_type' =>
                        $candidate['evidence_type'] ?? null,

                    'weight_unit' =>
                        $candidate['weight_unit'] ?? null,

                    'evidence_count' =>
                        $evidenceCount,

                    'total_sample_size' =>
                        $totalSampleSize,

                    'evidence_references' =>
                        $evidenceReferences,

                    'calculation_method' =>
                        $candidate['calculation_method'] ?? null,

                    'observed_minimum' =>
                        $candidate['observed_minimum'] ?? null,

                    'observed_maximum' =>
                        $candidate['observed_maximum'] ?? null,

                    'activation_status' =>
                        'NOT_ACTIVE',

                    'reason' =>
                        'An authorized reviewer role is required for approval.',
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REJECT
        |--------------------------------------------------------------------------
        */

        if ($decision === 'REJECT') {
            return [
                'status' =>
                    'REJECTED',

                'approval_code' =>
                    'FACTOR_REJECTED',

                'decision' =>
                    'REJECT',

                'approved' =>
                    false,

                'hs_code' =>
                    $hsCode,

                'candidate_factor' =>
                    round((float) $factor, 6),

                'methodology' =>
                    $methodology,

                'evidence_type' =>
                    $candidate['evidence_type'] ?? null,

                'weight_unit' =>
                    $candidate['weight_unit'] ?? null,

                'evidence_count' =>
                    $evidenceCount,

                'total_sample_size' =>
                    $totalSampleSize,

                'evidence_references' =>
                    $evidenceReferences,

                'calculation_method' =>
                    $candidate['calculation_method'] ?? null,

                'observed_minimum' =>
                    $candidate['observed_minimum'] ?? null,

                'observed_maximum' =>
                    $candidate['observed_maximum'] ?? null,

                'reviewer' =>
                    $reviewer,

                'reviewer_role' =>
                    $reviewerRole,

                'review_notes' =>
                    $notes,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor has been explicitly rejected. Factor remains inactive.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | REVIEW
        |--------------------------------------------------------------------------
        */

        if ($decision === 'REVIEW') {
            return [
                'status' =>
                    'REVIEW',

                'approval_code' =>
                    'FACTOR_REVIEW_REQUIRED',

                'decision' =>
                    'REVIEW',

                'approved' =>
                    false,

                'hs_code' =>
                    $hsCode,

                'candidate_factor' =>
                    round((float) $factor, 6),

                'methodology' =>
                    $methodology,

                'evidence_type' =>
                    $candidate['evidence_type'] ?? null,

                'weight_unit' =>
                    $candidate['weight_unit'] ?? null,

                'evidence_count' =>
                    $evidenceCount,

                'total_sample_size' =>
                    $totalSampleSize,

                'evidence_references' =>
                    $evidenceReferences,

                'calculation_method' =>
                    $candidate['calculation_method'] ?? null,

                'observed_minimum' =>
                    $candidate['observed_minimum'] ?? null,

                'observed_maximum' =>
                    $candidate['observed_maximum'] ?? null,

                'reviewer' =>
                    $reviewer,

                'reviewer_role' =>
                    $reviewerRole,

                'review_notes' =>
                    $notes,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor requires further review. Factor remains inactive.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | APPROVE — Final Approval Payload
        |--------------------------------------------------------------------------
        */

        return [
            'status' =>
                'APPROVED',

            'approval_code' =>
                'FACTOR_APPROVED',

            'decision' =>
                'APPROVE',

            'approved' =>
                true,

            /*
            |--------------------------------------------------------------------------
            | Factor Identity
            |--------------------------------------------------------------------------
            */

            'hs_code' =>
                $hsCode,

            'candidate_factor' =>
                round((float) $factor, 6),

            'methodology' =>
                $methodology,

            /*
            |--------------------------------------------------------------------------
            | Evidence Provenance
            |--------------------------------------------------------------------------
            */

            'evidence_type' =>
                $candidate['evidence_type'] ?? null,

            'weight_unit' =>
                $candidate['weight_unit'] ?? null,

            'evidence_count' =>
                $evidenceCount,

            'total_sample_size' =>
                $totalSampleSize,

            'evidence_references' =>
                $evidenceReferences,

            /*
            |--------------------------------------------------------------------------
            | Calculation Provenance
            |--------------------------------------------------------------------------
            */

            'calculation_method' =>
                $candidate['calculation_method'] ?? null,

            'observed_minimum' =>
                $candidate['observed_minimum'] ?? null,

            'observed_maximum' =>
                $candidate['observed_maximum'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Reviewer
            |--------------------------------------------------------------------------
            */

            'reviewer' =>
                $reviewer,

            'reviewer_role' =>
                $reviewerRole,

            'review_notes' =>
                $notes,

            /*
            |--------------------------------------------------------------------------
            | Activation Guard
            |--------------------------------------------------------------------------
            */

            'activation_status' =>
                'NOT_ACTIVE',

            'reason' =>
                'Candidate factor has been approved through the explicit approval gate. Factor remains inactive until a separate activation process is executed.',
        ];
    }
}