<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\GarmentConversionEvidence;
use App\Models\TradeUnitClassification;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use RuntimeException;

class GarmentConversionEvidenceRegistryService
{
    public function __construct(
        protected TradeConversionMethodologyService $methodologyService
    ) {
    }

    /**
     * Register new conversion evidence.
     *
     * IMPORTANT:
     * - Does not calculate conversion factors.
     * - Does not enable conversion.
     * - Does not automatically validate evidence.
     * - New evidence always enters as PENDING.
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function register(
        string $hsCode,
        array $evidence
    ): GarmentConversionEvidence {
        $classification = TradeUnitClassification::query()
            ->where('hs_code', trim($hsCode))
            ->first();

        if (!$classification) {
            throw new InvalidArgumentException(
                "HS-8 {$hsCode} was not found in TradeUnitClassification."
            );
        }

        $unit = strtoupper(
            trim((string) $classification->intelligence_unit)
        );

        $methodology = $this->methodologyService->resolve(
            (string) $classification->hs_code,
            (string) $classification->hs_description,
            $unit,
            (string) $classification->product_group,
            (string) $classification->product_type
        );

        /*
        |--------------------------------------------------------------------------
        | BLOCKED METHODOLOGY
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                strtoupper((string) $methodology['methodology']),
                [
                    'MIXED_PRODUCT',
                    'RESIDUAL',
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                "HS-8 {$hsCode} is blocked from automatic conversion evidence registration: "
                . $methodology['methodology']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Type
        |--------------------------------------------------------------------------
        */

        $evidenceType = strtoupper(
            trim(
                (string) ($evidence['evidence_type'] ?? '')
            )
        );

