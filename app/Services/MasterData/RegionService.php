<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use App\Models\Country;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Region Service
 * ==========================================================================
 *
 * Master Region Service.
 *
 * Responsible for:
 *
 * • Region lookup
 * • Localized region names
 * • Region dropdown options
 * • Country grouping
 *
 * Used by:
 *
 * • Company Profile
 * • Company Directory
 * • Market Intelligence
 * • Trade Intelligence
 * • Supply Chain Intelligence
 * • Executive AI
 *
 * Version:
 * 1.0
 */
class RegionService
{
    /**
     * Cached countries.
     */
    protected Collection $countries;

    public function __construct()
    {
        $this->countries = Country::active()->get();
    }

    /**
     * --------------------------------------------------------------------------
     * All Regions
     * --------------------------------------------------------------------------
     */
    public function all(?string $locale = null): Collection
    {
        $locale ??= App::getLocale();

        $field = $locale === 'en'

            ? 'region_en'

            : 'region_id';

        return $this->countries

            ->filter(fn ($country) => ! empty($country->{$field}))

            ->groupBy('region_code')

            ->map(function (Collection $countries) use ($field) {

                $country = $countries->first();

                return [

                    'code' => $country->region_code,

                    'name' => $country->{$field},

                    'countries' => $countries->count(),

                ];

            })

            ->sortBy('name')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Find Region
     * --------------------------------------------------------------------------
     */
    public function find(
        string $regionCode,
        ?string $locale = null,
    ): ?array {

        return $this->all($locale)

            ->firstWhere(
                'code',
                strtoupper($regionCode)
            );

    }

    /**
     * --------------------------------------------------------------------------
     * Region Exists
     * --------------------------------------------------------------------------
     */
    public function exists(
        string $regionCode
    ): bool {

        return $this->find($regionCode) !== null;

    }

    /**
     * --------------------------------------------------------------------------
     * Region Name
     * --------------------------------------------------------------------------
     */
    public function displayName(
        string $regionCode,
        ?string $locale = null,
    ): string {

        $region = $this->find(
            $regionCode,
            $locale
        );

        return $region['name']

            ?? $regionCode;

    }
        /**
     * --------------------------------------------------------------------------
     * Region Options
     * --------------------------------------------------------------------------
     *
     * Dropdown options.
     */
    public function options(?string $locale = null): Collection
    {
        return $this->all($locale)

            ->map(function (array $region) {

                return [

                    'code' => $region['code'],

                    'name' => $region['name'],

                    'label' => $region['name'],

                    'countries' => $region['countries'],

                ];

            })

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Countries
     * --------------------------------------------------------------------------
     *
     * Returns all countries belonging to a region.
     */
    public function countries(
        string $regionCode,
    ): Collection {

        return $this->countries

            ->where(

                'region_code',

                strtoupper($regionCode)

            )

            ->values();

    }

    /**
     * --------------------------------------------------------------------------
     * Country Count
     * --------------------------------------------------------------------------
     */
    public function countryCount(
        string $regionCode,
    ): int {

        return $this->countries(

            $regionCode

        )->count();

    }

    /**
     * --------------------------------------------------------------------------
     * Sub Regions
     * --------------------------------------------------------------------------
     */
    public function subRegions(
        string $regionCode,
        ?string $locale = null,
    ): Collection {

        $locale ??= App::getLocale();

        $field = $locale === 'en'

            ? 'sub_region_en'

            : 'sub_region_id';

        return $this->countries(

            $regionCode

        )

            ->filter(fn ($country) => ! empty($country->{$field}))

            ->groupBy($field)

            ->map(function (Collection $countries, string $name) {

                return [

                    'name' => $name,

                    'countries' => $countries->count(),

                ];

            })

            ->sortBy('name')

            ->values();

    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'regions' => $this->all()->count(),

            'countries' => $this->countries->count(),

            'active_countries' =>

                $this->countries

                    ->where('is_active', true)

                    ->count(),

        ];

    }
}