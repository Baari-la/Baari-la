<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Business Role → Technology Edge Builder
 * ==========================================================================
 *
 * Creates relationships between Business Roles and Technologies.
 *
 * Example:
 *
 * Spinner
 *      │
 *      ├── uses ─────────► Ring Spinning
 *      ├── uses ─────────► Compact Spinning
 *      ├── uses ─────────► Open End
 *      └── uses ─────────► Auto Winding
 *
 * Garment Manufacturer
 *      │
 *      ├── uses ─────────► CAD
 *      ├── uses ─────────► PLM
 *      ├── uses ─────────► MES
 *      └── uses ─────────► Digital Printing
 *
 */

class BusinessRoleTechnologyEdgeBuilder extends AbstractEdgeBuilder
{
    /**
     * Relationship name.
     */
    protected function relationship(): string
    {
        return 'uses';
    }

    /**
     * Default relationship weight.
     */
    protected function weight(): float
    {
        return 0.95;
    }

    /**
     * Confidence.
     */
    protected function confidence(): float
    {
        return 100;
    }

    /**
     * Build relationships.
     *
     * Expected source:
     *
     * [
     *     'id' => 'garment_manufacturer',
     *     'technologies' => [
     *          'cad',
     *          'plm',
     *          'mes',
     *          'digital_printing'
     *     ]
     * ]
     */
    public function build(mixed $source): iterable
    {
        $technologies = $source['technologies'] ?? [];

        if (empty($technologies)) {
            return [];
        }

        return $this->edges(

            from: $source['id'],

            targets: $technologies,

            resolver: fn (string $technology) => $technology

        );
    }
}