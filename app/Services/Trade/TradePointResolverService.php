<?php

declare(strict_types=1);

namespace App\Services\Trade;

use Illuminate\Support\Facades\DB;

class TradePointResolverService
{
    /**
     * @var array<string, array{
     *     id:int,
     *     code:string,
     *     name:string,
     *     name_en:?string,
     *     trade_point_type_id:?int,
     *     province_id:?int,
     *     city:?string
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
     * Load canonical Trade Points + active aliases once.
     */
    protected function load(): void
    {
        if ($this->loaded) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Canonical Trade Point Master
        |--------------------------------------------------------------------------
        */

        $tradePoints = DB::table('trade_points')
            ->where('is_active', true)
            ->get([
                'id',
                'code',
                'name',
                'name_en',
                'trade_point_type_id',
                'province_id',
                'city',
            ]);

        foreach ($tradePoints as $tradePoint) {

            $record = [
                'id' =>
                    (int) $tradePoint->id,

                'code' =>
                    (string) $tradePoint->code,

                'name' =>
                    (string) $tradePoint->name,

                'name_en' =>
                    $tradePoint->name_en !== null
                        ? (string) $tradePoint->name_en
                        : null,

                'trade_point_type_id' =>
                    $tradePoint->trade_point_type_id !== null
                        ? (int) $tradePoint->trade_point_type_id
                        : null,

                'province_id' =>
                    $tradePoint->province_id !== null
                        ? (int) $tradePoint->province_id
                        : null,

                'city' =>
                    $tradePoint->city !== null
                        ? (string) $tradePoint->city
                        : null,
            ];

            /*
            |--------------------------------------------------------------------------
            | Exact canonical names
            |--------------------------------------------------------------------------
            */

            foreach ([
                $tradePoint->name,
                $tradePoint->name_en,
                $tradePoint->code,
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
        | Active aliases
        |--------------------------------------------------------------------------
        */

        $aliases = DB::table(
            'trade_point_aliases as a'
        )
            ->join(
                'trade_points as tp',
                'tp.id',
                '=',
                'a.trade_point_id'
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
                'tp.is_active',
                true
            )
            ->get([
                'a.normalized_name',
                'a.trade_point_id',
                'tp.id',
                'tp.code',
                'tp.name',
                'tp.name_en',
                'tp.trade_point_type_id',
                'tp.province_id',
                'tp.city',
            ]);

        foreach ($aliases as $alias) {

            $normalized =
                $this->normalize(
                    (string) $alias->normalized_name
                );

            if ($normalized === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Return canonical trade_point ID, not alias row ID.
            |--------------------------------------------------------------------------
            */

            $this->lookup[$normalized] = [
                'id' =>
                    (int) $alias->trade_point_id,

                'code' =>
                    (string) $alias->code,

                'name' =>
                    (string) $alias->name,

                'name_en' =>
                    $alias->name_en !== null
                        ? (string) $alias->name_en
                        : null,

                'trade_point_type_id' =>
                    $alias->trade_point_type_id !== null
                        ? (int) $alias->trade_point_type_id
                        : null,

                'province_id' =>
                    $alias->province_id !== null
                        ? (int) $alias->province_id
                        : null,

                'city' =>
                    $alias->city !== null
                        ? (string) $alias->city
                        : null,
            ];
        }

        $this->loaded = true;
    }

    /**
     * Resolve Trade Point.
     *
     * Optional provinceId is used as a safety/context constraint.
     */
    public function resolve(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG',
        ?int $provinceId = null
    ): ?array {

        /*
        |--------------------------------------------------------------------------
        | Currently only KEMENDAG aliases are loaded.
        |--------------------------------------------------------------------------
        */

        $this->load();

        $normalized =
            $this->normalize($sourceName);

        if ($normalized === '') {
            return null;
        }

        $result =
            $this->lookup[$normalized]
            ?? null;

        if ($result === null) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Conditional province validation
        |--------------------------------------------------------------------------
        |
        | If a province context is supplied, a trade point belonging to a
        | different province must NOT resolve.
        |--------------------------------------------------------------------------
        */

        if (
            $provinceId !== null
            &&
            $result['province_id'] !== null
            &&
            (int) $result['province_id'] !== $provinceId
        ) {
            return null;
        }

        return $result;
    }

    public function resolveId(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG',
        ?int $provinceId = null
    ): ?int {
        return
            $this->resolve(
                $sourceName,
                $sourceSystem,
                $provinceId
            )['id']
            ?? null;
    }

    public function resolveCode(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG',
        ?int $provinceId = null
    ): ?string {
        return
            $this->resolve(
                $sourceName,
                $sourceSystem,
                $provinceId
            )['code']
            ?? null;
    }

    public function resolveTypeId(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG',
        ?int $provinceId = null
    ): ?int {
        return
            $this->resolve(
                $sourceName,
                $sourceSystem,
                $provinceId
            )['trade_point_type_id']
            ?? null;
    }

    public function resolveProvinceId(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG',
        ?int $provinceId = null
    ): ?int {
        return
            $this->resolve(
                $sourceName,
                $sourceSystem,
                $provinceId
            )['province_id']
            ?? null;
    }

    public function resolveName(
        ?string $sourceName,
        string $sourceSystem = 'KEMENDAG',
        ?int $provinceId = null
    ): ?string {
        return
            $this->resolve(
                $sourceName,
                $sourceSystem,
                $provinceId
            )['name']
            ?? null;
    }
}