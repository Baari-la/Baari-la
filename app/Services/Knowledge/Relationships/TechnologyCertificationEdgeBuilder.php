<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Relationships;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Technology → Certification Edge Builder
 * ==========================================================================
 *
 * Example:
 *
 * Digital Printing
 *      │
 *      ├── validated_by ─────► OEKO-TEX
 *      ├── validated_by ─────► GOTS
 *      └── validated_by ─────► GRS
 *
 */

class TechnologyCertificationEdgeBuilder extends AbstractEdgeBuilder
{
    protected function relationship(): string
    {
        return 'validated_by';
    }

    protected function weight(): float
    {
        return 0.94;
    }

    protected function confidence(): float
    {
        return 98;
    }

    /**
     * Expected source:
     *
     * [
     *      'id' => 'digital_printing',
     *      'certifications' => [
     *          'oeko_tex',
     *          'gots',
     *          'grs'
     *      ]
     * ]
     */
    public function build(mixed $source): iterable
    {
        $certifications = $source['certifications'] ?? [];

        if (empty($certifications)) {
            return [];
        }

        $edges = [];

        foreach ($certifications as $certification) {

            $metadata = [];

            if (is_array($certification)) {

                $metadata = [

                    'required' => $certification['required'] ?? true,

                    'compliance_level'
                        => $certification['compliance_level'] ?? 'recommended',

                    'notes'
                        => $certification['notes'] ?? null,

                ];

                $certification = $certification['id'];
            }

            $edges[] = $this->edge(

                from: $source['id'],

                to: $certification,

                metadata: $metadata

            );
        }

        return $edges;
    }
}