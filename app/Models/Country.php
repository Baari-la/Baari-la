<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Country extends Model
{
    protected $table = 'mst_countries';

    protected $fillable = [
        'country_code',
        'iso3',
        'country_name_en',
        'country_name_id',
        'official_name',
        'region_code',
        'region_en',
        'region_id',
        'sub_region_en',
        'sub_region_id',
        'flag_emoji',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Locale Aware Attributes
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'en'
            ? $this->country_name_en
            : ($this->country_name_id ?: $this->country_name_en);
    }

    public function getDisplayRegionAttribute(): ?string
    {
        return app()->getLocale() === 'en'
            ? $this->region_en
            : ($this->region_id ?: $this->region_en);
    }

    public function getDisplaySubRegionAttribute(): ?string
    {
        return app()->getLocale() === 'en'
            ? $this->sub_region_en
            : ($this->sub_region_id ?: $this->sub_region_en);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getDisplayWithFlagAttribute(): string
    {
        return trim("{$this->flag_emoji} {$this->display_name}");
    }

    /**
 * Scope active countries.
 */
public function scopeActive(Builder $query): Builder
{
    return $query->where('is_active', true);
}
}