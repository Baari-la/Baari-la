<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence;

class AIExecutiveRecommendationService
{
    /**
     * --------------------------------------------------------------------------
     * Generate Executive Recommendations
     * --------------------------------------------------------------------------
     *
     * Inputs:
     * - Country Intelligence
     * - Global Trade Early Warning
     * - Global Textile Radar
     *
     * Outputs:
     * - Top Executive Actions
     */
    public function generate(
        array $countries,
        array $earlyWarning,
        array $globalRadar,
    ): array {

        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Top Country
        |--------------------------------------------------------------------------
        */

        $topCountry = collect($countries)

            ->sortByDesc(
                fn ($country) =>
                    $country['score']['score'] ?? 0
            )

            ->first();

        if ($topCountry) {

            $recommendations[] = [

                'title' => sprintf(
                    'Prioritize %s',
                    $topCountry['country_name_en']
                ),

                'priority' => 'HIGH',

                'description' => sprintf(
                    '%s has a Country Score of %s (%s) and should remain a strategic priority market.',
                    $topCountry['country_name_en'],
                    $topCountry['score']['score'],
                    $topCountry['score']['grade']
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Critical Alerts
        |--------------------------------------------------------------------------
        */

        foreach (
            data_get(
                $earlyWarning,
                'alerts',
                []
            ) as $alert
        ) {

            if (
                ($alert['severity'] ?? null)
                !== 'CRITICAL'
            ) {
                continue;
            }

            $recommendations[] = [

                'title' =>
                    $alert['type'],

                'priority' =>
                    'HIGH',

                'description' =>
                    $alert['message'],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Fastest Growing Markets
        |--------------------------------------------------------------------------
        */

        $fastestGrowing = collect($countries)

            ->sortByDesc('growth')

            ->take(3);

        foreach ($fastestGrowing as $country) {

            $recommendations[] = [

                'title' => sprintf(
                    'Expand to %s',
                    $country['country_name_en']
                ),

                'priority' => 'MEDIUM',

                'description' => sprintf(
                    '%s recorded %.1f%% growth and presents significant expansion opportunities.',
                    $country['country_name_en'],
                    $country['growth']
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Regional Intelligence
        |--------------------------------------------------------------------------
        */

        $asiaCountries = data_get(
            $globalRadar,
            'asia.total_countries',
            0
        );

        if ($asiaCountries > 0) {

            $recommendations[] = [

                'title' => 'Strengthen Presence in Asia',

                'priority' => 'MEDIUM',

                'description' => sprintf(
                    'Asia remains the most important region with %d analyzed textile markets.',
                    $asiaCountries
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */

        $recommendations[] = [

            'title' => 'Executive Summary',

            'priority' => 'LOW',

            'description' => sprintf(
                '%d countries analyzed with %d critical alerts identified across the global textile ecosystem.',
                count($countries),
                data_get(
                    $earlyWarning,
                    'summary.critical',
                    0
                )
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return collect($recommendations)

            ->unique('title')

            ->take(5)

            ->values()

            ->toArray();
    }
}