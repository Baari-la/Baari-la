<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyLink;

class CompanyLinkService
{
    public static function syncLinks(
        Company $company,
        array $links
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($links)) {
            return;
        }

        $processedIds = [];

        foreach ($links as $link) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($link['url'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($link['id'])) {

                $record = CompanyLink::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $link['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'link_type' =>
                            $link['link_type'] ?? 'website',

                        'url' =>
                            $link['url'] ?? null,
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

            $newLink = $company->links()->create([

                'link_type' =>
                    $link['link_type'] ?? 'website',

                'url' =>
                    $link['url'] ?? null,
            ]);

            $processedIds[] = $newLink->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->links()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}