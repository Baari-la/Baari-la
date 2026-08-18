<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TradeUnitClassification;
use App\Services\Trade\TradeConversionMethodologyService;
use Illuminate\Console\Command;

class AuditGarmentConversionFactorEvidence extends Command
{
    protected $signature =
        'digestex:audit-garment-conversion-factor-evidence';

    protected $description =
        'Audit evidence readiness for Garment HS-8 conversion factors v1.1.';

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

                // Existing DB values are inspected only.
                // They are NOT used as the methodology source.
                'conversion_enabled',
                'conversion_factor',
                'conversion_method',
                'conversion_source',
                'conversion_confidence',
            ])
            ->orderBy('hs_code')
            ->get();

        $this->info(
            'DIGESTEX Garment HS-8 Conversion Factor Evidence Audit v1.1'
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
        | AUDIT
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

            $evidence = $this->classifyEvidence(
                $methodology
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

                'methodology_status' =>
                    $methodology['status'] ?? 'UNKNOWN',

                'evidence_type' =>
                    $evidence['evidence_type'],

                'status' =>
                    $evidence['status'],

                'reason' =>
                    $evidence['reason'],

                /*
                |--------------------------------------------------------------------------
                | EXISTING DATABASE VALUES
                |--------------------------------------------------------------------------
                |
                | These are inspection-only.
                |
                */

                'existing_factor' =>
                    $row->conversion_factor,

                'existing_method' =>
                    $row->conversion_method,

                'existing_source' =>
                    $row->conversion_source,

                'existing_confidence' =>
                    $row->conversion_confidence,

                'conversion_enabled' =>
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
            'EVIDENCE_READY' => 1,
            'EVIDENCE_REVIEW' => 2,
            'NO_DIRECT_FACTOR' => 3,
            'EXCEPTION' => 4,
        ];

        $summary = $collection
            ->groupBy('status')
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
                'Evidence Status',
                'HS-8',
            ],
            $summary
        );

        /*
        |--------------------------------------------------------------------------
        | EVIDENCE PATHWAY SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Evidence Pathway Summary'
        );

        $evidenceSummary = $collection
            ->groupBy('evidence_type')
            ->map(
                fn ($items, $evidenceType) => [
                    'evidence_type' => $evidenceType,
                    'hs8' => $items->count(),
                    'status' => $items
                        ->groupBy('status')
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
        | METHODOLOGY SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Resolved Methodology Summary'
        );

        $methodologySummary = $collection
            ->groupBy('methodology')
            ->map(
                fn ($items, $methodology) => [
                    'methodology' => $methodology,
                    'hs8' => $items->count(),
                    'status' => $items
                        ->groupBy('status')
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
                'Methodology',
                'HS-8',
                'Evidence Status',
            ],
            $methodologySummary
        );

        /*
        |--------------------------------------------------------------------------
        | EVIDENCE REVIEW
        |--------------------------------------------------------------------------
        */

        $review = $collection
            ->where('status', 'EVIDENCE_REVIEW')
            ->values();

        if ($review->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'EVIDENCE REVIEW — Factor Evidence Required'
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
        | NO DIRECT FACTOR
        |--------------------------------------------------------------------------
        */

        $blocked = $collection
            ->where('status', 'NO_DIRECT_FACTOR')
            ->values();

        if ($blocked->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'NO DIRECT FACTOR — Automatic Conversion Blocked'
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
                $blocked
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
        | EXCEPTIONS
        |--------------------------------------------------------------------------
        */

        $exceptions = $collection
            ->where('status', 'EXCEPTION')
            ->values();

        if ($exceptions->isNotEmpty()) {
            $this->newLine();

            $this->error(
                'EXCEPTION — Evidence Classification Conflict'
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
                $exceptions
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
        | EXISTING FACTOR SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        $existingFactors = $collection
            ->filter(
                fn ($row) =>
                    $row['existing_factor'] !== null
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
                    'Existing Factor',
                    'DB Method',
                    'DB Source',
                    'DB Confidence',
                    'Enabled',
                ],
                $existingFactors
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['existing_factor'],
                            $row['existing_method'] ?? '-',
                            $row['existing_source'] ?? '-',
                            $row['existing_confidence'] ?? '-',
                            $row['conversion_enabled']
                                ? 'YES'
                                : 'NO',
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | METHODOLOGY CONSISTENCY CHECK
        |--------------------------------------------------------------------------
        */

        $databaseMethodConflicts = $collection
            ->filter(
                function ($row) {
                    $dbMethod = trim(
                        (string) ($row['existing_method'] ?? '')
                    );

                    if ($dbMethod === '') {
                        return false;
                    }

                    return $dbMethod !== $row['methodology'];
                }
            )
            ->values();

        if ($databaseMethodConflicts->isNotEmpty()) {
            $this->newLine();

            $this->warn(
                'DATABASE METHODOLOGY CONFLICTS DETECTED'
            );

            $this->table(
                [
                    'HS-8',
                    'Resolved Methodology',
                    'Database Method',
                ],
                $databaseMethodConflicts
                    ->map(
                        fn ($row) => [
                            $row['hs_code'],
                            $row['methodology'],
                            $row['existing_method'],
                        ]
                    )
                    ->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL RESULT
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        if ($exceptions->isNotEmpty()) {
            $this->error(
                'Conversion Factor Evidence Audit v1.1 FAILED.'
            );

            $this->error(
                'Evidence classification conflicts require resolution.'
            );

            $this->info(
                'No database records were modified.'
            );

            $this->info(
                'No conversion factors were assigned.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Conversion Factor Evidence Audit v1.1 PASSED WITH REVIEW.'
        );

        $this->info(
            'Methodology was resolved through TradeConversionMethodologyService.'
        );

        $this->info(
            'Evidence pathways have been classified for all Garment HS-8.'
        );

        $this->warn(
            'Evidence readiness does NOT approve a conversion factor.'
        );

        $this->warn(
            'Factor approval requires a separate Factor Validation stage.'
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
    | EVIDENCE CLASSIFICATION
    |--------------------------------------------------------------------------
    */

    protected function classifyEvidence(
        array $methodology
    ): array {
        $status = strtoupper(
            trim(
                (string) ($methodology['status'] ?? '')
            )
        );

        $method = strtoupper(
            trim(
                (string) ($methodology['methodology'] ?? '')
            )
        );

        /*
        |--------------------------------------------------------------------------
        | METHODOLOGY EXCEPTION
        |--------------------------------------------------------------------------
        */

        if (
            $method === ''
            ||
            $method === 'MANUAL_REVIEW'
            ||
            $status === ''
        ) {
            return [
                'status' => 'EXCEPTION',
                'evidence_type' => 'MISSING_METHODOLOGY',
                'reason' =>
                    'No valid conversion methodology was resolved.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | BLOCKED — MIXED PRODUCT
        |--------------------------------------------------------------------------
        */

        if ($method === 'MIXED_PRODUCT') {
            return [
                'status' => 'NO_DIRECT_FACTOR',
                'evidence_type' => 'BLOCKED',
                'reason' =>
                    'Mixed product cannot receive a defensible automatic conversion factor.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | BLOCKED — RESIDUAL
        |--------------------------------------------------------------------------
        */

        if ($method === 'RESIDUAL') {
            return [
                'status' => 'NO_DIRECT_FACTOR',
                'evidence_type' => 'BLOCKED',
                'reason' =>
                    'Residual HS-8 is too broad for automatic factor assignment.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MULTI-PIECE
        |--------------------------------------------------------------------------
        */

        if ($method === 'MULTI_PIECE') {
            return [
                'status' => 'EVIDENCE_REVIEW',
                'evidence_type' =>
                    'COMPONENT_WEIGHT_EVIDENCE',
                'reason' =>
                    'Requires validated complete-set or component-level weight evidence.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT-SPECIFIC
        |--------------------------------------------------------------------------
        */

        if ($method === 'PRODUCT_SPECIFIC') {
            return [
                'status' => 'EVIDENCE_REVIEW',
                'evidence_type' =>
                    'PRODUCT_SPECIFIC_WEIGHT_EVIDENCE',
                'reason' =>
                    'Requires product-specific weight evidence before factor approval.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PAIR → KG
        |--------------------------------------------------------------------------
        */

        if ($method === 'PAIR_TO_KG') {
            return [
                'status' => 'EVIDENCE_REVIEW',
                'evidence_type' =>
                    'AVERAGE_WEIGHT_PER_PAIR',
                'reason' =>
                    'Requires validated average weight per pair.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PCS → KG
        |--------------------------------------------------------------------------
        */

        if ($method === 'PCS_TO_KG') {
            return [
                'status' => 'EVIDENCE_REVIEW',
                'evidence_type' =>
                    'AVERAGE_WEIGHT_PER_PIECE',
                'reason' =>
                    'Requires validated average weight per piece.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNKNOWN
        |--------------------------------------------------------------------------
        */

        return [
            'status' => 'EXCEPTION',
            'evidence_type' =>
                'UNKNOWN_METHODOLOGY',
            'reason' =>
                "Unsupported conversion methodology: {$method}.",
        ];
    }
}