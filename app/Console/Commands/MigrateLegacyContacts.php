<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyContact;
use Illuminate\Console\Command;

class MigrateLegacyContacts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature =
        'digestex:migrate-contacts';

    /**
     * The console command description.
     */
    protected $description =
        'Move legacy company contacts into company_contacts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info(
            'Starting contacts migration...'
        );

        $created = 0;

        Company::chunk(
            100,
            function ($companies) use (&$created) {

                foreach ($companies as $company) {

                    /*
                    |---------------------------------------
                    | PRIMARY CONTACT
                    |---------------------------------------
                    */

                    if (
                        !empty($company->pimpinan)
                    ) {

                        $email =
                            $this->extractFirstEmail(
                                $company->email_web
                            );

                        $contact =
                            CompanyContact::firstOrCreate(

                                [
                                    'company_id' =>
                                        $company->id,

                                    'contact_name' =>
                                        trim(
                                            $company->pimpinan
                                        ),
                                ],

                                [
                                    'position' =>
                                        'Primary Contact',

                                    'email' =>
                                        $email,

                                    'is_primary' =>
                                        true,
                                ]
                            );

                        if (
                            $contact->wasRecentlyCreated
                        ) {
                            $created++;
                        }
                    }

                    /*
                    |---------------------------------------
                    | SECONDARY CONTACT
                    |---------------------------------------
                    */

                    if (
                        !empty($company->pimpinan_2)
                    ) {

                        $contact =
                            CompanyContact::firstOrCreate(

                                [
                                    'company_id' =>
                                        $company->id,

                                    'contact_name' =>
                                        trim(
                                            $company->pimpinan_2
                                        ),
                                ],

                                [
                                    'position' =>
                                        'Secondary Contact',

                                    'is_primary' =>
                                        false,
                                ]
                            );

                        if (
                            $contact->wasRecentlyCreated
                        ) {
                            $created++;
                        }
                    }
                }
            }
        );

        $this->info(
            "Migration completed. {$created} contacts created."
        );

        return self::SUCCESS;
    }

    /**
     * Extract first valid email.
     */
    protected function extractFirstEmail(
        ?string $value
    ): ?string {

        if (
            empty($value)
        ) {
            return null;
        }

        $parts = preg_split(
            '/[\/;,]+/',
            $value
        );

        foreach ($parts as $part) {

            $part = trim($part);

            if (
                filter_var(
                    $part,
                    FILTER_VALIDATE_EMAIL
                )
            ) {
                return $part;
            }
        }

        return null;
    }
}