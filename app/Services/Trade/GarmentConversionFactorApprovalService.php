<?php

declare(strict_types=1);

namespace App\Services\Trade;

use InvalidArgumentException;

class GarmentConversionFactorApprovalService
{
    /**
     * Preview an approval decision for a validated candidate factor.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database records.
     * - Does not activate a factor.
     * - Approval is an explicit governance decision.
     */
    public function preview(
        array $candidate,
        string $decision,
        ?string $reviewer = null,
        ?string $reviewerRole = null,
        ?string $notes = null
    ): array {
        $decision = strtoupper(trim($decision));

        /*
        |--------------------------------------------------------------------------
        | Decision Validation
        |--------------------------------------------------------------------------
        */

        $allowedDecisions = [
            'APPROVE',
            'REJECT',
            'RETURN',
        ];

        if (!in_array($decision, $allowedDecisions, true)) {
            throw new InvalidArgumentException(
                'Invalid approval decision. Allowed decisions: APPROVE, REJECT, RETURN.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Candidate Status Gate
        |--------------------------------------------------------------------------
        */

        $candidateStatus = strtoupper(
            trim((string) ($candidate['status'] ?? ''))
        );

        if ($candidateStatus !== 'APPROVED_CANDIDATE') {
            return [
                'status' => 'REVIEW',

                'approval_code' =>
                    'INVALID_CANDIDATE_STATUS',

                'decision' => $decision,

                'approved' => false,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Only APPROVED_CANDIDATE factors may enter the approval gate.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Factor Eligibility Gate
        |--------------------------------------------------------------------------
        */

        if (
            !isset($candidate['factor_eligible'])
            || $candidate['factor_eligible'] !== true
        ) {
            return [
                'status' => 'REVIEW',

                'approval_code' =>
                    'FACTOR_NOT_ELIGIBLE',

                'decision' => $decision,

                'approved' => false,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor is not eligible for approval.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Candidate Factor Gate
        |--------------------------------------------------------------------------
        */

        $factor = $candidate['candidate_factor'] ?? null;

        if (
            $factor === null
            || !is_numeric($factor)
            || (float) $factor <= 0
        ) {
            return [
                'status' => 'REVIEW',

                'approval_code' =>
                    'INVALID_CANDIDATE_FACTOR',

                'decision' => $decision,

                'approved' => false,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor must be a positive numeric value.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Gate
        |--------------------------------------------------------------------------
        */

        $evidenceCount =
            (int) ($candidate['evidence_count'] ?? 0);

        $totalSampleSize =
            (int) ($candidate['total_sample_size'] ?? 0);

        if (
            $evidenceCount < 1
            || $totalSampleSize < 1
        ) {
            return [
                'status' => 'REVIEW',

                'approval_code' =>
                    'INSUFFICIENT_EVIDENCE',

                'decision' => $decision,

                'approved' => false,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor does not contain sufficient evidence for approval.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Reviewer Gate
        |--------------------------------------------------------------------------
        */

        if ($decision === 'APPROVE') {
            if (
                $reviewer === null
                || trim($reviewer) === ''
            ) {
                return [
                    'status' => 'REVIEW',

                    'approval_code' =>
                        'REVIEWER_REQUIRED',

                    'decision' => $decision,

                    'approved' => false,

                    'activation_status' =>
                        'NOT_ACTIVE',

                    'reason' =>
                        'An authorized reviewer is required for approval.',
                ];
            }

            if (
                $reviewerRole === null
                || trim($reviewerRole) === ''
            ) {
                return [
                    'status' => 'REVIEW',

                    'approval_code' =>
                        'REVIEWER_ROLE_REQUIRED',

                    'decision' => $decision,

                    'approved' => false,

                    'activation_status' =>
                        'NOT_ACTIVE',

                    'reason' =>
                        'Reviewer role is required for approval auditability.',
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Decision: APPROVE
        |--------------------------------------------------------------------------
        */

        if ($decision === 'APPROVE') {
            return [
                'status' =>
                    'APPROVED',

                'approval_code' =>
                    'FACTOR_APPROVED',

                'decision' =>
                    'APPROVE',

                'approved' =>
                    true,

                'hs_code' =>
                    $candidate['hs_code'] ?? null,

                'candidate_factor' =>
                    round((float) $factor, 6),

                'methodology' =>
                    $candidate['methodology'] ?? null,

                'evidence_count' =>
                    $evidenceCount,

                'total_sample_size' =>
                    $totalSampleSize,

                'reviewer' =>
                    trim($reviewer),

                'reviewer_role' =>
                    trim($reviewerRole),

                'review_notes' =>
                    $notes !== null
                        ? trim($notes)
                        : null,

                /*
                |--------------------------------------------------------------------------
                | CRITICAL: Approval does not activate factor
                |--------------------------------------------------------------------------
                */

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor has been approved through the explicit approval gate. Factor remains inactive until a separate activation process is executed.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Decision: REJECT
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
                    $candidate['hs_code'] ?? null,

                'candidate_factor' =>
                    round((float) $factor, 6),

                'reviewer' =>
                    $reviewer !== null
                        ? trim($reviewer)
                        : null,

                'reviewer_role' =>
                    $reviewerRole !== null
                        ? trim($reviewerRole)
                        : null,

                'review_notes' =>
                    $notes !== null
                        ? trim($notes)
                        : null,

                'activation_status' =>
                    'NOT_ACTIVE',

                'reason' =>
                    'Candidate factor was rejected through the approval gate.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Decision: RETURN
        |--------------------------------------------------------------------------
        */

        return [
            'status' =>
                'RETURNED',

            'approval_code' =>
                'FACTOR_RETURNED_FOR_REVIEW',

            'decision' =>
                'RETURN',

            'approved' =>
                false,

            'hs_code' =>
                $candidate['hs_code'] ?? null,

            'candidate_factor' =>
                round((float) $factor, 6),

            'reviewer' =>
                $reviewer !== null
                    ? trim($reviewer)
                    : null,

            'reviewer_role' =>
                $reviewerRole !== null
                    ? trim($reviewerRole)
                    : null,

            'review_notes' =>
                $notes !== null
                    ? trim($notes)
                    : null,

            'activation_status' =>
                'NOT_ACTIVE',

            'reason' =>
                'Candidate factor has been returned for additional evidence or review.',
        ];
    }
}