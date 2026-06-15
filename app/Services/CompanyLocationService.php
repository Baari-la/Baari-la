<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyLocation;

class CompanyLocationService
{
    public static function syncLocations(
    Company $company,
    array $locations
): void {

    $company->locations()->update([
        'is_primary' => false,
    ]);

    $processedIds = [];

    foreach ($locations as $location) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($location['location_name']) &&
                empty($location['address'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE OR CREATE
            |--------------------------------------------------------------------------
            */

            $record = CompanyLocation::updateOrCreate(
               
            
            [
                    'id' => $location['id'] ?? null,
                    'company_id' => $company->id,
                ],
                [
                    'location_name' => $location['location_name'] ?? '',
                    'location_type' => $location['location_type'] ?? 'head_office',

                    'country_name' => $location['country_name'] ?? null,
                    'province_name' => $location['province_name'] ?? null,
                    'city_name' => $location['city_name'] ?? null,

                    'address' => $location['address'] ?? null,

                    'contact_person' => $location['contact_person'] ?? null,

                    'phone' => $location['phone'] ?? null,
                    'email' => $location['email'] ?? null,

                    'is_primary' => (bool) (
    $location['is_primary'] ?? false
),
            ]
        );

        $processedIds[] = $record->id;
    }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED LOCATIONS
        |--------------------------------------------------------------------------
        */

        // $company->locations()
        //     ->whereNotIn('id', $processedIds)
        //     ->delete();
    }
}