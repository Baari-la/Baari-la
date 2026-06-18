<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyProduct;

class CompanyProductService
{
    public static function syncProducts(
        Company $company,
        array $products
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($products)) {
            return;
        }

        $processedIds = [];

        foreach ($products as $product) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($product['product_name'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($product['id'])) {

                $record = CompanyProduct::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $product['id']
                )
                ->first();

                if ($record) {

                    $record->update([

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

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW
            |--------------------------------------------------------------------------
            */

            $newProduct = $company->products()->create([

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

            $processedIds[] = $newProduct->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->products()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}