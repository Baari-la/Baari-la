<?php

declare(strict_types=1);

namespace App\Services\Trade;

use InvalidArgumentException;

class GarmentConversionFactorActivationService
{
    /**
     * Preview an explicit conversion-factor activation decision.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Does not activate a factor.
     * - Requires a READY approval audit.
     * - Requires explicit activation authority.
     * - Preserves complete factor and evidence provenance.
     */
    public function preview(
        array $audit,
        string $decision,
        ?string $activator = null,
        ?string $activatorRole = null,
        ?string $notes = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Decision
        |--------------------------------------------------------------------------
        */

        $decision = strtoupper(
            trim($decision)
        );

        if (!in_array($decision, ['ACTIVATE', 'REJECT'], true)) {
            throw new InvalidArgumentException(
                'Activation decision must be ACTIVATE or REJECT.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Status Gate
        |--------------------------------------------------------------------------
        */

        $auditStatus = strtoupper(
            trim((string) ($audit['audit_status'] ?? ''))
        );

        if ($auditStatus !== 'READY') {
            throw new InvalidArgumentException(
                'Activation requires a READY approval audit.'
            );
        }

        $auditCode = strtoupper(
            trim((string) ($audit['audit_code'] ?? ''))
        );

        if ($auditCode !== 'APPROVAL_AUDIT_READY') {
            throw new InvalidArgumentException(
                'Activation requires APPROVAL_AUDIT_READY.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Approval Decision Gate
        |--------------------------------------------------------------------------
        */

        $approvalDecision = strtoupper(
            trim((string) ($audit['decision'] ?? ''))
        );

        if ($approvalDecision !== 'APPROVE') {
            throw new InvalidArgumentException(
                'Activation requires an approved factor decision.'
            );
        }

        $newStatus = strtoupper(
            trim((string) ($audit['new_status'] ?? ''))
        );

        if ($newStatus !== 'APPROVED') {
            throw new InvalidArgumentException(
                'Activation requires factor status APPROVED.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Current Activation State
        |--------------------------------------------------------------------------
        */

        $activationStatus = strtoupper(
            trim(
                (string) (
                    $audit['activation_status']
                    ?? 'NOT_ACTIVE'
                )
            )
        );

        if ($activationStatus !== 'NOT_ACTIVE') {
            throw new InvalidArgumentException(
                'Only NOT_ACTIVE factors may enter the activation gate.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Factor Identity
        |--------------------------------------------------------------------------
        */

        $hsCode = trim(
            (string) ($audit['hs_code'] ?? '')
        );

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 is required for activation.'
            );
        }

        $factor = $audit['candidate_factor'] ?? null;

        if (
            $factor === null
            || !is_numeric($factor)
            || (float) $factor <= 0
        ) {
            throw new InvalidArgumentException(
                'A positive candidate factor is required for activation.'
            );
        }

        $methodology = trim(
            (string) ($audit['methodology'] ?? '')
        );

        if ($methodology === '') {
            throw new InvalidArgumentException(
                'Methodology is required for activation.'
            );
        }

        if ($methodology !== 'KG_PER_PCS') {
    throw new InvalidArgumentException(
        'Factor activation requires KG_PER_PCS methodology.'
    );
}

        /*
        |--------------------------------------------------------------------------
        | Evidence Gate
        |--------------------------------------------------------------------------
        */

        $evidenceCount = (int) (
            $audit['evidence_count'] ?? 0
        );

        $totalSampleSize = (int) (
            $audit['total_sample_size'] ?? 0
        );

        if ($evidenceCount < 1) {
            throw new InvalidArgumentException(
                'Activation requires at least one evidence record.'
            );
        }

        if ($totalSampleSize < 1) {
            throw new InvalidArgumentException(
                'Activation requires a positive total sample size.'
            );
        }

        $evidenceReferences =
            $audit['evidence_references'] ?? [];

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
                    'Evidence reference HS-8 does not match activation HS-8.'
                );
            }

            $validationStatus = strtoupper(
                trim(
                    (string) (
                        $reference['validation_status']
                        ?? ''
                    )
                )
            );

            if ($validationStatus !== 'VALIDATED') {
                throw new InvalidArgumentException(
                    'All evidence references must have VALIDATED status.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Reviewer Traceability
        |--------------------------------------------------------------------------
        */

        $reviewer = trim(
            (string) ($audit['reviewer'] ?? '')
        );

        if ($reviewer === '') {
            throw new InvalidArgumentException(
                'Approval reviewer is required for activation.'
            );
        }

        $reviewerRole = trim(
            (string) ($audit['reviewer_role'] ?? '')
        );

        if ($reviewerRole === '') {
            throw new InvalidArgumentException(
                'Approval reviewer role is required for activation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Activation Authority
        |--------------------------------------------------------------------------
        */

        $activator = $activator !== null
            ? trim($activator)
            : '';

        $activatorRole = $activatorRole !== null
            ? trim($activatorRole)
            : '';

        if ($decision === 'ACTIVATE') {
            if ($activator === '') {
                throw new InvalidArgumentException(
                    'An authorized activator is required for activation.'
                );
            }

            if ($activatorRole === '') {
                throw new InvalidArgumentException(
                    'Activator role is required for activation.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Explicit Activation Authority Separation
        |--------------------------------------------------------------------------
        */

        if (
            $decision === 'ACTIVATE'
            && strcasecmp($activator, $reviewer) === 0
        ) {
            throw new InvalidArgumentException(
                'Activation authority must be separate from the approval reviewer.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REJECT Gate
        |--------------------------------------------------------------------------
        */

        if ($decision === 'REJECT') {
            return [
                'status' =>
                    'REJECTED',

                'activation_code' =>
                    'FACTOR_ACTIVATION_REJECTED',

                'decision' =>
                    'REJECT',

                'activated' =>
                    false,

                'hs_code' =>
                    $hsCode,

                'candidate_factor' =>
                    round((float) $factor, 6),

                'methodology' =>
                    $methodology,

                'evidence_count' =>
                    $evidenceCount,

                'total_sample_size' =>
                    $totalSampleSize,

                'evidence_references' =>
                    $evidenceReferences,

                'reviewer' =>
                    $reviewer,

                'reviewer_role' =>
                    $reviewerRole,

                'activator' =>
                    $activator !== ''
                        ? $activator
                        : null,

                'activator_role' =>
                    $activatorRole !== ''
                        ? $activatorRole
                        : null,

                'activation_status' =>
                    'NOT_ACTIVE',

                'notes' =>
                    $notes,

                'reason' =>
                    'Factor activation was explicitly rejected. Factor remains inactive.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVATE Result
        |--------------------------------------------------------------------------
        */

        return [
            'status' =>
                'ACTIVATION_APPROVED',

            'activation_code' =>
                'FACTOR_ACTIVATION_APPROVED',

            'decision' =>
                'ACTIVATE',

            'activated' =>
                true,

            'hs_code' =>
                $hsCode,

            'candidate_factor' =>
                round((float) $factor, 6),

            'methodology' =>
                $methodology,

            'evidence_type' =>
                $audit['evidence_type'] ?? null,

            'weight_unit' =>
                $audit['weight_unit'] ?? null,

            'evidence_count' =>
                $evidenceCount,

            'total_sample_size' =>
                $totalSampleSize,

            'evidence_references' =>
                $evidenceReferences,

            'calculation_method' =>
                $audit['calculation_method'] ?? null,

            'observed_minimum' =>
                $audit['observed_minimum'] ?? null,

            'observed_maximum' =>
                $audit['observed_maximum'] ?? null,

            'reviewer' =>
                $reviewer,

            'reviewer_role' =>
                $reviewerRole,

            'activator' =>
                $activator,

            'activator_role' =>
                $activatorRole,

            'previous_status' =>
                'APPROVED',

            'new_status' =>
                'ACTIVE',

            'activation_status' =>
                'ACTIVE',

            'notes' =>
                $notes,

            'reason' =>
                'Factor passed the explicit activation gate. Activation is approved but no database mutation is performed by preview().',
        ];
    }
}