        $this->assertEvidenceTypeMatchesMethodology(
            $evidenceType,
            (string) $methodology['methodology']
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = Validator::make(
            $evidence,
            $this->rulesForEvidenceType($evidenceType)
        )->validate();

        /*
        |--------------------------------------------------------------------------
        | Weight Validation
        |--------------------------------------------------------------------------
        */

        $this->validateWeightEvidence(
            $unit,
            $methodology['methodology'],
            $evidenceType,
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Build Registry Record
        |--------------------------------------------------------------------------
        */

        return GarmentConversionEvidence::create([
            'hs_code' =>
                $classification->hs_code,

            'product_group' =>
                $classification->product_group,

            'product_type' =>
                $classification->product_type,

            'conversion_sub_group' =>
                $methodology['sub_group'] ?? null,

            'methodology' =>
                $methodology['methodology'] ?? null,

            'evidence_type' =>
                $evidenceType,

            'sample_size' =>
                $validated['sample_size'] ?? null,

            'average_weight' =>
                $validated['average_weight'] ?? null,

            'minimum_weight' =>
                $validated['minimum_weight'] ?? null,

            'maximum_weight' =>
                $validated['maximum_weight'] ?? null,

            'weight_unit' =>
                strtoupper(
                    (string) ($validated['weight_unit'] ?? '')
                ) ?: null,

            'material_composition' =>
                $validated['material_composition'] ?? null,

            'product_specification' =>
                $validated['product_specification'] ?? null,

            'source_type' =>
                $validated['source_type'] ?? null,

            'source_reference' =>
                $validated['source_reference'] ?? null,

            'source_date' =>
                $validated['source_date'] ?? null,

            'country' =>
                $validated['country'] ?? null,

            'market' =>
                $validated['market'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Registry Safety
            |--------------------------------------------------------------------------
            |
            | Never trust caller to set validation status.
            |
            */

            'confidence_level' => null,

            'validation_status' =>
                'PENDING',

            'reviewed_by' => null,
            'reviewed_at' => null,

            'notes' =>
                $validated['notes'] ?? null,
        ]);
    }

    /**
     * Preview evidence without inserting it.
     *
     * Useful for Tinker / command testing.
     */
    public function preview(
        string $hsCode,
        array $evidence
    ): array {
        $classification = TradeUnitClassification::query()
            ->where('hs_code', trim($hsCode))
            ->first();

        if (!$classification) {
            throw new InvalidArgumentException(
                "HS-8 {$hsCode} was not found in TradeUnitClassification."
            );
        }

        $unit = strtoupper(
            trim((string) $classification->intelligence_unit)
        );

        $methodology = $this->methodologyService->resolve(
            (string) $classification->hs_code,
            (string) $classification->hs_description,
            $unit,
            (string) $classification->product_group,
            (string) $classification->product_type
        );

        if (
            in_array(
                strtoupper((string) $methodology['methodology']),
                [
                    'MIXED_PRODUCT',
                    'RESIDUAL',
                ],
                true
            )
        ) {
            throw new InvalidArgumentException(
                "HS-8 {$hsCode} is blocked from automatic conversion evidence registration."
            );
        }

        $evidenceType = strtoupper(
            trim(
                (string) ($evidence['evidence_type'] ?? '')
            )
        );

        $this->assertEvidenceTypeMatchesMethodology(
            $evidenceType,
            (string) $methodology['methodology']
        );

        $validated = Validator::make(
            $evidence,
            $this->rulesForEvidenceType($evidenceType)
        )->validate();

        $this->validateWeightEvidence(
            $unit,
            $methodology['methodology'],
            $evidenceType,
            $validated
        );

        return [
            'hs_code' =>
                $classification->hs_code,

            'unit' =>
                $unit,

            'product_group' =>
                $classification->product_group,

            'product_type' =>
                $classification->product_type,

            'conversion_sub_group' =>
                $methodology['sub_group'] ?? null,

            'methodology' =>
                $methodology['methodology'] ?? null,

            'evidence_type' =>
                $evidenceType,

            'validation_status' =>
                'PENDING',

            'validated_payload' =>
                $validated,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Evidence Type / Methodology Compatibility
    |--------------------------------------------------------------------------
    */

    protected function assertEvidenceTypeMatchesMethodology(
        string $evidenceType,
        string $methodology
    ): void {
        $allowed = match (strtoupper($methodology)) {
            'PCS_TO_KG' => [
                'AVERAGE_WEIGHT_PER_PIECE',
            ],

            'PAIR_TO_KG' => [
                'AVERAGE_WEIGHT_PER_PAIR',
            ],

            'MULTI_PIECE' => [
                'COMPONENT_WEIGHT_EVIDENCE',
            ],

            'PRODUCT_SPECIFIC' => [
                'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE',
            ],

            default => [],
        };

        if (
            !in_array(
                $evidenceType,
                $allowed,
                true
            )
        ) {
            throw new InvalidArgumentException(
                "Evidence type {$evidenceType} is not compatible with methodology {$methodology}."
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    protected function rulesForEvidenceType(
        string $evidenceType
    ): array {
        $rules = [
            'evidence_type' => [
                'required',
                'string',
                'max:100',
            ],

            'source_type' => [
                'required',
                'string',
                'max:100',
            ],

            'source_reference' => [
                'required',
                'string',
                'max:5000',
            ],

            'source_date' => [
                'nullable',
                'date',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'market' => [
                'nullable',
                'string',
                'max:100',
            ],

            'material_composition' => [
                'nullable',
                'string',
                'max:500',
            ],

            'product_specification' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Quantitative Evidence
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $evidenceType,
                [
                    'AVERAGE_WEIGHT_PER_PIECE',
                    'AVERAGE_WEIGHT_PER_PAIR',
                    'COMPONENT_WEIGHT_EVIDENCE',
                    'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE',
                ],
                true
            )
        ) {
            $rules['sample_size'] = [
                'required',
                'integer',
                'min:1',
            ];

            $rules['average_weight'] = [
                'required',
                'numeric',
                'gt:0',
            ];

            $rules['minimum_weight'] = [
                'nullable',
                'numeric',
                'gt:0',
            ];

            $rules['maximum_weight'] = [
                'nullable',
                'numeric',
                'gt:0',
            ];

            $rules['weight_unit'] = [
                'required',
                'string',
                'max:20',
            ];
        }

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Weight Validation
    |--------------------------------------------------------------------------
    */

    protected function validateWeightEvidence(
        string $unit,
        string $methodology,
        string $evidenceType,
        array $validated
    ): void {
        if (
            !array_key_exists(
                'average_weight',
                $validated
            )
        ) {
            return;
        }

        $average = (float) $validated['average_weight'];

        $minimum = isset(
            $validated['minimum_weight']
        )
            ? (float) $validated['minimum_weight']
            : null;

        $maximum = isset(
            $validated['maximum_weight']
        )
            ? (float) $validated['maximum_weight']
            : null;

        /*
        |--------------------------------------------------------------------------
        | Weight Unit
        |--------------------------------------------------------------------------
        */

        $weightUnit = strtoupper(
            trim(
                (string) ($validated['weight_unit'] ?? '')
            )
        );

        if ($weightUnit !== 'KG') {
            throw new InvalidArgumentException(
                'Conversion evidence weight_unit must be KG.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Average Range
        |--------------------------------------------------------------------------
        */

        if (
            $minimum !== null
            && $minimum > $average
        ) {
            throw new InvalidArgumentException(
                'minimum_weight cannot exceed average_weight.'
            );
        }

        if (
            $maximum !== null
            && $maximum < $average
        ) {
            throw new InvalidArgumentException(
                'maximum_weight cannot be lower than average_weight.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Methodology / Evidence Consistency
        |--------------------------------------------------------------------------
        */

        if (
            $methodology === 'PCS_TO_KG'
            && $evidenceType !== 'AVERAGE_WEIGHT_PER_PIECE'
        ) {
            throw new InvalidArgumentException(
                'PCS_TO_KG requires AVERAGE_WEIGHT_PER_PIECE evidence.'
            );
        }

        if (
            $methodology === 'PAIR_TO_KG'
            && $evidenceType !== 'AVERAGE_WEIGHT_PER_PAIR'
        ) {
            throw new InvalidArgumentException(
                'PAIR_TO_KG requires AVERAGE_WEIGHT_PER_PAIR evidence.'
            );
        }

        if (
            $methodology === 'MULTI_PIECE'
            && $evidenceType !== 'COMPONENT_WEIGHT_EVIDENCE'
        ) {
            throw new InvalidArgumentException(
                'MULTI_PIECE requires COMPONENT_WEIGHT_EVIDENCE.'
            );
        }

        if (
            $methodology === 'PRODUCT_SPECIFIC'
            && $evidenceType !== 'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE'
        ) {
            throw new InvalidArgumentException(
                'PRODUCT_SPECIFIC requires PRODUCT_SPECIFIC_WEIGHT_EVIDENCE.'
            );
        }
    }
}