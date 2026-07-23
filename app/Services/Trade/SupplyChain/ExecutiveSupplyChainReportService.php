<?php

declare(strict_types=1);

namespace App\Services\Trade\SupplyChain;

class ExecutiveSupplyChainReportService
{
    public function build(
        array $supplyChain,
        array $suppliers = [],
        array $buyers = [],
        array $graph = [],
    ): array {

        return [

            'title' =>

                sprintf(
                    'Build My Supply Chain™ Report - %s',
                    $supplyChain['product']
                ),

            'generated_at' =>

                now()->toDateTimeString(),

            'product' =>

                $supplyChain['product'],

            'sector' =>

                $supplyChain['sector'],

            'executive_summary' =>

                $this->summary(
                    $supplyChain,
                    $suppliers,
                    $buyers
                ),

            'supply_chain' =>

                $supplyChain,

            'suppliers' =>

                $suppliers,

            'buyers' =>

                $buyers,

            'graph' =>

                $graph,

            'metrics' => [

                'total_suppliers' =>

                    count(
                        $suppliers
                    ),

                'total_buyers' =>

                    count(
                        $buyers
                    ),

                'total_nodes' =>

                    data_get(
                        $graph,
                        'total_nodes',
                        0
                    ),

                'total_edges' =>

                    data_get(
                        $graph,
                        'total_edges',
                        0
                    ),
            ],

            'ai_insight' =>

                $this->insight(
                    $supplyChain,
                    $buyers
                ),
        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    protected function summary(
        array $supplyChain,
        array $suppliers,
        array $buyers
    ): string {

        return sprintf(
            '%s belongs to the %s sector and currently has %d recommended suppliers and %d potential buyers identified by DIGESTEX.',
            $supplyChain['product'],
            ucfirst(
                $supplyChain['sector']
            ),
            count(
                $suppliers
            ),
            count(
                $buyers
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * AI Insight
     * --------------------------------------------------------------------------
     */
    protected function insight(
        array $supplyChain,
        array $buyers
    ): string {

        $product =

            $supplyChain['product'];

        $buyerCount = count(
            $buyers
        );

        return sprintf(
            '%s demonstrates strong global market potential with %d identified buyers across strategic textile markets.',
            $product,
            $buyerCount
        );
    }
}