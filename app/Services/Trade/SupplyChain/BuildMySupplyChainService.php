<?php

declare(strict_types=1);

namespace App\Services\Trade\SupplyChain;

class BuildMySupplyChainService
{
    /**
     * --------------------------------------------------------------------------
     * Build Supply Chain
     * --------------------------------------------------------------------------
     */
     public function buildByProduct(
        string $product
    ): array {

        $config = config(
            "product_supply_chain.{$product}"
        );

        if (! $config) {

            return [

                'product' => $product,

                'sector' => null,

                'upstream' => [],

                'midstream' => [],

                'downstream' => [],

                'supporting' => [],

                'buyers' => [],

                'countries' => [],

                'ai_insight' =>

                    sprintf(
                        'No supply chain intelligence is currently available for %s.',
                        str($product)
                            ->replace('_', ' ')
                            ->title()
                    ),
            ];
        }

        return [

            'product' =>

                str($product)

                    ->replace(
                        '_',
                        ' '
                    )

                    ->title()

                    ->toString(),

            'sector' =>

                $config['sector'],

            'upstream' =>

                $config['upstream']

                ?? [],

            'midstream' =>

                $config['midstream']

                ?? [],

            'downstream' =>

                $config['downstream']

                ?? [],

            'supporting' =>

                $config['supporting']

                ?? [],

            'buyers' =>

                $config['buyers']

                ?? [],

            'countries' =>

                $config['countries']

                ?? [],

            'ai_insight' =>

                $this->insight(
                    $product,
                    $config
                ),
        ];
    }
     /**
     * --------------------------------------------------------------------------
     * AI Insight
     * --------------------------------------------------------------------------
     */
    protected function insight(
        string $product,
        array $config
    ): string {

        $name = str($product)

            ->replace(
                '_',
                ' '
            )

            ->title()

            ->toString();

        $sector = $config['sector']
            ?? 'textile';

        $buyerCount = count(
            $config['buyers']
            ?? []
        );

        $countryCount = count(
            $config['countries']
            ?? []
        );

        return sprintf(
            '%s belongs to the %s sector and currently has %d identified buyers across %d strategic markets.',
            $name,
           ucfirst(
    $sector
),

            $buyerCount,
            $countryCount
        );
    }
}   