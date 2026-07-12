<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

class BusinessEcosystemService
{
    /**
     * ==========================================================================
     * DIGESTEX CORE
     * ==========================================================================
     * Business Ecosystem Service
     * ==========================================================================
     *
     * Converts a Business Role into a complete Business Ecosystem.
     *
     * This service reads the DIGESTEX Textile Knowledge Graph stored in:
     *
     * config/textile_ecosystem.php
     *
     * Used by:
     *
     * • Smart Business Matching
     * • Build My Supply Chain™
     * • Executive AI
     * • Buyer Discovery
     * • RFQ Intelligence
     *
     * This service DOES NOT search companies.
     * It only defines the ecosystem surrounding a business role.
     *
     * Version : 1.0
     */

    /**
     * Resolve Business Ecosystem.
     */
    public function resolve(
        string $role
    ): array {

        $ecosystem = config(
            "textile_ecosystem.$role"
        );

        if (!$ecosystem) {

            return [];

        }

        return [

            'role' => $role,

            'name' => $ecosystem['name']
                ?? ucfirst($role),

            'upstream' => $this->expandRoles(
                $ecosystem['upstream'] ?? []
            ),

            'downstream' => $this->expandRoles(
                $ecosystem['downstream'] ?? []
            ),

            'needs' => $this->buildNeeds(
                $ecosystem['needs'] ?? []
            ),

            'supporting' => $this->buildSupporting(),

        ];

    }

    /**
     * --------------------------------------------------------------------------
     * Expand Upstream / Downstream Roles
     * --------------------------------------------------------------------------
     */
    protected function expandRoles(
        array $roles
    ): array {

        return collect($roles)

            ->map(function ($role) {

                $item = config(
                    "textile_ecosystem.$role"
                );

                return [

                    'key' => $role,

                    'name' => $item['name']
                        ?? ucfirst($role),

                ];

            })

            ->values()

            ->all();

    }

    /**
     * --------------------------------------------------------------------------
     * Build Business Needs
     * --------------------------------------------------------------------------
     */
    protected function buildNeeds(
        array $needs
    ): array {

        return collect($needs)

            ->values()

            ->map(function ($need, $index) {

                return [

                    'key' => $need,

                    'title' => $this->title($need),

                    'priority' => $this->priority($index),

                    'description' => $this->description($need),

                    /*
                     * Filled later by CompanyMatchingService
                     */
                    'partners' => [],

                ];

            })

            ->all();

    }

    /**
     * --------------------------------------------------------------------------
     * Supporting Ecosystem
     * --------------------------------------------------------------------------
     */
    protected function buildSupporting(): array
    {
        return collect(

            config(
                'textile_ecosystem.supporting',
                []
            )

        )

        ->map(function ($item) {

            return [

                'key' => $item,

                'title' => $this->title($item),

            ];

        })

        ->values()

        ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Priority Level
     * --------------------------------------------------------------------------
     */
    protected function priority(
        int $index
    ): string {

        return match (true) {

            $index <= 2 => 'High',

            $index <= 5 => 'Medium',

            default => 'Standard',

        };

    }

    /**
     * --------------------------------------------------------------------------
     * Display Title
     * --------------------------------------------------------------------------
     */
    protected function title(
        string $key
    ): string {

        return match ($key) {

            'fabric' =>
                'Fabric Suppliers',

            'thread' =>
                'Sewing Thread',

            'accessories' =>
                'Accessories',

            'packaging' =>
                'Packaging Suppliers',

            'machinery' =>
                'Machinery & Equipment',

            'technology' =>
                'Technology Solutions',

            'testing' =>
                'Testing & Certification',

            'inspection' =>
                'Inspection Services',

            'laboratory' =>
                'Laboratory Services',

            'logistics' =>
                'Logistics Partners',

            'warehouse' =>
                'Warehouse Services',

            'buyers' =>
                'Potential Buyers',

            'chemicals' =>
                'Chemical Suppliers',

            'auxiliaries' =>
                'Chemical Auxiliaries',

            'energy' =>
                'Energy Solutions',

            'wastewater' =>
                'Wastewater Treatment',

            'printing' =>
                'Printing Services',

            'embroidery' =>
                'Embroidery Services',

            'needles' =>
                'Knitting Needles',

            'looms' =>
                'Weaving Machinery',

            'sizing' =>
                'Sizing Solutions',

            'specialty_fiber' =>
                'Specialty Fiber Suppliers',

            default =>
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $key
                    )
                ),

        };

    }

    /**
     * --------------------------------------------------------------------------
     * Description
     * --------------------------------------------------------------------------
     */
    protected function description(
        string $key
    ): string {

        return match ($key) {

            'fabric' =>
                'Recommended fabric manufacturers and suppliers.',

            'thread' =>
                'Industrial sewing thread suppliers.',

            'accessories' =>
                'Buttons, zippers, elastics, labels and trims.',

            'machinery' =>
                'Production machinery and equipment providers.',

            'technology' =>
                'ERP, PLM, MES, CAD/CAM and digital solutions.',

            'testing' =>
                'Testing laboratories and certification bodies.',

            'inspection' =>
                'Inspection and quality assurance services.',

            'logistics' =>
                'Domestic and international logistics providers.',

            'buyers' =>
                'Potential buyers, brands and sourcing offices.',

            default =>
                '',

        };

    }
}