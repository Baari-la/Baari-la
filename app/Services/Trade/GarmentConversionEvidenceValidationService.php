<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\GarmentConversionEvidence;
use InvalidArgumentException;

class GarmentConversionEvidenceValidationService
{
    /**
     * Validate one evidence record.
     *
     * IMPORTANT:
     * - Does not calculate conversion factors.
     * - Does not enable conversion.
     * - Does not approve conversion.
     * - Does not modify the database.
     *
     * Canonical methodology:
     *
     * KG_PER_PCS
     *
     * Meaning:
     *   1 PCS garment = X KG
     *
     * This factor is later used to convert
     * official trade quantity reported in KG
     * into estimated garment quantity in PCS.
     *
     * Example:
     *
     *   KG_PER_PCS = 0.193333
     *
     *   1,000 KG / 0.193333
     *   = approximately 5,172 PCS
     *
     * The returned result is a validation decision only.
     */
    public function validate(
        GarmentConversionEvidence $evidence
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Basic Registry State
        |--------------------------------------------------------------------------
        */

        if (
            strtoupper(
                trim((string) $evidence->validation_status)
            ) === 'BLOCKED'
        ) {
            return $this->blocked(
                'Evidence record is already marked BLOCKED.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Methodology
        |--------------------------------------------------------------------------
        */

        $methodology = strtoupper(
            trim((string) $evidence->methodology)
        );

        if ($methodology === '') {
            return $this->reject(
                'MISSING_METHODOLOGY',
                'Evidence record has no conversion methodology.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Automatic Conversion Block
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $methodology,
                [
                    'MIXED_PRODUCT',
                    'RESIDUAL',
                ],
                true
            )
        ) {
            return $this->blocked(
                'Methodology does not permit automatic conversion.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Supported Conversion Methodologies
        |--------------------------------------------------------------------------
        |
        | KG_PER_PCS is the canonical garment conversion methodology.
        |
        | Meaning:
        |
        |   KG_PER_PCS = average garment weight in KG per PCS.
        |
        | Other methodologies remain available for future evidence models,
        | but must not be silently interpreted as KG_PER_PCS.
        |
        */

        if (
            !in_array(
                $methodology,
                [
                    'KG_PER_PCS',
                    'PAIR_TO_KG',
                    'MULTI_PIECE',
                    'PRODUCT_SPECIFIC',
                ],
                true
            )
        ) {
            return $this->review(
                'UNSUPPORTED_METHODOLOGY',
                "Methodology {$methodology} is not supported by the current garment conversion evidence validator."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evidence Type Compatibility
        |--------------------------------------------------------------------------
        */

        $evidenceType = strtoupper(
            trim((string) $evidence->evidence_type)
        );

        if (
            !$this->isEvidenceTypeCompatible(
                $methodology,
                $evidenceType
            )
        ) {
            return $this->reject(
                'EVIDENCE_TYPE_MISMATCH',
                "Evidence type {$evidenceType} is not compatible with methodology {$methodology}."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Required Source Evidence
        |--------------------------------------------------------------------------
        */

        if (
            trim((string) $evidence->source_type) === ''
        ) {
            return $this->review(
                'MISSING_SOURCE_TYPE',
                'Evidence source type is required.'
            );
        }

        if (
            trim((string) $evidence->source_reference) === ''
        ) {
            return $this->review(
                'MISSING_SOURCE_REFERENCE',
                'Evidence source reference is required.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Quantitative Evidence
        |--------------------------------------------------------------------------
        */

        $quantitative = $this->validateQuantitativeEvidence(
            $evidence
        );

        if ($quantitative !== null) {
            return $quantitative;
        }

        /*
        |--------------------------------------------------------------------------
        | Product Context
        |--------------------------------------------------------------------------
        */

        $context = $this->validateProductContext(
            $evidence
        );

        if ($context !== null) {
            return $context;
        }

        /*
        |--------------------------------------------------------------------------
        | Source Quality
        |--------------------------------------------------------------------------
        */

        $sourceQuality = $this->evaluateSourceQuality(
            $evidence
        );

        /*
        |--------------------------------------------------------------------------
        | Final Validation Decision
        |--------------------------------------------------------------------------
        */

        if ($sourceQuality['status'] !== 'VALIDATED') {
            return $sourceQuality;
        }

        return [
            'status' => 'VALIDATED',

            'validation_code' =>
                'EVIDENCE_VALIDATED',

            'confidence_level' =>
                $sourceQuality['confidence_level'],

            'reason' =>
                'Evidence satisfies the current structural, quantitative, product-context, and source requirements.',

            'factor_eligible' => true,

            /*
            |--------------------------------------------------------------------------
            | Factor Is Not Calculated Here
            |--------------------------------------------------------------------------
            */

            'conversion_factor' => null,
        ];
    }

    /**
     * Validate and return a decision without persisting it.
     */
    public function preview(
        GarmentConversionEvidence $evidence
    ): array {
        return $this->validate($evidence);
    }

    /*
    |--------------------------------------------------------------------------
    | Evidence Type Compatibility
    |--------------------------------------------------------------------------
    */

    protected function isEvidenceTypeCompatible(
        string $methodology,
        string $evidenceType
    ): bool {
        $allowed = match ($methodology) {
            /*
            |--------------------------------------------------------------------------
            | Canonical Garment Conversion
            |--------------------------------------------------------------------------
            |
            | AVERAGE_WEIGHT_PER_PIECE means:
            |
            |   average KG of one garment piece.
            |
            | Therefore:
            |
            |   KG_PER_PCS
            |
            */

            'KG_PER_PCS' => [
                'AVERAGE_WEIGHT_PER_PIECE',
            ],

            /*
            |--------------------------------------------------------------------------
            | Pair-based conversion
            |--------------------------------------------------------------------------
            */

            'PAIR_TO_KG' => [
                'AVERAGE_WEIGHT_PER_PAIR',
            ],

            /*
            |--------------------------------------------------------------------------
            | Multi-piece products
            |--------------------------------------------------------------------------
            */

            'MULTI_PIECE' => [
                'COMPONENT_WEIGHT_EVIDENCE',
            ],

            /*
            |--------------------------------------------------------------------------
            | Product-specific evidence
            |--------------------------------------------------------------------------
            */

            'PRODUCT_SPECIFIC' => [
                'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE',
            ],

            default => [],
        };

        return in_array(
            $evidenceType,
            $allowed,
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Quantitative Evidence
    |--------------------------------------------------------------------------
    */

    protected function validateQuantitativeEvidence(
        GarmentConversionEvidence $evidence
    ): ?array {
        $sampleSize = $evidence->sample_size;

        $average = $evidence->average_weight;
        $minimum = $evidence->minimum_weight;
        $maximum = $evidence->maximum_weight;

        /*
        |--------------------------------------------------------------------------
        | Sample Size
        |--------------------------------------------------------------------------
        */

        if (
            $sampleSize === null
            || (int) $sampleSize < 1
        ) {
            return $this->review(
                'INVALID_SAMPLE_SIZE',
                'Quantitative evidence requires a positive sample size.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Average Weight
        |--------------------------------------------------------------------------
        */

        if (
            $average === null
            || !is_numeric($average)
            || (float) $average <= 0
        ) {
            return $this->review(
                'INVALID_AVERAGE_WEIGHT',
                'Average weight must be a positive numeric value.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Weight Unit
        |--------------------------------------------------------------------------
        */

        if (
            strtoupper(
                trim((string) $evidence->weight_unit)
            ) !== 'KG'
        ) {
            return $this->review(
                'INVALID_WEIGHT_UNIT',
                'Conversion evidence weight unit must be KG.'
            );
        }

        $average = (float) $average;

        /*
        |--------------------------------------------------------------------------
        | Minimum
        |--------------------------------------------------------------------------
        */

        if ($minimum !== null) {
            if (
                !is_numeric($minimum)
                || (float) $minimum <= 0
            ) {
                return $this->review(
                    'INVALID_MINIMUM_WEIGHT',
                    'Minimum weight must be a positive numeric value.'
                );
            }

            if ((float) $minimum > $average) {
                return $this->review(
                    'INVALID_WEIGHT_RANGE',
                    'Minimum weight cannot exceed average weight.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum
        |--------------------------------------------------------------------------
        */

        if ($maximum !== null) {
            if (
                !is_numeric($maximum)
                || (float) $maximum <= 0
            ) {
                return $this->review(
                    'INVALID_MAXIMUM_WEIGHT',
                    'Maximum weight must be a positive numeric value.'
                );
            }

            if ((float) $maximum < $average) {
                return $this->review(
                    'INVALID_WEIGHT_RANGE',
                    'Maximum weight cannot be lower than average weight.'
                );
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Product Context
    |--------------------------------------------------------------------------
    */

    protected function validateProductContext(
        GarmentConversionEvidence $evidence
    ): ?array {
        $methodology = strtoupper(
            trim((string) $evidence->methodology)
        );

        /*
        |--------------------------------------------------------------------------
        | PRODUCT SPECIFIC
        |--------------------------------------------------------------------------
        */

        if (
            $methodology === 'PRODUCT_SPECIFIC'
            && trim(
                (string) $evidence->product_specification
            ) === ''
        ) {
            return $this->review(
                'MISSING_PRODUCT_SPECIFICATION',
                'PRODUCT_SPECIFIC evidence requires a product specification.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MATERIAL CONTEXT
        |--------------------------------------------------------------------------
        |
        | Material composition is strongly preferred but is not universally
        | mandatory because some evidence may originate from product-level
        | specifications where composition is documented separately.
        |
        */

        if (
            $methodology === 'PRODUCT_SPECIFIC'
            && trim(
                (string) $evidence->material_composition
            ) === ''
        ) {
            return $this->review(
                'MISSING_MATERIAL_CONTEXT',
                'PRODUCT_SPECIFIC evidence requires material composition or equivalent material context.'
            );
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Source Quality
    |--------------------------------------------------------------------------
    */

    protected function evaluateSourceQuality(
        GarmentConversionEvidence $evidence
    ): array {
        $sourceType = strtoupper(
            trim((string) $evidence->source_type)
        );

        /*
        |--------------------------------------------------------------------------
        | Strong Sources
        |--------------------------------------------------------------------------
        */

        $strongSources = [
            'INTERNAL_SAMPLE',
            'FACTORY_PRODUCTION_DATA',
            'LABORATORY_MEASUREMENT',
            'CERTIFIED_TEST_REPORT',
            'VERIFIED_SUPPLIER_DATA',
        ];

        /*
        |--------------------------------------------------------------------------
        | Medium Sources
        |--------------------------------------------------------------------------
        */

        $mediumSources = [
            'CUSTOMER_SPECIFICATION',
            'BUYER_SPECIFICATION',
            'TECHNICAL_SPECIFICATION',
            'INDUSTRY_DATABASE',
        ];

        /*
        |--------------------------------------------------------------------------
        | Weak / Reference Sources
        |--------------------------------------------------------------------------
        */

        $referenceSources = [
            'PUBLIC_REFERENCE',
            'LITERATURE',
            'BENCHMARK',
            'ESTIMATE',
        ];

        /*
        |--------------------------------------------------------------------------
        | Strong Source
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $sourceType,
                $strongSources,
                true
            )
        ) {
            return [
                'status' => 'VALIDATED',

                'confidence_level' => 'HIGH',

                'validation_code' =>
                    'STRONG_SOURCE',

                'reason' =>
                    'Source type is considered strong enough for evidence validation, subject to the recorded measurement context.',

                'factor_eligible' => true,

                'conversion_factor' => null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Medium Source
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $sourceType,
                $mediumSources,
                true
            )
        ) {
            return [
                'status' => 'VALIDATED',

                'confidence_level' => 'MEDIUM',

                'validation_code' =>
                    'MEDIUM_SOURCE',

                'reason' =>
                    'Source is usable as evidence but should retain medium confidence pending stronger corroboration.',

                'factor_eligible' => true,

                'conversion_factor' => null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Reference Source
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $sourceType,
                $referenceSources,
                true
            )
        ) {
            return $this->review(
                'REFERENCE_SOURCE_ONLY',
                'Reference or benchmark evidence is not sufficient by itself for factor approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Source
        |--------------------------------------------------------------------------
        */

        return $this->review(
            'UNKNOWN_SOURCE_TYPE',
            "Source type {$sourceType} is not classified in the current evidence hierarchy."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Result Builders
    |--------------------------------------------------------------------------
    */

    protected function validated(
        string $code,
        string $reason,
        string $confidence = 'HIGH'
    ): array {
        return [
            'status' => 'VALIDATED',

            'validation_code' => $code,

            'confidence_level' => $confidence,

            'reason' => $reason,

            'factor_eligible' => true,

            'conversion_factor' => null,
        ];
    }

    protected function review(
        string $code,
        string $reason
    ): array {
        return [
            'status' => 'REVIEW',

            'validation_code' => $code,

            'confidence_level' => null,

            'reason' => $reason,

            'factor_eligible' => false,

            'conversion_factor' => null,
        ];
    }

    protected function reject(
        string $code,
        string $reason
    ): array {
        return [
            'status' => 'REJECTED',

            'validation_code' => $code,

            'confidence_level' => null,

            'reason' => $reason,

            'factor_eligible' => false,

            'conversion_factor' => null,
        ];
    }

    protected function blocked(
        string $reason
    ): array {
        return [
            'status' => 'BLOCKED',

            'validation_code' =>
                'AUTOMATIC_CONVERSION_BLOCKED',

            'confidence_level' => null,

            'reason' => $reason,

            'factor_eligible' => false,

            'conversion_factor' => null,
        ];
    }
}