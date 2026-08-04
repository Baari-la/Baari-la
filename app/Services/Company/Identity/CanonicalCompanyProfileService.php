<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\Company;
use App\Models\CompanyIdentity;
use App\Models\CompanyIdentityProfile;
use App\Models\User;
use App\Models\CompanyClaim;

class CanonicalCompanyProfileService
{
    /**
     * Build canonical company payload.
     */
    public function build(
        CompanyIdentity $identity
    ): array {

        $identity->loadMissing([
            'profile',
            'capabilities',
            'sources.company',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Owner Profile
        |--------------------------------------------------------------------------
        */

        $profile = $identity->profile;

        /*
        |--------------------------------------------------------------------------
        | Best Legacy Source
        |--------------------------------------------------------------------------
        */

        $legacyCompany = $this->bestLegacyCompany(
            $identity
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'record_type' =>
                'company_identity',

            'company_identity_id' =>
                $identity->id,

            'nama_perusahaan' =>
                $identity->canonical_name,

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            'company_type' =>
                $profile?->company_type,

            'phone' =>
                $profile?->phone
                ?? $legacyCompany?->telepon,

            'website' =>
                $profile?->website
                ?? $legacyCompany?->email_web,

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'country' =>
                $profile?->country
                ?? $identity->country_name,

            'province' =>
                $profile?->province
                ?? $legacyCompany?->province,

            'city' =>
                $profile?->city
                ?? $legacyCompany?->city,

            'postal_code' =>
                $profile?->postal_code
                ?? $legacyCompany?->postal_code,

            'address' =>
                $profile?->address
                ?? $legacyCompany?->alamat_lengkap,

            /*
            |--------------------------------------------------------------------------
            | Intelligence
            |--------------------------------------------------------------------------
            */

            'profile_exists' =>
                $profile !== null,

            'source_count' =>
                $identity->sources->count(),

            'capabilities' =>
                $identity
                    ->capabilities
                    ->pluck('capability')
                    ->sort()
                    ->values()
                    ->all(),
        ];
    }

   /**
 * Build profile from user.
 */
public function buildFromUser(
    User $user
): ?array {

    if (
        !$user->company_identity_id
    ) {
        return null;
    }

    return $this->buildFromIdentity(
        $user->company_identity_id
    );
}

    /**
     * Return best legacy source.
     */
    protected function bestLegacyCompany(
        CompanyIdentity $identity
    ): ?Company {

        return $identity
            ->sources
            ->pluck('company')
            ->filter()
            ->first();
    }
    /**
 * Build profile from identity ID or model.
 */
public function buildFromIdentity(
    CompanyIdentity|int $identity
): ?array {

    if (is_numeric($identity)) {

        $identity = CompanyIdentity::query()
            ->find($identity);
    }

    if (!$identity) {
        return null;
    }

    return $this->build(
        $identity
    );
}
/**
 * Build profile from ownership claim.
 */
public function buildFromClaim(
    CompanyClaim $claim
): ?array {

    if (
        !$claim->company_identity_id
    ) {
        return null;
    }

    return $this->buildFromIdentity(
        $claim->company_identity_id
    );
}
}