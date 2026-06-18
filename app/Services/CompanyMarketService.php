<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyMarket;

class CompanyMarketService
{
    public static function syncMarkets(
        Company $company,
        array $markets
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($markets)) {
            return;
        }

        $processedIds = [];

        foreach ($markets as $market) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($market['country_name'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($market['id'])) {

                $record = CompanyMarket::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $market['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'country_name' =>
                            $market['country_name'] ?? null,

                        'market_type' =>
                            $market['market_type'] ?? 'export',
                    ]);

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW
            |--------------------------------------------------------------------------
            */

            $newMarket = $company->markets()->create([

                'country_name' =>
                    $market['country_name'] ?? null,

                'market_type' =>
                    $market['market_type'] ?? 'export',
            ]);

            $processedIds[] = $newMarket->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->markets()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}