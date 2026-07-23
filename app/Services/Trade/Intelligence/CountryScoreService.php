<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence;

class CountryScoreService
{
    /**
     * --------------------------------------------------------------------------
     * Calculate DIGESTEX Country Score
     * --------------------------------------------------------------------------
     */
    public function calculate(
        array $country,
        array $badges = []
    ): array {

        $score = 0;

        /*
        |--------------------------------------------------------------------------
        | Market Size (25)
        |--------------------------------------------------------------------------
        */

        $exportValue = (float) (
            $country['export_value'] ?? 0
        );

        if ($exportValue >= 1_000_000_000) {
            $score += 25;
        } elseif ($exportValue >= 500_000_000) {
            $score += 20;
        } elseif ($exportValue >= 100_000_000) {
            $score += 15;
        } elseif ($exportValue >= 50_000_000) {
            $score += 10;
        } else {
            $score += 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Growth (20)
        |--------------------------------------------------------------------------
        */

        $growth = (float) (
            $country['growth'] ?? 0
        );

        if ($growth >= 50) {
            $score += 20;
        } elseif ($growth >= 20) {
            $score += 15;
        } elseif ($growth >= 0) {
            $score += 10;
        } else {
            $score += 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Trade Balance (15)
        |--------------------------------------------------------------------------
        */

        $tradeBalance = (float) (
            $country['trade_balance'] ?? 0
        );

        if ($tradeBalance >= 500_000_000) {
            $score += 15;
        } elseif ($tradeBalance > 0) {
            $score += 10;
        }

        /*
        |--------------------------------------------------------------------------
        | Market Share (15)
        |--------------------------------------------------------------------------
        */

        $share = (float) (
            $country['share'] ?? 0
        );

        if ($share >= 25) {
            $score += 15;
        } elseif ($share >= 10) {
            $score += 10;
        } elseif ($share >= 5) {
            $score += 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Import Dependency (10)
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                'IMPORT DEPENDENT',
                array_column(
                    $badges,
                    'name'
                )
            )
        ) {
            $score += 0;
        } else {
            $score += 10;
        }

        /*
        |--------------------------------------------------------------------------
        | Strategic Importance (10)
        |--------------------------------------------------------------------------
        */

        foreach ($badges as $badge) {

            if (
                in_array(
                    $badge['name'],
                    [
                        'TOP BUYER',
                        'STRATEGIC MARKET',
                        'GLOBAL MARKET LEADER',
                    ]
                )
            ) {
                $score += 10;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Regional Influence (5)
        |--------------------------------------------------------------------------
        */

        foreach ($badges as $badge) {

            if (
                in_array(
                    $badge['name'],
                    [
                        'REGIONAL HUB',
                        'STRATEGIC SUPPLIER',
                    ]
                )
            ) {
                $score += 5;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Grade
        |--------------------------------------------------------------------------
        */

        return [

            'score' => min(
                100,
                $score
            ),

            'grade' => $this->grade(
                $score
            ),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Grade
     * --------------------------------------------------------------------------
     */
    protected function grade(
        int|float $score
    ): string {

        return match (true) {

            $score >= 90 => 'A+',

            $score >= 80 => 'A',

            $score >= 70 => 'B',

            $score >= 60 => 'C',

            default => 'D',
        };
    }
}