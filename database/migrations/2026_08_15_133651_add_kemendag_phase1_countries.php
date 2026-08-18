<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {

            $countries = [
                [
                    'country_code' => 'MU',
                    'iso3' => 'MUS',
                    'country_name_en' => 'Mauritius',
                    'country_name_id' => 'Mauritius',
                ],
                [
                    'country_code' => 'LT',
                    'iso3' => 'LTU',
                    'country_name_en' => 'Lithuania',
                    'country_name_id' => 'Lituania',
                ],
                [
                    'country_code' => 'KZ',
                    'iso3' => 'KAZ',
                    'country_name_en' => 'Kazakhstan',
                    'country_name_id' => 'Kazakhstan',
                ],
                [
                    'country_code' => 'AZ',
                    'iso3' => 'AZE',
                    'country_name_en' => 'Azerbaijan',
                    'country_name_id' => 'Azerbaijan',
                ],
                [
                    'country_code' => 'GE',
                    'iso3' => 'GEO',
                    'country_name_en' => 'Georgia',
                    'country_name_id' => 'Georgia',
                ],
                [
                    'country_code' => 'MT',
                    'iso3' => 'MLT',
                    'country_name_en' => 'Malta',
                    'country_name_id' => 'Malta',
                ],
                [
                    'country_code' => 'DJ',
                    'iso3' => 'DJI',
                    'country_name_en' => 'Djibouti',
                    'country_name_id' => 'Djibouti',
                ],
                [
                    'country_code' => 'MC',
                    'iso3' => 'MCO',
                    'country_name_en' => 'Monaco',
                    'country_name_id' => 'Monako',
                ],
                [
                    'country_code' => 'SK',
                    'iso3' => 'SVK',
                    'country_name_en' => 'Slovakia',
                    'country_name_id' => 'Slovakia',
                ],
                [
                    'country_code' => 'AL',
                    'iso3' => 'ALB',
                    'country_name_en' => 'Albania',
                    'country_name_id' => 'Albania',
                ],
                [
                    'country_code' => 'MZ',
                    'iso3' => 'MOZ',
                    'country_name_en' => 'Mozambique',
                    'country_name_id' => 'Mozambik',
                ],
                [
                    'country_code' => 'MD',
                    'iso3' => 'MDA',
                    'country_name_en' => 'Moldova',
                    'country_name_id' => 'Moldova',
                ],
                [
                    'country_code' => 'GN',
                    'iso3' => 'GIN',
                    'country_name_en' => 'Guinea',
                    'country_name_id' => 'Guinea',
                ],
                [
                    'country_code' => 'UZ',
                    'iso3' => 'UZB',
                    'country_name_en' => 'Uzbekistan',
                    'country_name_id' => 'Uzbekistan',
                ],
                [
                    'country_code' => 'BA',
                    'iso3' => 'BIH',
                    'country_name_en' => 'Bosnia and Herzegovina',
                    'country_name_id' => 'Bosnia dan Herzegovina',
                ],
                [
                    'country_code' => 'CG',
                    'iso3' => 'COG',
                    'country_name_en' => 'Republic of the Congo',
                    'country_name_id' => 'Kongo',
                ],
                [
                    'country_code' => 'BY',
                    'iso3' => 'BLR',
                    'country_name_en' => 'Belarus',
                    'country_name_id' => 'Belarus',
                ],
                [
                    'country_code' => 'AG',
                    'iso3' => 'ATG',
                    'country_name_en' => 'Antigua and Barbuda',
                    'country_name_id' => 'Antigua dan Barbuda',
                ],
                [
                    'country_code' => 'BJ',
                    'iso3' => 'BEN',
                    'country_name_en' => 'Benin',
                    'country_name_id' => 'Benin',
                ],
                [
                    'country_code' => 'ME',
                    'iso3' => 'MNE',
                    'country_name_en' => 'Montenegro',
                    'country_name_id' => 'Montenegro',
                ],
                [
                    'country_code' => 'AM',
                    'iso3' => 'ARM',
                    'country_name_en' => 'Armenia',
                    'country_name_id' => 'Armenia',
                ],
                [
                    'country_code' => 'SL',
                    'iso3' => 'SLE',
                    'country_name_en' => 'Sierra Leone',
                    'country_name_id' => 'Sierra Leone',
                ],
                [
                    'country_code' => 'AO',
                    'iso3' => 'AGO',
                    'country_name_en' => 'Angola',
                    'country_name_id' => 'Angola',
                ],
                [
                    'country_code' => 'LR',
                    'iso3' => 'LBR',
                    'country_name_en' => 'Liberia',
                    'country_name_id' => 'Liberia',
                ],
                [
                    'country_code' => 'GM',
                    'iso3' => 'GMB',
                    'country_name_en' => 'Gambia',
                    'country_name_id' => 'Gambia',
                ],
                [
                    'country_code' => 'KG',
                    'iso3' => 'KGZ',
                    'country_name_en' => 'Kyrgyzstan',
                    'country_name_id' => 'Kirgizstan',
                ],
                [
                    'country_code' => 'ML',
                    'iso3' => 'MLI',
                    'country_name_en' => 'Mali',
                    'country_name_id' => 'Mali',
                ],
                [
                    'country_code' => 'BI',
                    'iso3' => 'BDI',
                    'country_name_en' => 'Burundi',
                    'country_name_id' => 'Burundi',
                ],
                [
                    'country_code' => 'SZ',
                    'iso3' => 'SWZ',
                    'country_name_en' => 'Eswatini',
                    'country_name_id' => 'Eswatini',
                ],
                [
                    'country_code' => 'BF',
                    'iso3' => 'BFA',
                    'country_name_en' => 'Burkina Faso',
                    'country_name_id' => 'Burkina Faso',
                ],
                [
                    'country_code' => 'GD',
                    'iso3' => 'GRD',
                    'country_name_en' => 'Grenada',
                    'country_name_id' => 'Grenada',
                ],
                [
                    'country_code' => 'SY',
                    'iso3' => 'SYR',
                    'country_name_en' => 'Syria',
                    'country_name_id' => 'Suriah',
                ],
                [
                    'country_code' => 'ZW',
                    'iso3' => 'ZWE',
                    'country_name_en' => 'Zimbabwe',
                    'country_name_id' => 'Zimbabwe',
                ],
                [
                    'country_code' => 'LS',
                    'iso3' => 'LSO',
                    'country_name_en' => 'Lesotho',
                    'country_name_id' => 'Lesotho',
                ],
                [
                    'country_code' => 'NA',
                    'iso3' => 'NAM',
                    'country_name_en' => 'Namibia',
                    'country_name_id' => 'Namibia',
                ],
            ];

            $aliasMap = [
                'MAURITIUS' => 'MUS',
                'LITHUANIA' => 'LTU',
                'KAZAKHSTAN' => 'KAZ',
                'AZERBAIJAN' => 'AZE',
                'GEORGIA' => 'GEO',
                'MALTA' => 'MLT',
                'DJIBOUTI' => 'DJI',
                'MONAKO' => 'MCO',
                'SLOVAKIA' => 'SVK',
                'ALBANIA' => 'ALB',
                'MOZAMBIK' => 'MOZ',
                'MOLDOVA' => 'MDA',
                'GUINEA' => 'GIN',
                'UZBEKISTAN' => 'UZB',
                'BOSNIA DAN HERZEGOVINA' => 'BIH',
                'KONGO' => 'COG',
                'BELARUS' => 'BLR',
                'ANTIGUA DAN BARBUDA' => 'ATG',
                'BENIN' => 'BEN',
                'MONTENEGRO' => 'MNE',
                'ARMENIA' => 'ARM',
                'SIERA LEONE' => 'SLE',
                'ANGOLA' => 'AGO',
                'LIBERIA' => 'LBR',
                'GAMBIA' => 'GMB',
                'KYRGYZSTAN' => 'KGZ',
                'MALI' => 'MLI',
                'BURUNDI' => 'BDI',
                'SWAZILAND' => 'SWZ',
                'BURKINA FASO' => 'BFA',
                'GRENADA' => 'GRD',
                'SIRIA' => 'SYR',
                'ZIMBABWE' => 'ZWE',
                'LESOTHO' => 'LSO',
                'NAMIBIA' => 'NAM',
            ];

            $countryIds = [];

            foreach ($countries as $country) {

                $existing =
                    DB::table('mst_countries')
                        ->where(
                            'country_code',
                            $country['country_code']
                        )
                        ->first();

                if ($existing !== null) {

                    if (
                        strtoupper(
                            (string) $existing->iso3
                        )
                        !==
                        strtoupper(
                            (string) $country['iso3']
                        )
                    ) {
                        throw new RuntimeException(
                            'Country code conflict for '
                            . $country['country_code']
                            . '. Existing ISO3='
                            . ($existing->iso3 ?? 'NULL')
                            . ', expected='
                            . $country['iso3']
                        );
                    }

                    $countryIds[
                        $country['iso3']
                    ] = (int) $existing->id;

                    continue;
                }

                $id =
                    DB::table('mst_countries')
                        ->insertGetId([
                            'country_code' =>
                                $country['country_code'],

                            'iso3' =>
                                $country['iso3'],

                            'country_name_en' =>
                                $country['country_name_en'],

                            'country_name_id' =>
                                $country['country_name_id'],

                            'official_name' =>
                                null,

                            'region_code' =>
                                null,

                            'region_en' =>
                                null,

                            'region_id' =>
                                null,

                            'sub_region_en' =>
                                null,

                            'sub_region_id' =>
                                null,

                            'flag_emoji' =>
                                null,

                            'is_active' =>
                                true,

                            'created_at' =>
                                now(),

                            'updated_at' =>
                                now(),
                        ]);

                $countryIds[
                    $country['iso3']
                ] = (int) $id;
            }

            /*
             * Insert KEMENDAG aliases idempotently.
             */
            foreach ($aliasMap as $sourceName => $iso3) {

                if (
                    !isset(
                        $countryIds[$iso3]
                    )
                ) {
                    throw new RuntimeException(
                        'Country ID belum tersedia untuk ISO3 '
                        . $iso3
                        . ' alias '
                        . $sourceName
                    );
                }

                $normalized =
                    strtoupper(
                        trim(
                            preg_replace(
                                '/\s+/',
                                ' ',
                                $sourceName
                            ) ?? ''
                        )
                    );

                $existingAlias =
                    DB::table(
                        'trade_country_aliases'
                    )
                        ->where(
                            'source_system',
                            'KEMENDAG'
                        )
                        ->where(
                            'normalized_name',
                            $normalized
                        )
                        ->first();

                if ($existingAlias === null) {

                    DB::table(
                        'trade_country_aliases'
                    )->insert([
                        'country_id' =>
                            $countryIds[$iso3],

                        'source_name' =>
                            $sourceName,

                        'normalized_name' =>
                            $normalized,

                        'source_system' =>
                            'KEMENDAG',

                        'is_active' =>
                            true,

                        'created_at' =>
                            now(),

                        'updated_at' =>
                            now(),
                    ]);

                } else {

                    if (
                        (int) $existingAlias->country_id
                        !==
                        $countryIds[$iso3]
                    ) {
                        throw new RuntimeException(
                            'Alias conflict for '
                            . $sourceName
                            . '. Existing country_id='
                            . $existingAlias->country_id
                            . ', expected='
                            . $countryIds[$iso3]
                        );
                    }
                }
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {

            $aliases = [
                'MAURITIUS',
                'LITHUANIA',
                'KAZAKHSTAN',
                'AZERBAIJAN',
                'GEORGIA',
                'MALTA',
                'DJIBOUTI',
                'MONAKO',
                'SLOVAKIA',
                'ALBANIA',
                'MOZAMBIK',
                'MOLDOVA',
                'GUINEA',
                'UZBEKISTAN',
                'BOSNIA DAN HERZEGOVINA',
                'KONGO',
                'BELARUS',
                'ANTIGUA DAN BARBUDA',
                'BENIN',
                'MONTENEGRO',
                'ARMENIA',
                'SIERA LEONE',
                'ANGOLA',
                'LIBERIA',
                'GAMBIA',
                'KYRGYZSTAN',
                'MALI',
                'BURUNDI',
                'SWAZILAND',
                'BURKINA FASO',
                'GRENADA',
                'SIRIA',
                'ZIMBABWE',
                'LESOTHO',
                'NAMIBIA',
            ];

            DB::table(
                'trade_country_aliases'
            )
                ->where(
                    'source_system',
                    'KEMENDAG'
                )
                ->whereIn(
                    'normalized_name',
                    $aliases
                )
                ->delete();

            $iso3 = [
                'MUS',
                'LTU',
                'KAZ',
                'AZE',
                'GEO',
                'MLT',
                'DJI',
                'MCO',
                'SVK',
                'ALB',
                'MOZ',
                'MDA',
                'GIN',
                'UZB',
                'BIH',
                'COG',
                'BLR',
                'ATG',
                'BEN',
                'MNE',
                'ARM',
                'SLE',
                'AGO',
                'LBR',
                'GMB',
                'KGZ',
                'MLI',
                'BDI',
                'SWZ',
                'BFA',
                'GRD',
                'SYR',
                'ZWE',
                'LSO',
                'NAM',
            ];

            DB::table('mst_countries')
                ->whereIn(
                    'iso3',
                    $iso3
                )
                ->delete();
        });
    }
};