<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Product → Technology Edge Builder
 * ==========================================================================
 *
 * Creates relationships between Products and Technologies.
 *
 * Example:
 *
 * Cotton Yarn
 *      │
 *      ├── manufactured_using ───► Ring Spinning
 *      ├── manufactured_using ───► Compact Spinning
 *      └── manufactured_using ───► Auto Winding
 *
 * Sportswear
 *      │
 *      ├── manufactured_using ───► CAD
 *      ├── manufactured_using ───► Digital Printing
 *      ├── manufactured_using ───► Seam Sealing
 *      └── manufactured_using ───► RFID
 *
 */

class ProductTechnologyEdgeBuilder extends AbstractEdgeBuilder
{
    /**
     * Relationship.
     */
    protected function relationship(): string
    {
        return 'manufactured_using';
    }

    /**
     * Weight.
     */
    protected function weight(): float
    {
        return 0.97;
    }

    /**
     * Confidence.
     */
    protected function confidence(): float
    {
        return 100;
    }

    /**
     * Build Product → Technology edges.
     *
     * Expected source:
     *
     * [
     *     'id' => 'sportswear',
     *     'technologies' => [
     *         'cad',
     *         'digital_printing',
     *         'seam_sealing'
     *     ]
     * ]
     */
    public function build(mixed $source): iterable
    {
        $technologies = $source['technologies'] ?? [];

        if (empty($technologies)) {
            return [];
        }

        $edges = [];

        foreach ($technologies as $technology) {

            $metadata = [];

            if (is_array($technology)) {

                $metadata = [

                    'required' => $technology['required'] ?? true,

                    'production_stage'
                        => $technology['production_stage'] ?? null,

                    'machine_role'
                        => $technology['machine_role'] ?? 'primary',

                    'automation_level'
                        => $technology['automation_level'] ?? null,

                    'notes'
                        => $technology['notes'] ?? null,

                ];

                $technology = $technology['id'];
            }

            $edges[] = $this->edge(

                from: $source['id'],

                to: $technology,

                metadata: $metadata

            );
        }

        return $edges;
    }
}