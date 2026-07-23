<?php

declare(strict_types=1);

namespace App\Services\Trade\SupplyChain;

class SupplyChainGraphService
{
    /**
     * --------------------------------------------------------------------------
     * Build Supply Chain Graph
     * --------------------------------------------------------------------------
     */
    public function build(
        array $supplyChain
    ): array {

        $nodes = [];

        $edges = [];

        /*
        |--------------------------------------------------------------------------
        | Upstream
        |--------------------------------------------------------------------------
        */

        foreach (
            $supplyChain['upstream'] ?? []
            as $item
        ) {

            $nodes[] = [

                'id' => str($item)
                    ->slug()
                    ->toString(),

                'label' => $item,

                'type' => 'upstream',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Midstream
        |--------------------------------------------------------------------------
        */

        foreach (
            $supplyChain['midstream'] ?? []
            as $item
        ) {

            $nodes[] = [

                'id' => str($item)
                    ->slug()
                    ->toString(),

                'label' => $item,

                'type' => 'midstream',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Downstream
        |--------------------------------------------------------------------------
        */

        foreach (
            $supplyChain['downstream'] ?? []
            as $item
        ) {

            $nodes[] = [

                'id' => str($item)
                    ->slug()
                    ->toString(),

                'label' => $item,

                'type' => 'downstream',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Supporting
        |--------------------------------------------------------------------------
        */

        foreach (
            $supplyChain['supporting'] ?? []
            as $item
        ) {

            $nodes[] = [

                'id' => str($item)
                    ->slug()
                    ->toString(),

                'label' => $item,

                'type' => 'supporting',
            ];
        }
        /*
        |--------------------------------------------------------------------------
        | Build Edges
        |--------------------------------------------------------------------------
        */

        $ordered = [

            ...($supplyChain['upstream']
                ?? []),

            ...($supplyChain['midstream']
                ?? []),

            ...($supplyChain['downstream']
                ?? []),
        ];

        foreach (
            $ordered as $index => $item
        ) {

            if (
                ! isset(
                    $ordered[$index + 1]
                )
            ) {

                continue;
            }

            $edges[] = [

                'source' =>

                    str($item)
                        ->slug()
                        ->toString(),

                'target' =>

                    str(
                        $ordered[
                            $index + 1
                        ]
                    )

                    ->slug()

                    ->toString(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Buyers
        |--------------------------------------------------------------------------
        */

        foreach (
            $supplyChain['buyers']
            ?? [] as $buyer
        ) {

            $nodes[] = [

                'id' => str($buyer)
                    ->slug()
                    ->toString(),

                'label' => $buyer,

                'type' => 'buyer',
            ];

            if (! empty($ordered)) {

                $edges[] = [

                    'source' =>

                        str(
                            end($ordered)
                        )

                        ->slug()

                        ->toString(),

                    'target' =>

                        str($buyer)
                            ->slug()
                            ->toString(),
                ];
            }
        }
              /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

            'product' =>

                $supplyChain['product']
                ?? null,

            'sector' =>

                $supplyChain['sector']
                ?? null,

            'nodes' =>

                collect($nodes)

                    ->unique('id')

                    ->values()

                    ->toArray(),

            'edges' =>

                $edges,

            'total_nodes' =>

                count($nodes),

            'total_edges' =>

                count($edges),

            'ai_insight' =>

                sprintf(
                    '%s supply chain consists of %d interconnected nodes.',
                    $supplyChain['product']
                        ?? 'This',
                    count($nodes)
                ),
        ];
    }
}   