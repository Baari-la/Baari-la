<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyProduct;
use Illuminate\Console\Command;

class MigrateLegacyProducts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature =
        'digestex:migrate-products';

    /**
     * The console command description.
     */
    protected $description =
        'Move legacy companies.produk into company_products';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info(
            'Starting migration...'
        );

        $created = 0;

        Company::chunk(
            100,
            function ($companies) use (&$created) {

                foreach ($companies as $company) {

                    if (
                        empty($company->produk)
                    ) {
                        continue;
                    }

                    $products = preg_split(
                        '/[,;|\/]+/',
                        $company->produk
                    );

                    foreach ($products as $index => $product) {

                        $product = trim($product);

                        if (
                            empty($product)
                        ) {
                            continue;
                        }

                        $record =
                            CompanyProduct::firstOrCreate(

                                [
                                    'company_id' =>
                                        $company->id,

                                    'product_name' =>
                                        $product,
                                ],

                                [
                                    'is_primary' =>
                                        $index === 0,
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
            "Migration completed. {$created} products created."
        );

        return self::SUCCESS;
    }
}