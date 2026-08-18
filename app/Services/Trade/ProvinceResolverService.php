<?php

declare(strict_types=1);

namespace App\Services\Trade;

use Illuminate\Support\Facades\DB;

class ProvinceResolverService
{
    /**
     * @var array<string, array{
     *     id:int,
     *     code:string,
     *     name:string,
     *     name_en:string
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

    protected function load(): void
    {
        if ($this->loaded) {
            return;
        }

        $provinces = DB::table('provinces')
            ->where('is_active', true)
            ->get([
                'id',
                'code',
                'name',
                'name_en',
            ]);

        foreach ($provinces as $province) {
            $record = [
                'id' =>
                    (int) $province->id,

                'code' =>
                    strtoupper(
                        trim(
                            (string) $province->code
                        )
                    ),

                'name' =>
                    (string) $province->name,

                'name_en' =>
                    (string) $province->name_en,
            ];

            foreach ([
                $province->name,
                $province->name_en,
                $province->code,
            ] as $candidate) {

                $normalized =
                    $this->normalize(
                        (string) $candidate
                    );

                if ($normalized !== '') {
                    $this->lookup[$normalized] = $record;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Historical Kemendag aliases
        |--------------------------------------------------------------------------
        */

        $aliases = [
            'D.I. YOGYAKARTA' =>
                'ID-YO',

            'NANGROE ACEH DARUSALAM' =>
                'ID-AC',
           
            'BANGKA BELITUNG' =>
                'ID-BB',
        ];

        foreach ($aliases as $sourceName => $provinceCode) {
            $normalized =
                $this->normalize($sourceName);

            $record =
                $this->findByCode($provinceCode);

            if (
                $normalized !== ''
                &&
                $record !== null
            ) {
                $this->lookup[$normalized] = $record;
            }
        }

        $this->loaded = true;
    }

    protected function findByCode(
        string $code
    ): ?array {
        $code = strtoupper(trim($code));

        foreach ($this->lookup as $record) {
            if ($record['code'] === $code) {
                return $record;
            }
        }

        return null;
    }

    public function resolve(
        ?string $sourceName
    ): ?array {
        $this->load();

        $normalized =
            $this->normalize($sourceName);

        if ($normalized === '') {
            return null;
        }

        return
            $this->lookup[$normalized]
            ?? null;
    }

    public function resolveId(
        ?string $sourceName
    ): ?int {
        return
            $this->resolve($sourceName)['id']
            ?? null;
    }

    public function resolveCode(
        ?string $sourceName
    ): ?string {
        return
            $this->resolve($sourceName)['code']
            ?? null;
    }

    public function resolveName(
        ?string $sourceName
    ): ?string {
        return
            $this->resolve($sourceName)['name']
            ?? null;
    }
}