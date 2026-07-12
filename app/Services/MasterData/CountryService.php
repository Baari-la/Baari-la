<?php

namespace App\Services\MasterData;

use App\Models\Country;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class CountryService
{
    /**
     * Cache countries during current request.
     */
    protected Collection $countries;

    public function __construct()
    {
        $this->countries = Country::active()
            ->orderBy('country_name_en')
            ->get()
            ->keyBy(function ($country) {
                return strtoupper($country->country_code);
            });
    }

    /**
     * Find country by ISO Alpha-2.
     */
    public function find(string $code): ?Country
    {
        return $this->countries->get(strtoupper($code));
    }

    /**
     * Find country by ISO Alpha-3.
     */
    public function byIso3(string $iso3): ?Country
    {
        return $this->countries
            ->first(fn ($country) => strtoupper($country->iso3) === strtoupper($iso3));
    }

    /**
     * Localized country name.
     */
    public function displayName(string $code, ?string $locale = null): string
    {
        $country = $this->find($code);

        if (!$country) {
            return $code;
        }

        $locale = $locale ?? App::getLocale();

        return $locale === 'en'
            ? $country->country_name_en
            : ($country->country_name_id ?: $country->country_name_en);
    }

    /**
     * Country name with flag.
     */
    public function displayWithFlag(string $code, ?string $locale = null): string
    {
        $country = $this->find($code);

        if (!$country) {
            return $code;
        }

        return trim(
            $country->flag_emoji . ' ' .
            $this->displayName($code, $locale)
        );
    }

    /**
     * Country flag.
     */
    public function flag(string $code): string
    {
        return $this->find($code)?->flag_emoji ?? '';
    }

    /**
     * Country exists.
     */
    public function exists(string $code): bool
    {
        return $this->find($code) !== null;
    }

    /**
     * Active countries.
     */
    public function all(): Collection
    {
        return $this->countries;
    }

    /**
     * Countries by region.
     */
    public function byRegion(string $region): Collection
    {
        return $this->countries
            ->where('region_code', strtoupper($region))
            ->values();
    }

    /**
     * Countries by sub region.
     */
    public function bySubRegion(string $subRegion): Collection
    {
        return $this->countries
            ->where('sub_region_en', $subRegion)
            ->values();
    }
/**
 * --------------------------------------------------------------------------
 * Dropdown Options
 * --------------------------------------------------------------------------
 */
public function options(?string $locale = null): Collection
{
    $locale ??= App::getLocale();

    return $this->countries

        ->map(function ($country) use ($locale) {

            $name = $locale === 'en'

                ? $country->country_name_en

                : ($country->country_name_id ?: $country->country_name_en);

            return [

                'code' => $country->country_code,

                'name' => $name,

                'flag' => $country->flag_emoji,

                'label' => trim(

                    $country->flag_emoji . ' ' . $name

                ),

            ];

        })

        ->values();
}
}