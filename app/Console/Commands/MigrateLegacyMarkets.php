<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyMarket;
use Illuminate\Console\Command;

class MigrateLegacyMarkets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature =
        'digestex:migrate-markets';

    /**
     * The console command description.
     */
    protected $description =
        'Move legacy export markets into company_markets';

    public function handle(): int
    {
        $this->info(
            'Starting markets migration...'
        );

        $created = 0;

        $marketMap = [

            'USA' =>
                'United States',

            'US' =>
                'United States',

            'AMERICA' =>
                'United States',

            'UK' =>
                'United Kingdom',

            'ENGLAND' =>
                'United Kingdom',

            'KOREA' =>
                'South Korea',

            'SOUTH KOREA' =>
                'South Korea',

            'HONGKONG' =>
                'Hong Kong',

            'UAE' =>
                'United Arab Emirates',

            'USTRALIA' =>
                'Australia',

            'EUROPE' =>
                'Europe',

            'ASIA' =>
                'Asia',

            'AFRICA' =>
                'Africa',

            'ETC' =>
                null,
        ];

        Company::whereNotNull(
            'pasar_ekspor'
        )
            ->where(
                'pasar_ekspor',
                '!=',
                ''
            )
            ->chunk(
                100,
                function (
                    $companies
                ) use (
                    &$created,
                    $marketMap
                ) {

                    foreach (
                        $companies as
                        $company
                    ) {

                        $markets =
                            preg_split(
                                '/[,;\/]+/',
                                $company->pasar_ekspor
                            );

                        foreach (
                            $markets as
                            $market
                        ) {

                            $market = trim(
                                strtoupper(
                                    $market
                                )
                            );

                            if (
                                empty(
                                    $market
                                )
                            ) {
                                continue;
                            }

                            $market =
                                $marketMap[
                                    $market
                                ]
                                ?? ucwords(
                                    strtolower(
                                        $market
                                    )
                                );

                            if (
                                empty(
                                    $market
                                )
                            ) {
                                continue;
                            }

                            $record =
                                CompanyMarket::firstOrCreate(

                                    [
                                        'company_id' =>
                                            $company->id,

                                        'country_name' =>
                                            $market,
                                    ],

                                    [
                                        'market_type' =>
                                            'export',
                                    ]
                                );

                            if (
                                $record->wasRecentlyCreated
                            ) {
                                $created++;
                            }
                        }
                    }
                }
            );

        $this->info(
            "Migration completed. {$created} markets created."
        );

        return self::SUCCESS;
    }
}