<?php

declare(strict_types=1);

namespace App\Services\Trade\Executive;

class SectorOverviewService
{
    /**
     * --------------------------------------------------------------------------
     * Build Sector Overview
     * --------------------------------------------------------------------------
     */
    public function build(
        string $sector = 'textile'
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Textile (Master Value Chain)
        |--------------------------------------------------------------------------
        */

        if ($sector === 'textile') {

            return [

                'slug' => 'textile',

                'type' => 'master',

                'title' => 'Textile',

                'icon' => '🌐',

                'description' =>

                    'Complete textile value chain from upstream to downstream.',

                'hs' => [

                    '50',
                    '51',
                    '52',
                    '53',
                    '54',
                    '55',
                    '56',
                    '57',
                    '58',
                    '59',
                    '60',
                    '61',
                    '62',
                    '63',
                ],

                'total_hs' => 14,

                'has_children' => true,

                'upstream' => [

                    [
                        'slug' => 'fiber',
                        'title' => 'Fiber',
                        'icon' => '🌾',
                    ],

                    [
                        'slug' => 'yarn',
                        'title' => 'Yarn',
                        'icon' => '🧵',
                    ],

                ],

                'midstream' => [

                    [
                        'slug' => 'fabric',
                        'title' => 'Fabric',
                        'icon' => '🧶',
                    ],

                ],

                'downstream' => [

                    [
                        'slug' => 'apparel',
                        'title' => 'Apparel',
                        'icon' => '👔',
                    ],

                ],

                'ai_insight' =>

                    'Indonesia demonstrates stronger competitiveness in downstream textile products while maintaining strategic dependence on imported upstream materials.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Sector Taxonomy
        |--------------------------------------------------------------------------
        */

        $taxonomy = config(
            "textile_taxonomy.{$sector}"
        );

        if (! $taxonomy) {
            return [];
        }

        return [

            'slug' =>

                $taxonomy['slug']
                ?? $sector,

            'type' => 'sector',

            'title' =>

                $taxonomy['title_en']
                ?? ucfirst($sector),

            'icon' =>

                $taxonomy['icon']
                ?? '📦',

            'description' =>

                $taxonomy['description_en']
                ?? null,

            'hs' =>

                $taxonomy['hs']
                ?? [],

            'total_hs' => count(
                $taxonomy['hs']
                ?? []
            ),

            'has_children' =>

                ! empty(
                    $taxonomy['children']
                ),

            'children' =>

                $this->children(
                    $taxonomy
                ),

            'ai_insight' =>

                $this->insight(
                    $sector
                ),
        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Build Children
     * --------------------------------------------------------------------------
     */
    protected function children(
        array $taxonomy
    ): array {

        $children = [];

        foreach (
            $taxonomy['children'] ?? []
            as $key => $child
        ) {

            if (is_array($child)) {

                $children[] = [

                    'slug' =>

                        $child['slug']
                        ?? $key,

                    'title' =>

                        $child['title_en']
                        ?? ucfirst($key),

                ];

                continue;
            }

            $children[] = [

                'slug' => $child,

                'title' => str($child)

                    ->replace(
                        '_',
                        ' '
                    )

                    ->title()

                    ->toString(),
            ];
        }

        return $children;
    }

    /**
     * --------------------------------------------------------------------------
     * AI Insight
     * --------------------------------------------------------------------------
     */
    protected function insight(
        string $sector
    ): string {

        return match ($sector) {

            'fiber' =>

                'Indonesia remains dependent on imported raw materials, creating opportunities for upstream investment.',

            'yarn' =>

                'The yarn sector plays a strategic role in connecting upstream and downstream textile industries.',

            'fabric' =>

                'Fabric manufacturing continues to serve as a key midstream component of Indonesia\'s textile ecosystem.',

            'apparel' =>

                'Apparel remains Indonesia\'s strongest export-oriented textile segment with broad international market access.',

            'home_textile' =>

                'Home textile products continue to benefit from global demand driven by hospitality, residential, and lifestyle markets.',

            'technical_textile' =>

                'Technical textiles represent a high-value segment with applications across automotive, healthcare, defense, and infrastructure industries.',

            'machinery' =>

                'Modern textile machinery investment remains critical for improving productivity and industrial competitiveness.',

            'chemicals' =>

                'Textile chemicals play a strategic role in supporting quality, sustainability, and manufacturing efficiency.',

            'accessories' =>

                'Garment accessories remain an important supporting industry within the broader textile ecosystem.',

            default =>

                'This sector plays an important role within Indonesia\'s textile ecosystem.',
        };
    }
}