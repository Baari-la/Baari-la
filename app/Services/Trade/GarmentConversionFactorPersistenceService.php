<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\GarmentConversionFactor;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class GarmentConversionFactorPersistenceService
{
    /**
     * Preview persistence of an activated garment conversion factor.
     *
     * IMPORTANT:
     * - Read-only.
     * - Does not modify database.
     * - Requires ACTIVATION_APPROVED.
     * - Requires ACTIVE activation status.
     * - Requires KG_PER_PCS methodology.
     * - Factor is always HS-8 specific.
     */
    public function preview(array $activation): array
    {
        $this->validateActivationPayload($activation);

        return [
            'persistence_status' => 'READY',
            'persistence_code' => 'FACTOR_PERSISTENCE_READY',

            'hs_code' =>
                trim((string) $activation['hs_code']),

            'candidate_factor' =>
                round(
                    (float) $activation['candidate_factor'],
                    6
                ),

            'methodology' =>
                'KG_PER_PCS',

            'evidence_type' =>
                $activation['evidence_type'] ?? null,

            'weight_unit' =>
                $activation['weight_unit'] ?? null,

            'evidence_count' =>
                (int) $activation['evidence_count'],

            'total_sample_size' =>
                (int) $activation['total_sample_size'],

            'evidence_references' =>
                $activation['evidence_references'],

            'calculation_method' =>
                $activation['calculation_method'] ?? null,

            'observed_minimum' =>
                $activation['observed_minimum'] ?? null,

            'observed_maximum' =>
                $activation['observed_maximum'] ?? null,

            'approval_status' =>
                'APPROVED',

            'audit_status' =>
                'READY',

            'activation_status' =>
                'ACTIVE',

            'reviewer' =>
                $activation['reviewer'] ?? null,

            'reviewer_role' =>
                $activation['reviewer_role'] ?? null,

            'activator' =>
                $activation['activator'] ?? null,

            'activator_role' =>
                $activation['activator_role'] ?? null,

            'effective_status' =>
                'ACTIVE',

            'reason' =>
                'Activated garment conversion factor is ready for database persistence.',
        ];
    }

    /**
     * Persist an activated garment conversion factor.
     *
     * IMPORTANT:
     * - Performs database mutation.
     * - Uses database transaction.
     * - Factor remains HS-8 specific.
     * - Existing ACTIVE factor for the same HS-8 and methodology
     *   is rejected in v1 to prevent silent replacement.
     *
     * @throws Throwable
     */
    public function persist(array $activation): GarmentConversionFactor
    {
        $this->validateActivationPayload($activation);

        return DB::transaction(function () use ($activation) {
            $hsCode = trim(
                (string) $activation['hs_code']
            );

            $methodology = 'KG_PER_PCS';

            /*
            |--------------------------------------------------------------------------
            | Duplicate Active Factor Guard
            |--------------------------------------------------------------------------
            |
            | v1 does not silently replace an existing active factor.
            | Versioning/replacement policy can be added later explicitly.
            |
            */

            $existing = GarmentConversionFactor::query()
                ->where('hs_code', $hsCode)
                ->where('methodology', $methodology)
                ->where('status', 'ACTIVE')
                ->lockForUpdate()
                ->first();

            if ($existing !== null) {
                throw new InvalidArgumentException(
                    "An ACTIVE conversion factor already exists for HS-8 {$hsCode}."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Persistence
            |--------------------------------------------------------------------------
            */

            return GarmentConversionFactor::create([
                'hs_code' =>
                    $hsCode,

                'factor' =>
                    round(
                        (float) $activation['candidate_factor'],
                        6
                    ),

                'methodology' =>
                    $methodology,

                'evidence_type' =>
                    $activation['evidence_type'] ?? null,

                'weight_unit' =>
                    $activation['weight_unit'] ?? null,

                'evidence_count' =>
                    (int) $activation['evidence_count'],

                'total_sample_size' =>
                    (int) $activation['total_sample_size'],

                'calculation_method' =>
                    $activation['calculation_method'] ?? null,

                'observed_minimum' =>
                    $activation['observed_minimum'] ?? null,

                'observed_maximum' =>
                    $activation['observed_maximum'] ?? null,

                'evidence_references' =>
                    $activation['evidence_references'],

                'reviewer' =>
                    $activation['reviewer'] ?? null,

                'reviewer_role' =>
                    $activation['reviewer_role'] ?? null,

                'activator' =>
                    $activation['activator'] ?? null,

                'activator_role' =>
                    $activation['activator_role'] ?? null,

                'status' =>
                    'ACTIVE',
            ]);
        });
    }

    /**
     * Validate the complete activation payload before persistence.
     */
    private function validateActivationPayload(
        array $activation
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Activation Gate
        |--------------------------------------------------------------------------
        */

        $status = strtoupper(
            trim((string) ($activation['status'] ?? ''))
        );

        if ($status !== 'ACTIVATION_APPROVED') {
            throw new InvalidArgumentException(
                'Persistence requires ACTIVATION_APPROVED status.'
            );
        }

        $decision = strtoupper(
            trim((string) ($activation['decision'] ?? ''))
        );

        if ($decision !== 'ACTIVATE') {
            throw new InvalidArgumentException(
                'Persistence requires decision ACTIVATE.'
            );
        }

        $activated = $activation['activated'] ?? false;

        if ($activated !== true) {
            throw new InvalidArgumentException(
                'Persistence requires activated=true.'
            );
        }

        $activationStatus = strtoupper(
            trim(
                (string) (
                    $activation['activation_status']
                    ?? ''
                )
            )
        );

        if ($activationStatus !== 'ACTIVE') {
            throw new InvalidArgumentException(
                'Persistence requires ACTIVE activation status.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HS-8 Identity
        |--------------------------------------------------------------------------
        */

        $hsCode = trim(
            (string) ($activation['hs_code'] ?? '')
        );

        if ($hsCode === '') {
            throw new InvalidArgumentException(
                'HS-8 is required for factor persistence.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology
        |--------------------------------------------------------------------------
        */

        $methodology = strtoupper(
            trim((string) ($activation['methodology'] ?? ''))
        );

        if ($methodology !== 'KG_PER_PCS') {
            throw new InvalidArgumentException(
                'Factor persistence requires KG_PER_PCS methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Factor
        |--------------------------------------------------------------------------
        */

        $factor = $activation['candidate_factor'] ?? null;

        if (
            $factor === null
            || !is_numeric($factor)
            || (float) $factor <= 0
        ) {
            throw new InvalidArgumentException(
                'A positive candidate factor is required for persistence.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence
        |--------------------------------------------------------------------------
        */

        $evidenceCount = (int) (
            $activation['evidence_count'] ?? 0
        );

        $totalSampleSize = (int) (
            $activation['total_sample_size'] ?? 0
        );

        if ($evidenceCount < 1) {
            throw new InvalidArgumentException(
                'Persistence requires at least one evidence record.'
            );
        }

        if ($totalSampleSize < 1) {
            throw new InvalidArgumentException(
                'Persistence requires a positive total sample size.'
            );
        }

        $evidenceReferences =
            $activation['evidence_references'] ?? null;

        if (
            !is_array($evidenceReferences)
            || count($evidenceReferences) !== $evidenceCount
        ) {
            throw new InvalidArgumentException(
                'Evidence reference count must match evidence count.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Provenance
        |--------------------------------------------------------------------------
        */

        foreach ($evidenceReferences as $index => $evidence) {
            if (!is_array($evidence)) {
                throw new InvalidArgumentException(
                    "Evidence reference #{$index} must be an array."
                );
            }

            $evidenceHsCode = trim(
                (string) ($evidence['hs_code'] ?? '')
            );

            if ($evidenceHsCode !== $hsCode) {
                throw new InvalidArgumentException(
                    "Evidence reference #{$index} HS-8 does not match factor HS-8."
                );
            }

            $evidenceMethodology = strtoupper(
                trim(
                    (string) (
                        $evidence['methodology']
                        ?? ''
                    )
                )
            );

            if ($evidenceMethodology !== 'KG_PER_PCS') {
                throw new InvalidArgumentException(
                    "Evidence reference #{$index} must use KG_PER_PCS methodology."
                );
            }

            $validationStatus = strtoupper(
                trim(
                    (string) (
                        $evidence['validation_status']
                        ?? ''
                    )
                )
            );

            if ($validationStatus !== 'VALIDATED') {
                throw new InvalidArgumentException(
                    "Evidence reference #{$index} is not VALIDATED."
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Separation of Duties
        |--------------------------------------------------------------------------
        */

        $reviewer = trim(
            (string) ($activation['reviewer'] ?? '')
        );

        $activator = trim(
            (string) ($activation['activator'] ?? '')
        );

        if ($reviewer === '') {
            throw new InvalidArgumentException(
                'Reviewer is required for persistence.'
            );
        }

        if ($activator === '') {
            throw new InvalidArgumentException(
                'Activator is required for persistence.'
            );
        }

        if (
            mb_strtolower($reviewer)
            === mb_strtolower($activator)
        ) {
            throw new InvalidArgumentException(
                'Persistence requires separation between reviewer and activator.'
            );
        }
    }
}