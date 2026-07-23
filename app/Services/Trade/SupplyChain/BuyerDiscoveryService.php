<?php

declare(strict_types=1);

namespace App\Services\Trade\SupplyChain;

class BuyerDiscoveryService
{
    /**
     * --------------------------------------------------------------------------
     * Discover Buyers
     * --------------------------------------------------------------------------
     */
    public function discover(
        string $product
    ): array {

        $buyers = config(
            "product_supply_chain.{$product}.buyers",
            []
        );

        return collect($buyers)

            ->map(function (
                string $buyer,
                int $index
            ) {

                return [

                    'company_id' =>

                        $index + 1,

                    'name' =>

                        $buyer,

                    'business_role' =>

                        'Global Buyer',

                    'country' =>

                        $this->country(
                            $buyer
                        ),

                    'score' =>

                        95,

                    'markets' =>

                        $this->markets(
                            $buyer
                        ),

                    'certifications' => [],
                ];
            })

            ->values()

            ->toArray();
    }
    /**
     * --------------------------------------------------------------------------
     * Country
     * --------------------------------------------------------------------------
     */
    protected function country(
        string $buyer
    ): string {

        return match ($buyer) {

            'Uniqlo' => 'Japan',

            'H&M' => 'Sweden',

            'Decathlon' => 'France',

            'Lacoste' => 'France',

            'Polo Ralph Lauren' =>

                'United States',

            'Marks & Spencer' =>

                'United Kingdom',

            default =>

                'Global',
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Markets
     * --------------------------------------------------------------------------
     */
    protected function markets(
        string $buyer
    ): array {

        return match ($buyer) {

            'Uniqlo' => [

                'Japan',
                'ASEAN',
                'United States',
            ],

            'H&M' => [

                'Europe',
                'United States',
            ],

            'Decathlon' => [

                'Europe',
                'Asia',
            ],

            default => [

                'Global',
            ],
        };
    }
}    