<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyContact;

class CompanyContactService
{
    public static function syncContacts(
        Company $company,
        array $contacts
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($contacts)) {
            return;
        }

        $processedIds = [];

        foreach ($contacts as $contact) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($contact['contact_name']) &&
                empty($contact['email']) &&
                empty($contact['phone'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($contact['id'])) {

                $record = CompanyContact::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $contact['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'contact_name' =>
                            $contact['contact_name'] ?? null,

                        'position' =>
                            $contact['position'] ?? null,

                        'phone' =>
                            $contact['phone'] ?? null,

                        'email' =>
                            $contact['email'] ?? null,
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

            $newContact = $company->contacts()->create([

                'contact_name' =>
                    $contact['contact_name'] ?? null,

                'position' =>
                    $contact['position'] ?? null,

                'phone' =>
                    $contact['phone'] ?? null,

                'email' =>
                    $contact['email'] ?? null,
            ]);

            $processedIds[] = $newContact->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->contacts()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}