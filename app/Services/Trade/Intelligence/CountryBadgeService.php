<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence;

class CountryBadgeService
{
    /*
    |--------------------------------------------------------------------------
    | Thresholds
    |--------------------------------------------------------------------------
    */

    private const GLOBAL_MARKET_LEADER = 25;

    private const TOP_BUYER_SHARE = 15;

    private const STRATEGIC_MARKET_SHARE = 10;

    private const HIGH_GROWTH = 20;

    private const FASTEST_GROWING = 50;

    private const BALANCED_TRADE = 5_000_000;

    private const STRATEGIC_SUPPLIER = 100_000_000;

    private const MEGA_SUPPLIER = 500_000_000;

    /**
     * --------------------------------------------------------------------------
     * Generate Country Intelligence Badges
     * --------------------------------------------------------------------------
     */
    public function generate(array $country): array
    {
        $badges = [];

        $share = (float) ($country['share'] ?? 0);

        $growth = (float) ($country['growth'] ?? 0);

        $exportValue = (float) ($country['export_value'] ?? 0);

        $importValue = (float) ($country['import_value'] ?? 0);

        $tradeBalance = (float) (
            $country['trade_balance'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Market Position
        |--------------------------------------------------------------------------
        */

        if ($share >= self::GLOBAL_MARKET_LEADER) {
            $this->addBadge(
                $badges,
                'GLOBAL MARKET LEADER',
                'market'
            );
        }

        if ($share >= self::TOP_BUYER_SHARE) {
            $this->addBadge(
                $badges,
                'TOP BUYER',
                'market'
            );
        }

        if (
            $exportValue > 0 &&
            $importValue >= ($exportValue * 5)
        ) {
            $this->addBadge(
                $badges,
                'TOP SUPPLIER',
                'market'
            );
        }

        if ($share >= self::STRATEGIC_MARKET_SHARE) {
            $this->addBadge(
                $badges,
                'STRATEGIC MARKET',
                'market'
            );
        }

        if (
            $growth >= self::HIGH_GROWTH &&
            $share < self::STRATEGIC_MARKET_SHARE &&
            $tradeBalance > 0
        ) {
            $this->addBadge(
                $badges,
                'NEXT DESTINATION',
                'market'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Trade Position
        |--------------------------------------------------------------------------
        */

        if ($tradeBalance > 0) {
            $this->addBadge(
                $badges,
                'NET EXPORTER',
                'trade'
            );
        }

        if ($tradeBalance < 0) {
            $this->addBadge(
                $badges,
                'NET IMPORTER',
                'trade'
            );
        }

        if (
            abs($tradeBalance)
            <= self::BALANCED_TRADE
        ) {
            $this->addBadge(
                $badges,
                'BALANCED TRADE',
                'trade'
            );
        }

        if (
            $exportValue > 0 &&
            $importValue >= ($exportValue * 2)
        ) {
            $this->addBadge(
                $badges,
                'IMPORT DEPENDENT',
                'trade'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Growth Intelligence
        |--------------------------------------------------------------------------
        */

        if ($growth >= self::FASTEST_GROWING) {
            $this->addBadge(
                $badges,
                'FASTEST GROWING',
                'growth'
            );
        }

        if ($growth >= self::HIGH_GROWTH) {
            $this->addBadge(
                $badges,
                'HIGH GROWTH',
                'growth'
            );
        }

        if (
            $growth >= -5 &&
            $growth < self::HIGH_GROWTH
        ) {
            $this->addBadge(
                $badges,
                'STABLE MARKET',
                'growth'
            );
        }

        if ($growth < -5) {
            $this->addBadge(
                $badges,
                'DECLINING MARKET',
                'growth'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Strategic Intelligence
        |--------------------------------------------------------------------------
        */

        if (
            $share >= 5 &&
            $growth >= 10
        ) {
            $this->addBadge(
                $badges,
                'REGIONAL HUB',
                'strategic'
            );
        }

        if (
            $share < 5 &&
            $growth >= 25
        ) {
            $this->addBadge(
                $badges,
                'EMERGING MARKET',
                'strategic'
            );
        }

        if (
            $importValue
            >= self::STRATEGIC_SUPPLIER
        ) {
            $this->addBadge(
                $badges,
                'STRATEGIC SUPPLIER',
                'strategic'
            );
        }

        if (
            $importValue
            >= self::MEGA_SUPPLIER
        ) {
            $this->addBadge(
                $badges,
                'MEGA SUPPLIER',
                'strategic'
            );
        }

        return array_values($badges);
    }

    /**
     * Add Badge
     */
    protected function addBadge(
        array &$badges,
        string $name,
        string $category,
    ): void {
        $badges[$name] = [
            'name' => $name,
            'category' => $category,
        ];
    }
}