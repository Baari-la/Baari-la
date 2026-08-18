<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\MstCountry;
use App\Models\TradeCountryAlias;
use Illuminate\Support\Facades\DB;

class CountryResolverService
{
    /**
     * @var array<string, array{
     *     id:int,
     *     country_code:string,
     *     iso3:?string,
     *     country_name_en:?string,
     *     country_name_id:?string
     * }>
     */
    protected array $lookup = [];

    protected bool $loaded = false;

    public function normalize(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        ) ?? '';

        return mb_strtoupper($value);
    }

    /**
     * Load the complete country resolver universe once.
     */
    protected function load(): void
    {
        if ($this->loaded) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Load country master once
        |--------------------------------------------------------------------------
        */

        $countries = DB::table('mst_countries')
            ->where('is_active', true)
            ->get([
                'id',
                'country_code',
                'iso3',
                'country_name_en',
                'country_name_id',
            ]);

        foreach ($countries as $country) {

            $record = [
                'id' =>
                    (int) $country->id,

                'country_code' =>
                    strtoupper(
                        trim(
                            (string) $country->country_code
                        )
                    ),

                'iso3' =>
                    $country->iso3 !== null
                        ? strtoupper(
                            trim(
                                (string) $country->iso3
                            )
                        )
                        : null,

                'country_name_en' =>
                    $country->country_name_en,

                'country_name_id' =>
                    $country->country_name_id,
            ];

            /*
            |--------------------------------------------------------------------------
            | Exact master-name lookup
            |--------------------------------------------------------------------------
            */

            foreach ([
                $country->country_name_en,
                $country->country_name_id,
                $country->country_code,
                $country->iso3,
            ] as $candidate) {

                $normalized =
                    $this->normalize(
                        (string) $candidate
                    );

                if ($normalized !== '') {
                    $this->lookup[$normalized] =
                        $record;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Load aliases once
        |--------------------------------------------------------------------------
        */

        $aliases = DB::table('trade_country_aliases as a')
            ->join(
                'mst_countries as c',
                'c.id',
                '=',
                'a.country_id'
            )
            ->where(
                'a.source_system',
                'KEMENDAG'
            )
            ->where(
                'a.is_active',
                true
            )
            ->where(
                'c.is_active',
                true
            )
            ->get([
                'a.normalized_name',
                'c.id',
                'c.country_code',
                'c.iso3',
                'c.country_name_en',
                'c.country_name_id',
            ]);

        foreach ($aliases as $alias) {

            $normalized =
                $this->normalize(
                    (string) $alias->normalized_name
                );

            if ($normalized === '') {
                continue;
            }

            $this->lookup[$normalized] = [
                'id' =>
                    (int) $alias->id,

                'country_code' =>
                    strtoupper(
                        trim(
                            (string) $alias->country_code
                        )
                    ),

                'iso3' =>
                    $alias->iso3 !== null
                        ? strtoupper(
                            trim(
                                (string) $alias->iso3
                            )
                        )
                        : null,

                'country_name_en' =>
                    $alias->country_name_en,

                'country_name_id' =>
                    $alias->country_name_id,
            ];
        }

        $this->loaded = true;
    }

    /**
     * Resolve source country to a lightweight country object.
     */
    public function resolve(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG'
    ): ?MstCountry {
        $this->load();

        $normalized =
            $this->normalize($sourceName);

        if ($normalized === '') {
            return null;
        }

        $record =
            $this->lookup[$normalized]
            ?? null;

        if ($record === null) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Hydrate only when caller actually asks for a model.
        |--------------------------------------------------------------------------
        */

        $country = new MstCountry();

        $country->id =
            $record['id'];

        $country->country_code =
            $record['country_code'];

        $country->iso3 =
            $record['iso3'];

        $country->country_name_en =
            $record['country_name_en'];

        $country->country_name_id =
            $record['country_name_id'];

        return $country;
    }

    public function resolveId(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG'
    ): ?int {
        $this->load();

        $normalized =
            $this->normalize($sourceName);

        if ($normalized === '') {
            return null;
        }

        return
            $this->lookup[$normalized]['id']
            ?? null;
    }

    public function resolveCode(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG'
    ): ?string {
        $this->load();

        $normalized =
            $this->normalize($sourceName);

        if ($normalized === '') {
            return null;
        }

        return
            $this->lookup[$normalized]['country_code']
            ?? null;
    }
}