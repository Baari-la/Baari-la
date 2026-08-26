<?php

declare(strict_types=1);

namespace App\Services\Trade;

use InvalidArgumentException;

class GarmentConversionFactorApprovalAuditService
{
    /**
     * Preview an approval audit record.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Does not activate a factor.
     * - Requires a fully formed APPROVED approval payload.
     * - Preserves complete factor and evidence provenance.
     */
    public function preview(
        array $approval,
        ?string $previousStatus = 'APPROVED_CANDIDATE'
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Approval Status Gate
        |--------------------------------------------------------------------------
        */

        $status = strtoupper(
            trim((string) ($approval['status'] ?? ''))
        );

        if ($status !== 'APPROVED') {
            throw new InvalidArgumentException(
                'Approval audit requires an APPROVED decision result.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Decision Gate
        |--------------------------------------------------------------------------
        */

        $decision = strtoupper(
            trim((string) ($approval['decision'] ?? ''))
        );

        if ($decision !== 'APPROVE') {
            throw new InvalidArgumentException(
                'Approval audit requires decision APPROVE.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Approval Flag
        |--------------------------------------------------------------------------
        */

        if (($approval['approved'] ?? false) !== true) {
            throw new InvalidArgumentException(
                'Approval audit requires approved=true.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Required Identity
        |--------------------------------------------------------------------------
        */

        $hsCode = trim(
            (string) ($approval['hs_code'] ?? '')
        );

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 is required for approval audit.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Candidate Factor
        |--------------------------------------------------------------------------
        */

        $factor = $approval['candidate_factor'] ?? null;

        if (
            $factor === null
            || !is_numeric($factor)
            || (float) $factor <= 0
        ) {
            throw new InvalidArgumentException(
                'A positive candidate factor is required for approval audit.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology
        |--------------------------------------------------------------------------
        */

        $methodology = trim(
            (string) ($approval['methodology'] ?? '')
        );

        if ($methodology === '') {
            throw new InvalidArgumentException(
                'Methodology is required for approval audit.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = trim(
            (string) ($approval['reviewer'] ?? '')
        );

        if ($reviewer === '') {
            throw new InvalidArgumentException(
                'Reviewer is required for approval audit.'
            );
        }

        $reviewerRole = trim(
            (string) ($approval['reviewer_role'] ?? '')
        );

        if ($reviewerRole === '') {
            throw new InvalidArgumentException(
                'Reviewer role is required for approval audit.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Traceability
        |--------------------------------------------------------------------------
        */

        $evidenceCount = (int) (
            $approval['evidence_count'] ?? 0
        );

        $totalSampleSize = (int) (
            $approval['total_sample_size'] ?? 0
        );

        if ($evidenceCount < 1) {
            throw new InvalidArgumentException(
                'Approval audit requires at least one evidence record.'
            );
        }

        if ($totalSampleSize < 1) {
            throw new InvalidArgumentException(
                'Approval audit requires a positive total sample size.'
            );
        }

        $evidenceReferences =
            $approval['evidence_references'] ?? [];

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
        | Evidence Validation
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
                    'Evidence reference HS-8 does not match approval HS-8.'
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
        | Activation Guard
        |--------------------------------------------------------------------------
        */

        $activationStatus = strtoupper(
            trim(
                (string) (
                    $approval['activation_status']
                    ?? 'NOT_ACTIVE'
                )
            )
        );

        if ($activationStatus !== 'NOT_ACTIVE') {
            throw new InvalidArgumentException(
                'Approval audit cannot record an already active factor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Previous Status
        |--------------------------------------------------------------------------
        */

        $previousStatus = $previousStatus !== null
            ? strtoupper(trim($previousStatus))
            : 'APPROVED_CANDIDATE';

        if ($previousStatus === '') {
            $previousStatus = 'APPROVED_CANDIDATE';
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Payload
        |--------------------------------------------------------------------------
        */

        return [
            'audit_status' =>
                'READY',

            'audit_code' =>
                'APPROVAL_AUDIT_READY',

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
            | Evidence
            |--------------------------------------------------------------------------
            */

            'evidence_type' =>
                $approval['evidence_type'] ?? null,

            'weight_unit' =>
                $approval['weight_unit'] ?? null,

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
                $approval['calculation_method'] ?? null,

            'observed_minimum' =>
                $approval['observed_minimum'] ?? null,

            'observed_maximum' =>
                $approval['observed_maximum'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Governance Decision
            |--------------------------------------------------------------------------
            */

            'decision' =>
                'APPROVE',

            'previous_status' =>
                $previousStatus,

            'new_status' =>
                'APPROVED',

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
                $approval['review_notes'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Activation
            |--------------------------------------------------------------------------
            */

            'activation_status' =>
                'NOT_ACTIVE',

            'reason' =>
                'Approval decision is ready for audit recording. Factor remains inactive.',
        ];
    }
}