<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use App\Services\Trade\TradeConversionMethodologyService;
use Illuminate\Console\Command;

class AuditGarmentConversionFactorValidation extends Command
{
    protected $signature =
        'digestex:audit-garment-conversion-factor-validation';

    protected $description =
        'Audit validation readiness for Garment HS-8 conversion factors v1.';

    public function __construct(
        protected TradeConversionMethodologyService $methodologyService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $rows = TradeUnitClassification::query()
            ->where('sector', 'garment')
            ->where('status', 'active')
            ->select([
                'hs_code',
                'hs_description',
                'product_group',
                'product_type',
                'intelligence_unit',
                'conversion_enabled',
                'conversion_factor',
                'conversion_method',
                'conversion_source',
                'conversion_confidence',
            ])
            ->orderBy('hs_code')
            ->get();

        $this->info(
            'DIGESTEX Garment HS-8 Conversion Factor Validation v1'
        );

        $this->newLine();

        $total = $rows->count();

        $this->line("Total HS-8: {$total}");

        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        if ($total !== 352) {
            $this->error(
                'Safety check failed: expected exactly 352 Garment HS-8 records.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $results = [];

        foreach ($rows as $row) {
            $methodology = $this->methodologyService->resolve(
                (string) $row->hs_code,
                (string) $row->hs_description,
                strtoupper((string) $row->intelligence_unit),
                (string) $row->product_group,
                (string) $row->product_type
            );

            $validation = $this->validateFactor(
                $methodology,
                $row
            );

            $results[] = [
                'hs_code' => $row->hs_code,

                'unit' => strtoupper(
                    (string) $row->intelligence_unit
                ),

                'sub_group' =>
                    $methodology['sub_group'] ?? 'UNRESOLVED',

                'methodology' =>
                    $methodology['methodology'] ?? 'UNKNOWN',

                'evidence_type' =>
                    $validation['evidence_type'],

                'factor_status' =>
                    $validation['status'],

                'factor' =>
                    $row->conversion_factor,

                'source' =>
                    $row->conversion_source,

                'confidence' =>
                    $row->conversion_confidence,

                'reason' =>
                    $validation['reason'],

                'enabled' =>
                    (bool) $row->conversion_enabled,
            ];
        }

        $collection = collect($results);

        /*
        |--------------------------------------------------------------------------
        | STATUS SUMMARY
        |--------------------------------------------------------------------------
        */

        $statusOrder = [
            'APPROVED' => 1,
            'REVIEW' => 2,
            'REJECTED' => 3,
            'BLOCKED' => 4,
        ];

        $summary = $collection
            ->groupBy('factor_status')
            ->map(
                fn ($items, $status) => [
                    'status' => $status,
                    'hs8' => $items->count(),
                ]
            )
            ->sortBy(
                fn ($row) =>
                    $statusOrder[$row['status']] ?? 99
            )
            ->values()
            ->toArray();

        $this->newLine();

        $this->table(
            [
                'Factor Validation Status',
                'HS-8',
            ],
            $summary
        );

        /*
        |--------------------------------------------------------------------------
        | EVIDENCE TYPE SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Factor Validation Evidence Summary'
        );

        $evidenceSummary = $collection
            ->groupBy('evidence_type')
            ->map(
                fn ($items, $type) => [
                    'evidence_type' => $type,
                    'hs8' => $items->count(),
                    'status' => $items
                        ->groupBy('factor_status')
                        ->map(
                            fn ($x) => $x->count()
                        )
                        ->map(
                            fn ($count, $status) =>
                                "{$status}: {$count}"
                        )
                        ->implode(' | '),
                ]
            )
            ->sortByDesc('hs8')
            ->values()
            ->toArray();

        $this->table(
            [
                'Evidence Type',
                'HS-8',
                'Status',
            ],
            $evidenceSummary
        );

        /*
        |--------------------------------------------------------------------------
        | APPROVED
        |--------------------------------------------------------------------------
        */

        $approved = $collection
            ->where('factor_status', 'APPROVED')
            ->values();

        if ($approved->isNotEmpty()) {
            $this->newLine();

            $this->info(
                'APPROVED — Factor Ready for Controlled Activation'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Factor',
                    'Source',
                    'Confidence',
                ],
                $approved
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['factor'] ?? '-',
                            $row['source'] ?? '-',
                            $row['confidence'] ?? '-',
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REVIEW
        |--------------------------------------------------------------------------
        */

        $review = $collection
            ->where('factor_status', 'REVIEW')
            ->values();

        if ($review->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'REVIEW — Factor Evidence / Validation Required'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Evidence Type',
                    'Reason',
                ],
                $review
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['evidence_type'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REJECTED
        |--------------------------------------------------------------------------
        */

        $rejected = $collection
            ->where('factor_status', 'REJECTED')
            ->values();

        if ($rejected->isNotEmpty()) {
            $this->newLine();

            $this->error(
                'REJECTED — Factor Cannot Be Used'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Evidence Type',
                    'Reason',
                ],
                $rejected
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['evidence_type'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BLOCKED
        |--------------------------------------------------------------------------
        */

        $blocked = $collection
            ->where('factor_status', 'BLOCKED')
            ->values();

        if ($blocked->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'BLOCKED — Automatic Conversion Prohibited'
            );

            $this->table(
                [
                    'HS-8',
                    'Unit',
                    'Sub-Group',
                    'Methodology',
                    'Reason',
                ],
                $blocked
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['unit'],
                            $row['sub_group'],
                            $row['methodology'],
                            $row['reason'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | EXISTING FACTOR SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        $existingFactors = $collection
            ->filter(
                fn ($row) =>
                    $row['factor'] !== null
            )
            ->values();

        if ($existingFactors->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'EXISTING CONVERSION FACTORS DETECTED'
            );

            $this->table(
                [
                    'HS-8',
                    'Factor',
                    'Source',
                    'Confidence',
                    'Enabled',
                ],
                $existingFactors
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['factor'],
                            $row['source'] ?? '-',
                            $row['confidence'] ?? '-',
                            $row['enabled']
                                ? 'YES'
                                : 'NO',
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAFETY RULE:
        | APPROVED CANNOT EXIST WITHOUT AN ACTUAL FACTOR
        |--------------------------------------------------------------------------
        */

        $invalidApproved = $collection
            ->filter(
                fn ($row) =>
                    $row['factor_status'] === 'APPROVED'
                    && (
                        $row['factor'] === null
                        || !is_numeric($row['factor'])
                        || (float) $row['factor'] <= 0
                    )
            )
            ->values();

        if ($invalidApproved->isNotEmpty()) {
            $this->newLine();

            $this->error(
                'INVALID APPROVED FACTORS DETECTED'
            );

            $this->table(
                [
                    'HS-8',
                    'Methodology',
                    'Factor',
                ],
                $invalidApproved
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['methodology'],
                            $row['factor'] ?? 'NULL',
                        ]
                    )
                    ->toArray()
            );

            $this->error(
                'No factor may be approved without a positive validated factor.'
            );

            $this->info(
                'No database records were modified.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL RESULT
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Conversion Factor Validation v1 COMPLETED.'
        );

        $this->info(
            'Validation was performed in READ-ONLY mode.'
        );

        $this->warn(
            'APPROVED means factor evidence is sufficient for controlled activation.'
        );

        $this->warn(
            'REVIEW means factor evidence or methodology still requires validation.'
        );

        $this->warn(
            'BLOCKED means automatic conversion must not be applied.'
        );

        $this->info(
            'No database records were modified.'
        );

        $this->info(
            'No conversion factors were assigned.'
        );

        return self::SUCCESS;
    }

    /*
    |--------------------------------------------------------------------------
    | FACTOR VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateFactor(
        array $methodology,
        TradeUnitClassification $row
    ): array {
        $method = strtoupper(
            trim(
                (string) ($methodology['methodology'] ?? '')
            )
        );

        /*
        |--------------------------------------------------------------------------
        | MIXED PRODUCT
        |--------------------------------------------------------------------------
        */

        if ($method === 'MIXED_PRODUCT') {
            return [
                'status' => 'BLOCKED',
                'evidence_type' => 'BLOCKED',
                'reason' =>
                    'Mixed product cannot receive an automatic conversion factor.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | RESIDUAL
        |--------------------------------------------------------------------------
        */

        if ($method === 'RESIDUAL') {
            return [
                'status' => 'BLOCKED',
                'evidence_type' => 'BLOCKED',
                'reason' =>
                    'Residual HS-8 is too broad for automatic factor validation.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | EXISTING FACTOR
        |--------------------------------------------------------------------------
        |
        | An existing factor is NOT automatically approved.
        |
        */

        $existingFactor = $row->conversion_factor;

        if ($existingFactor !== null) {
            return [
                'status' => 'REVIEW',
                'evidence_type' =>
                    $this->evidenceTypeForMethod($method),
                'reason' =>
                    'Existing factor detected, but source and methodology require independent validation.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PCS → KG
        |--------------------------------------------------------------------------
        */

        if ($method === 'PCS_TO_KG') {
            return [
                'status' => 'REVIEW',
                'evidence_type' =>
                    'AVERAGE_WEIGHT_PER_PIECE',
                'reason' =>
                    'No validated factor is currently stored; average weight per piece evidence is required.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PAIR → KG
        |--------------------------------------------------------------------------
        */

        if ($method === 'PAIR_TO_KG') {
            return [
                'status' => 'REVIEW',
                'evidence_type' =>
                    'AVERAGE_WEIGHT_PER_PAIR',
                'reason' =>
                    'No validated factor is currently stored; average weight per pair evidence is required.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MULTI-PIECE
        |--------------------------------------------------------------------------
        */

        if ($method === 'MULTI_PIECE') {
            return [
                'status' => 'REVIEW',
                'evidence_type' =>
                    'COMPONENT_WEIGHT_EVIDENCE',
                'reason' =>
                    'Complete-set or component-level weight evidence is required.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT SPECIFIC
        |--------------------------------------------------------------------------
        */

        if ($method === 'PRODUCT_SPECIFIC') {
            return [
                'status' => 'REVIEW',
                'evidence_type' =>
                    'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE',
                'reason' =>
                    'Product-specific weight evidence is required.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNKNOWN
        |--------------------------------------------------------------------------
        */

        return [
            'status' => 'REJECTED',
            'evidence_type' =>
                'UNKNOWN_METHODOLOGY',
            'reason' =>
                "Unsupported conversion methodology: {$method}.",
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EVIDENCE TYPE
    |--------------------------------------------------------------------------
    */

    protected function evidenceTypeForMethod(
        string $method
    ): string {
        return match ($method) {
            'PCS_TO_KG' =>
                'AVERAGE_WEIGHT_PER_PIECE',

            'PAIR_TO_KG' =>
                'AVERAGE_WEIGHT_PER_PAIR',

            'MULTI_PIECE' =>
                'COMPONENT_WEIGHT_EVIDENCE',

            'PRODUCT_SPECIFIC' =>
                'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE',

            'MIXED_PRODUCT',
            'RESIDUAL' =>
                'BLOCKED',

            default =>
                'UNKNOWN',
        };
    }
}