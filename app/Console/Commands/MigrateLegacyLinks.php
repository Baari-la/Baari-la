<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyLink;
use Illuminate\Console\Command;

class MigrateLegacyLinks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature =
        'digestex:migrate-links';

    /**
     * The console command description.
     */
    protected $description =
        'Move legacy websites from companies.email_web into company_links';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info(
            'Starting links migration...'
        );

        $created = 0;

        Company::chunk(
            100,
            function ($companies) use (&$created) {

                foreach ($companies as $company) {

                    if (
                        empty($company->email_web)
                    ) {
                        continue;
                    }

                    $items = preg_split(
                        '/[\/;,]+/',
                        $company->email_web
                    );

                    foreach ($items as $item) {

                        $item = trim($item);

                        if (
                            empty($item)
                        ) {
                            continue;
                        }

                        /*
                        |---------------------------------------
                        | SKIP EMAIL
                        |---------------------------------------
                        */

                        if (
                            filter_var(
                                $item,
                                FILTER_VALIDATE_EMAIL
                            )
                        ) {
                            continue;
                        }

                        /*
                        |---------------------------------------
                        | WEBSITE DETECTION
                        |---------------------------------------
                        */

                        if (
                            str_contains(
                                strtolower($item),
                                'www.'
                            ) ||
                            str_contains(
                                strtolower($item),
                                '.com'
                            ) ||
                            str_contains(
                                strtolower($item),
                                '.co.id'
                            ) ||
                            str_contains(
                                strtolower($item),
                                '.id'
                            )
                        ) {

                            $url = trim($item);

                            if (
                                !str_starts_with(
                                    strtolower($url),
                                    'http'
                                )
                            ) {
                                $url =
                                    'https://' .
                                    $url;
                            }

                            $link =
                                CompanyLink::firstOrCreate(

                                    [
                                        'company_id' =>
                                            $company->id,

                                        'url' =>
                                            $url,
                                    ],

                                    [
                                        'link_type' =>
                                            'website',
                                    ]
                                );

                            if (
                                $link->wasRecentlyCreated
                            ) {
                                $created++;
                            }
                        }
                    }
                }
            }
        );

        $this->info(
            "Migration completed. {$created} links created."
        );

        return self::SUCCESS;
    }
}