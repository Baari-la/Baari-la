<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\Company;
use App\Models\CompanyIdentity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CanonicalCompanyLinkService
{
    /**
     * Link a legacy company to its canonical company identity.
     */
    public function linkCompany(
        Company $company
    ): ?CompanyIdentity {

        if ($company->company_identity_id !== null) {
            return $company->companyIdentity;
        }

        $identity = $this->findCanonicalIdentity($company);

        if (! $identity) {
            return null;
        }

        $company->update([
            'company_identity_id' => $identity->id,
        ]);

        return $identity;
    }

    /**
     * Link a user to the canonical company identity.
     */
    public function linkUser(
        User $user
    ): void {

        if (! $user->company_id) {
            return;
        }

        $company = Company::find($user->company_id);

        if (! $company) {
            return;
        }

        $identity = $this->linkCompany($company);

        if (! $identity) {
            return;
        }

        $user->update([
            'company_identity_id' => $identity->id,
        ]);
    }

    /**
     * Link company and its claimed user.
     */
    public function linkCompanyAndUser(
        Company $company
    ): void {

        DB::transaction(function () use ($company) {

            $identity = $this->linkCompany($company);

            if (! $identity) {
                return;
            }

            if ($company->claimed_by_user_id) {

                $user = User::find(
                    $company->claimed_by_user_id
                );

                if ($user) {

                    $user->update([
                        'company_identity_id' => $identity->id,
                    ]);

                }
            }

        });
    }

    /**
     * Find canonical company identity.
     */
    public function findCanonicalIdentity(
        Company $company
    ): ?CompanyIdentity {

        $normalizedName = $this->normalizeCompanyName(
            $company->nama_perusahaan
        );

        return CompanyIdentity::query()
            ->where(
                'normalized_name',
                $normalizedName
            )
            ->first();
    }

    /**
     * Normalize company name.
     */
    public function normalizeCompanyName(
        string $name
    ): string {

        return Str::of($name)
            ->upper()
            ->replaceMatches('/\bPT\.?\s*/', '')
            ->replaceMatches('/\bCV\.?\s*/', '')
            ->replaceMatches('/\bTBK\b/', '')
            ->replaceMatches('/[^A-Z0-9 ]/', '')
            ->squish()
            ->toString();
    }

    /**
     * Link all companies.
     *
     * Returns total linked companies.
     */
    public function linkAllCompanies(): int
    {
        $count = 0;

        Company::query()
            ->whereNull('company_identity_id')
            ->chunkById(100, function ($companies) use (&$count) {

                foreach ($companies as $company) {

                    if ($this->linkCompany($company)) {
                        $count++;
                    }

                }

            });

        return $count;
    }

    /**
     * Link only claimed companies.
     *
     * Returns total linked companies.
     */
    public function linkClaimedCompanies(): int
    {
        $count = 0;

        Company::query()
            ->whereNotNull('claimed_by_user_id')
            ->chunkById(100, function ($companies) use (&$count) {

                foreach ($companies as $company) {

                    $this->linkCompanyAndUser($company);

                    $count++;

                }

            });

        return $count;
    }
}