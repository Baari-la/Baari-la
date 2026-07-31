<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\CompanyIdentity;
use Illuminate\Support\Collection;

class CompanyIdentityLookupService
{
    public function __construct(
        private readonly CompanyIdentityResolver $resolver
    ) {
    }

    /**
     * Search canonical company identities.
     *
     * Only READY identities are exposed.
     */
    public function search(
        string $query,
        int $limit = 10
    ): Collection {
        $query = trim($query);

        if ($query === '') {
            return collect();
        }

        $normalized = $this->resolver
            ->normalizeCompanyName($query);

        if ($normalized === '') {
            return collect();
        }

        return CompanyIdentity::query()
            ->with([
                'capabilities',
            ])
            ->where(
                'identity_status',
                'READY'
            )
            ->where(function ($builder) use (
                $query,
                $normalized
            ) {
                /*
                 * Exact normalized identity gets highest priority.
                 */
                $builder->where(
                    'normalized_name',
                    $normalized
                )
                ->orWhere(
                    'canonical_name',
                    'like',
                    '%' . $query . '%'
                )
                ->orWhere(
                    'normalized_name',
                    'like',
                    '%' . $normalized . '%'
                );
            })
            ->orderByRaw(
                'CASE
                    WHEN normalized_name = ? THEN 0
                    ELSE 1
                END',
                [$normalized]
            )
            ->orderBy(
                'canonical_name'
            )
            ->limit(
                max(1, min($limit, 50))
            )
            ->get();
    }

    /**
     * Find one exact canonical identity.
     */
    public function findExact(
        string $companyName
    ): ?CompanyIdentity {
        $normalized = $this->resolver
            ->normalizeCompanyName($companyName);

        if ($normalized === '') {
            return null;
        }

        return CompanyIdentity::query()
            ->with([
                'capabilities',
                'sources',
            ])
            ->where(
                'identity_status',
                'READY'
            )
            ->where(
                'normalized_name',
                $normalized
            )
            ->first();
    }

    /**
     * Determine whether an identity already exists.
     */
    public function exists(
        string $companyName
    ): bool {
        $normalized = $this->resolver
            ->normalizeCompanyName($companyName);

        if ($normalized === '') {
            return false;
        }

        return CompanyIdentity::query()
            ->where(
                'identity_status',
                'READY'
            )
            ->where(
                'normalized_name',
                $normalized
            )
            ->exists();
    }
}