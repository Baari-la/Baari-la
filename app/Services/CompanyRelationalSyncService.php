<?php

namespace App\Services;
use Illuminate\Http\UploadedFile;
use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\CompanyMarket;
use App\Models\CompanyCertification;
use App\Models\CompanyImage;
use App\Models\CompanyContact;
use App\Models\CompanyLink;
use App\Models\CompanyCapacity;
use App\Models\CompanyMachine;
use App\Models\CompanyMoq;
use App\Models\CompanyLeadTime;

class CompanyRelationalSyncService
{
    /*
    |--------------------------------------------------------------------------
    | MAIN SYNC
    |--------------------------------------------------------------------------
    */

    public static function sync(Company $company, array $data): void
    {
        if (array_key_exists('products', $data)) {   
        self::syncProducts(
            $company,
            $data['products'] ?? []
        );
}
        if (array_key_exists('markets', $data)) {   
        self::syncMarkets(
            $company,
            $data['markets'] ?? []
        );
        }
         if (array_key_exists('certifications', $data)) {   
        self::syncCertifications(
            $company,
            $data['certifications'] ?? []
        );
         }
          if (array_key_exists('contacts', $data)) {   
        self::syncContacts(
            $company,
            $data['contacts'] ?? []
        );
          }
           if (array_key_exists('links', $data)) {   
        self::syncLinks(
            $company,
            $data['links'] ?? []
        );
           }
       if (array_key_exists('capacities', $data)) {        
        self::syncCapacities(
            $company,
            $data['capacities'] ?? []
        );
       }
        if (array_key_exists('images', $data)) {   
        self::syncImages(
            $company,
            $data['images'] ?? []
        );
        }
         if (array_key_exists('machines', $data)) {   
        self::syncMachines(
            $company,
            $data['machines'] ?? []
        );
         }
          if (array_key_exists('moqs', $data)) {   
        self::syncMoqs(
            $company,
            $data['moqs'] ?? []
        );
          }
           if (array_key_exists('lead_times', $data)) {   
        self::syncLeadTimes(
            $company,
            $data['lead_times'] ?? []
        );
           }
        
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    public static function syncProducts(
        Company $company,
        array $products
    ): void {

        $company->products()->delete();

        foreach ($products as $product) {

            if (
                empty($product['product_name'])
            ) {
                continue;
            }

            $company->products()->create([
                'product_name' =>
                    $product['product_name'] ?? null,

                'product_name_en' =>
                    $product['product_name_en'] ?? null,

                'hs_code' =>
                    $product['hs_code'] ?? null,

                'category' =>
                    $product['category'] ?? null,

                'description' =>
                    $product['description'] ?? null,

                'is_primary' =>
                    $product['is_primary'] ?? 0,
            ]);
        }
    }

    /*
|--------------------------------------------------------------------------
| IMAGES
|--------------------------------------------------------------------------
*/

public static function syncImages(
    Company $company,
    array $images
): void {

    // HAPUS DATA LAMA
    $company->images()->delete();

    foreach ($images as $index => $image) {

        // SKIP JIKA BENAR-BENAR KOSONG
        if (
            empty($image['image_url']) &&
            empty($image['image_file'])
        ) {
            continue;
        }

        $imagePath = null;

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOAD
        |--------------------------------------------------------------------------
        */

        if (
            isset($image['image_file']) &&
            $image['image_file']
        ) {

            $imagePath = $image['image_file']->store(
                'company-images',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT DATABASE
        |--------------------------------------------------------------------------
        */

        $company->images()->create([

            'image_url' =>
                $image['image_url'] ?? null,

            'image_path' =>
                $imagePath,

            'image_type' =>
                $image['image_type'] ?? 'factory',

            'caption' =>
                $image['caption'] ?? null,

            'title' =>
                $image['caption'] ?? null,

            'sort_order' =>
                $index,

            'is_featured' =>
                false,
        ]);
    }
}
    /*
    |--------------------------------------------------------------------------
    | MARKETS
    |--------------------------------------------------------------------------
    */

    public static function syncMarkets(
        Company $company,
        array $markets
    ): void {

        

        foreach ($markets as $market) {

            if (
                empty($market['country_name'])
            ) {
                continue;
            }

            $company->markets()->create([
                'country_name' =>
                    $market['country_name'] ?? null,

                'market_type' =>
                    $market['market_type'] ?? 'export',
            ]);
        }
    }

  /*
|--------------------------------------------------------------------------
| CERTIFICATIONS
|--------------------------------------------------------------------------
*/

public static function syncCertifications(
    Company $company,
    array $certifications
): void {


       /*
    |--------------------------------------------------------------------------
    | INSERT NEW DATA
    |--------------------------------------------------------------------------
    */

    foreach ($certifications as $certification) {

        /*
        |--------------------------------------------------------------------------
        | SKIP EMPTY ROW
        |--------------------------------------------------------------------------
        */

        if (
            empty($certification['certification_name'])
        ) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | PDF UPLOAD
        |--------------------------------------------------------------------------
        */

        $pdfPath = null;

        if (
            isset($certification['certificate_file']) &&
            $certification['certificate_file'] instanceof UploadedFile
        ) {

            $pdfPath = $certification['certificate_file']
                ->store(
                    'company-certificates',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | KEEP EXISTING PDF
        |--------------------------------------------------------------------------
        */

        if (
            !$pdfPath &&
            !empty($certification['certificate_file']) &&
            is_string($certification['certificate_file'])
        ) {

            $pdfPath =
                $certification['certificate_file'];
        }

        /*
        |--------------------------------------------------------------------------
        | LOGO UPLOAD
        |--------------------------------------------------------------------------
        */

        $logoPath = null;

        if (
            isset($certification['logo_file']) &&
            $certification['logo_file'] instanceof UploadedFile
        ) {

            $logoPath = $certification['logo_file']
                ->store(
                    'company-certification-logos',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | KEEP EXISTING LOGO
        |--------------------------------------------------------------------------
        */

        if (
            !$logoPath &&
            !empty($certification['logo_url']) &&
            is_string($certification['logo_url'])
        ) {

            $logoPath =
                $certification['logo_url'];
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $company->certifications()->create([

            'certification_name' =>
                $certification['certification_name'] ?? null,

            'category' =>
                $certification['category'] ?? null,

            'certification_code' =>
                $certification['certification_code'] ?? null,

            'issuer' =>
                $certification['issuer'] ?? null,

            'certificate_number' =>
                $certification['certificate_number'] ?? null,

            'description' =>
                $certification['description'] ?? null,

            'certificate_file' =>
                $pdfPath,

            'logo_url' =>
                $logoPath,

            'is_verified' =>
                $certification['is_verified'] ?? false,

            'is_featured' =>
                $certification['is_featured'] ?? false,

            'sort_order' =>
                $certification['sort_order'] ?? 0,

            'issued_at' =>
                $certification['issued_at'] ?? null,

            'valid_until' =>
                $certification['valid_until'] ?? null,

            'status' =>
                $certification['status'] ?? 'active',
        ]);
    }
}

    /*
|--------------------------------------------------------------------------
| CONTACTS
|--------------------------------------------------------------------------
*/

public static function syncContacts(
    Company $company,
    array $contacts
): void {

   

    foreach ($contacts as $contact) {

        if (
            empty($contact['contact_name']) &&
            empty($contact['email']) &&
            empty($contact['phone'])
        ) {
            continue;
        }

        $company->contacts()->create([

            'contact_name' =>
                $contact['contact_name'] ?? null,

            'position' =>
                $contact['position'] ?? null,

            'phone' =>
                $contact['phone'] ?? null,

            'email' =>
                $contact['email'] ?? null,
        ]);
    }
}

    /*
    |--------------------------------------------------------------------------
    | LINKS
    |--------------------------------------------------------------------------
    */

    public static function syncLinks(
        Company $company,
        array $links
    ): void {

        

        foreach ($links as $link) {

            if (
                empty($link['url'])
            ) {
                continue;
            }

            $company->links()->create([
                 'link_type' =>
                    $link['link_type'] ?? 'website',

                'url' =>
                    $link['url'] ?? null,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CAPACITIES
    |--------------------------------------------------------------------------
    */

    public static function syncCapacities(
        Company $company,
        array $capacities
    ): void {

       

        foreach ($capacities as $capacity) {

            if (
                empty($capacity['capacity_type']) &&
                empty($capacity['item_name'])
            ) {
                continue;
            }

            $company->capacities()->create([
                'capacity_type' =>
                    $capacity['capacity_type'] ?? null,

                'item_name' =>
                    $capacity['item_name'] ?? null,

                'capacity_value' =>
                    $capacity['capacity_value'] ?? null,

                'capacity_unit' =>
                    $capacity['capacity_unit'] ?? null,

                'capacity_category' =>
                    $capacity['capacity_category']
                        ?? 'installed',

                'shift_info' =>
                    $capacity['shift_info'] ?? null,

                'machine_count' =>
                    $capacity['machine_count'] ?? null,

                'notes' =>
                    $capacity['notes'] ?? null,
            ]);
        }
    }

public static function syncLeadTimes(
    Company $company,
    array $leadTimes
): void {

    /*
    |--------------------------------------------------------------------------
    | DELETE OLD DATA
    |--------------------------------------------------------------------------
    */

    

    /*
    |--------------------------------------------------------------------------
    | INSERT NEW DATA
    |--------------------------------------------------------------------------
    */

    foreach ($leadTimes as $leadTime) {

        /*
        |--------------------------------------------------------------------------
        | SKIP EMPTY ROW
        |--------------------------------------------------------------------------
        */

        if (
            empty($leadTime['lead_time_type']) &&
            empty($leadTime['days'])
        ) {
            continue;
        }

        $company->leadTimes()->create([

            'lead_time_type' =>
                $leadTime['lead_time_type'] ?? null,

            'days' =>
                $leadTime['days'] ?? null,

            'notes' =>
                $leadTime['notes'] ?? null,
        ]);
    }
}
public static function syncMoqs(
    Company $company,
    array $moqs
): void {

    $processedIds = [];

    foreach ($moqs as $moq) {

        if (
            empty($moq['product_name']) &&
            empty($moq['minimum_quantity'])
        ) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE EXISTING
        |--------------------------------------------------------------------------
        */

        if (!empty($moq['id'])) {

            $record = CompanyMoq::where(
                'company_id',
                $company->id
            )
            ->where(
                'id',
                $moq['id']
            )
            ->first();

            if ($record) {

                $record->update([

                    'product_name' =>
                        $moq['product_name'] ?? null,

                    'minimum_quantity' =>
                        $moq['minimum_quantity'] ?? 0,

                    'unit' =>
                        $moq['unit'] ?? 'PCS',

                    'notes' =>
                        $moq['notes'] ?? null,
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

        $newMoq = CompanyMoq::create([

            'company_id' =>
                $company->id,

            'product_name' =>
                $moq['product_name'] ?? null,

            'minimum_quantity' =>
                $moq['minimum_quantity'] ?? 0,

            'unit' =>
                $moq['unit'] ?? 'PCS',

            'notes' =>
                $moq['notes'] ?? null,
        ]);

        $processedIds[] = $newMoq->id;
    }
  

    
}

public static function syncMachines(
    Company $company,
    array $machines
): void {

    foreach ($machines as $machine) {

        /*
        |--------------------------------------------------------------------------
        | SKIP EMPTY ROW
        |--------------------------------------------------------------------------
        */

        if (
            empty($machine['machine_type']) &&
            empty($machine['machine_brand'])
        ) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE EXISTING
        |--------------------------------------------------------------------------
        */

        if (!empty($machine['id'])) {

            $record = CompanyMachine::where(
                'company_id',
                $company->id
            )
            ->where(
                'id',
                $machine['id']
            )
            ->first();

            if ($record) {

                $record->update([

                    'machine_category' =>
                        $machine['machine_category'] ?? null,

                    'machine_type' =>
                        $machine['machine_type'] ?? null,

                    'machine_brand' =>
                        $machine['machine_brand'] ?? null,

                    'machine_model' =>
                        $machine['machine_model'] ?? null,

                    'quantity' =>
                        $machine['quantity'] ?? 0,

                    'production_capacity' =>
                        $machine['production_capacity'] ?? null,

                    'capacity_unit' =>
                        $machine['capacity_unit'] ?? null,

                    'working_width' =>
                        $machine['working_width'] ?? null,

                    'gauge_specification' =>
                        $machine['gauge_specification'] ?? null,

                    'year_installed' =>
                        $machine['year_installed'] ?? null,

                    'machine_condition' =>
                        $machine['machine_condition'] ?? null,

                    'automation_level' =>
                        $machine['automation_level'] ?? null,

                    'country_origin' =>
                        $machine['country_origin'] ?? null,

                    'is_active' =>
                        $machine['is_active'] ?? true,

                    'notes' =>
                        $machine['notes'] ?? null,
                ]);

                continue;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE NEW
        |--------------------------------------------------------------------------
        */

        CompanyMachine::create([

            'company_id' =>
                $company->id,

            'machine_category' =>
                $machine['machine_category'] ?? null,

            'machine_type' =>
                $machine['machine_type'] ?? null,

            'machine_brand' =>
                $machine['machine_brand'] ?? null,

            'machine_model' =>
                $machine['machine_model'] ?? null,

            'quantity' =>
                $machine['quantity'] ?? 0,

            'production_capacity' =>
                $machine['production_capacity'] ?? null,

            'capacity_unit' =>
                $machine['capacity_unit'] ?? null,

            'working_width' =>
                $machine['working_width'] ?? null,

            'gauge_specification' =>
                $machine['gauge_specification'] ?? null,

            'year_installed' =>
                $machine['year_installed'] ?? null,

            'machine_condition' =>
                $machine['machine_condition'] ?? null,

            'automation_level' =>
                $machine['automation_level'] ?? null,

            'country_origin' =>
                $machine['country_origin'] ?? null,

            'is_active' =>
                $machine['is_active'] ?? true,

            'notes' =>
                $machine['notes'] ?? null,
        ]);
    }
}
}