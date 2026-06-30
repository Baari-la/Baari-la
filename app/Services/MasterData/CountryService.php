<?php

namespace App\Services\MasterData;

use App\Models\Country;
use Illuminate\Support\Facades\App;

class CountryService
{
    /**
     * Find country by ISO Alpha-2 code.
     */
    public function find(string $code): ?Country
    {
        return Country::firstWhere('country_code', strtoupper($code));
    }

    /**
     * Find country by ISO Alpha-3 code.
     */
    public function byIso3(string $iso3): ?Country
    {
        return Country::firstWhere('iso3', strtoupper($iso3));
    }

    /**
     * Get localized country name.
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
     * Get localized country name with flag.
     */
    public function displayWithFlag(string $code, ?string $locale = null): string
    {
        $country = $this->find($code);

        if (!$country) {
            return $code;
        }

        return trim($country->flag_emoji . ' ' . $this->displayName($code, $locale));
    }

    /**
     * Get country flag emoji.
     */
    public function flag(string $code): string
    {
        return $this->find($code)?->flag_emoji ?? '';
    }

    /**
     * Check whether country exists.
     */
    public function exists(string $code): bool
    {
        return Country::where('country_code', strtoupper($code))->exists();
    }

    /**
     * Get all active countries.
     */
    public function all()
    {
        return Country::active()
            ->orderBy('country_name_en')
            ->get();
    }

    /**
     * Get countries by region.
     */
    public function byRegion(string $region)
    {
        return Country::active()
            ->where('region_code', strtoupper($region))
            ->orderBy('country_name_en')
            ->get();
    }

    /**
     * Get countries by sub region.
     */
    public function bySubRegion(string $subRegion)
    {
        return Country::active()
            ->where('sub_region_en', $subRegion)
            ->orderBy('country_name_en')
            ->get();
    }
}