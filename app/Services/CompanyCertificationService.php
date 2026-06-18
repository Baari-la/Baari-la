<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyCertification;
use Illuminate\Http\UploadedFile;

class CompanyCertificationService
{
    public static function syncCertifications(
        Company $company,
        array $certifications
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($certifications)) {
            return;
        }

        $processedIds = [];

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
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($certification['id'])) {

                $record = CompanyCertification::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $certification['id']
                )
                ->first();

                if ($record) {

                    $record->update([

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

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW
            |--------------------------------------------------------------------------
            */

            $newCertification = $company->certifications()->create([

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

            $processedIds[] = $newCertification->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->certifications()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}