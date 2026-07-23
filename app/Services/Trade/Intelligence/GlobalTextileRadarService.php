<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence;

use App\Services\MasterData\CountryService;
use Illuminate\Support\Collection;

class GlobalTextileRadarService
{
    public function __construct(
        protected CountryService $countryService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Build Global Textile Radar
     * --------------------------------------------------------------------------
     */
    public function build(array $countries): array
    {
        return [

            'asean' => $this->region(
                $countries,
                'ASEAN'
            ),

            'asia' => $this->region(
                $countries,
                'ASIA'
            ),

            'europe' => $this->region(
                $countries,
                'EUROPE'
            ),

            'america' => $this->region(
                $countries,
                'AMERICA'
            ),

            'middle_east' => $this->region(
                $countries,
                'MIDDLE EAST'
            ),

            'africa' => $this->region(
                $countries,
                'AFRICA'
            ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Region Intelligence
     * --------------------------------------------------------------------------
     */
    protected function region(
        array $countries,
        string $region
    ): array {

        $collection = collect($countries)

            ->filter(function ($country) use ($region) {

                $master = $this->countryService
                    ->find($country['country_code']);

                return strtoupper(
                    $master?->display_region ?? ''
                ) === strtoupper($region);
            })

            ->values();

        $largestMarket = data_get(

            $collection
                ->sortByDesc('export_value')
                ->first(),

            'country_name_en'
        );

        $largestSupplier = data_get(

            $collection
                ->sortByDesc('import_value')
                ->first(),

            'country_name_en'
        );

        $fastestGrowing = data_get(

            $collection
                ->sortByDesc('growth')
                ->first(),

            'country_name_en'
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            'total_countries' =>

                $collection->count(),

            'executive_headline' => sprintf(

                '%s countries analyzed. %s is the largest market while %s is the fastest growing market.',

                $collection->count(),

                $largestMarket ?? 'N/A',

                $fastestGrowing ?? 'N/A'
            ),

            'regional_summary' => [

                'largest_market' =>
                    $largestMarket,

                'largest_supplier' =>
                    $largestSupplier,

                'fastest_growing' =>
                    $fastestGrowing,
            ],

            /*
            |--------------------------------------------------------------------------
            | Top Scores
            |--------------------------------------------------------------------------
            */

            'top_scores' =>

                $collection

                    ->sortByDesc(
                        fn ($country) =>
                            data_get(
                                $country,
                                'score.score',
                                0
                            )
                    )

                    ->take(5)

                    ->map(function ($country) {

                        return [

                            'country' =>
                                $country['country_name_en'],

                            'score' =>
                                data_get(
                                    $country,
                                    'score.score',
                                    0
                                ),

                            'grade' =>
                                data_get(
                                    $country,
                                    'score.grade',
                                    '-'
                                ),
                        ];
                    })

                    ->values()

                    ->toArray(),

            /*
            |--------------------------------------------------------------------------
            | Intelligence
            |--------------------------------------------------------------------------
            */

            'top_buyers' =>

                $this->badges(
                    $collection,
                    'TOP BUYER'
                ),

            'top_suppliers' =>

                $this->badges(
                    $collection,
                    'TOP SUPPLIER'
                ),

            'market_leaders' =>

                $this->badges(
                    $collection,
                    'GLOBAL MARKET LEADER'
                ),

            'high_growth' =>

                $this->badges(
                    $collection,
                    'HIGH GROWTH'
                ),

            'fastest_growing' =>

                $this->badges(
                    $collection,
                    'FASTEST GROWING'
                ),

            'next_destination' =>

                $this->badges(
                    $collection,
                    'NEXT DESTINATION'
                ),

            'regional_hubs' =>

                $this->badges(
                    $collection,
                    'REGIONAL HUB'
                ),

            'emerging_markets' =>

                $this->badges(
                    $collection,
                    'EMERGING MARKET'
                ),

            'strategic_suppliers' =>

                $this->badges(
                    $collection,
                    'STRATEGIC SUPPLIER'
                ),

            'declining_markets' =>

                $this->badges(
                    $collection,
                    'DECLINING MARKET'
                ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Filter Countries by Badge
     * --------------------------------------------------------------------------
     */
    protected function badges(
        Collection $countries,
        string $badge
    ): array {

        return $countries

            ->filter(function ($country) use ($badge) {

                return collect(
                    $country['badges'] ?? []
                )

                    ->pluck('name')

                    ->contains($badge);
            })

            ->pluck('country_name_en')

            ->values()

            ->toArray();
    }
}