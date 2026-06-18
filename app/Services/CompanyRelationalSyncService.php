<?php

namespace App\Services;

use App\Models\Company;

use App\Services\CompanyProductService;
use App\Services\CompanyMarketService;
use App\Services\CompanyCertificationService;
use App\Services\CompanyContactService;
use App\Services\CompanyLinkService;
use App\Services\CompanyCapacityService;
use App\Services\CompanyImageService;
use App\Services\CompanyMachineService;
use App\Services\CompanyMoqService;
use App\Services\CompanyLeadTimeService;
use App\Services\CompanyLocationService;

class CompanyRelationalSyncService
{
    /*
    |--------------------------------------------------------------------------
    | MAIN SYNC
    |--------------------------------------------------------------------------
    */

     public static function sync(
        Company $company,
        array $data
    ): void {

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('products', $data)) {

            CompanyProductService::syncProducts(
                $company,
                $data['products'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MARKETS
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('markets', $data)) {

            CompanyMarketService::syncMarkets(
                $company,
                $data['markets'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CERTIFICATIONS
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('certifications', $data)) {

            CompanyCertificationService::syncCertifications(
                $company,
                $data['certifications'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTACTS
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('contacts', $data)) {

            CompanyContactService::syncContacts(
                $company,
                $data['contacts'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LINKS
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('links', $data)) {

            CompanyLinkService::syncLinks(
                $company,
                $data['links'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CAPACITIES
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('capacities', $data)) {

            CompanyCapacityService::syncCapacities(
                $company,
                $data['capacities'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGES
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('images', $data)) {

            CompanyImageService::syncImages(
                $company,
                $data['images'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MACHINES
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('machines', $data)) {

            CompanyMachineService::syncMachines(
                $company,
                $data['machines'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MOQ
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('moqs', $data)) {

            CompanyMoqService::syncMoqs(
                $company,
                $data['moqs'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LEAD TIMES
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('lead_times', $data)) {

            CompanyLeadTimeService::syncLeadTimes(
                $company,
                $data['lead_times'] ?? []
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOCATIONS
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('locations', $data)) {

            CompanyLocationService::syncLocations(
                $company,
                $data['locations'] ?? []
            );
        }
    }

}