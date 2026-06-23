<?php
namespace Database\Seeders;

use App\Models\MstCountry;
use Illuminate\Database\Seeder;


class MstCountryAdditionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [

            // Middle East
            [
                'country_code' => 'AE',
                'country_name' => 'United Arab Emirates',
                'region' => 'Middle East',
                'sub_region' => 'GCC',
                'flag_emoji' => '🇦🇪',
            ],
            [
                'country_code' => 'SA',
                'country_name' => 'Saudi Arabia',
                'region' => 'Middle East',
                'sub_region' => 'GCC',
                'flag_emoji' => '🇸🇦',
            ],
            [
                'country_code' => 'QA',
                'country_name' => 'Qatar',
                'region' => 'Middle East',
                'sub_region' => 'GCC',
                'flag_emoji' => '🇶🇦',
            ],
            [
                'country_code' => 'KW',
                'country_name' => 'Kuwait',
                'region' => 'Middle East',
                'sub_region' => 'GCC',
                'flag_emoji' => '🇰🇼',
            ],
            [
                'country_code' => 'OM',
                'country_name' => 'Oman',
                'region' => 'Middle East',
                'sub_region' => 'GCC',
                'flag_emoji' => '🇴🇲',
            ],
            [
                'country_code' => 'BH',
                'country_name' => 'Bahrain',
                'region' => 'Middle East',
                'sub_region' => 'GCC',
                'flag_emoji' => '🇧🇭',
            ],
            [
                'country_code' => 'JO',
                'country_name' => 'Jordan',
                'region' => 'Middle East',
                'sub_region' => 'Levant',
                'flag_emoji' => '🇯🇴',
            ],

            // Africa
            [
                'country_code' => 'ZA',
                'country_name' => 'South Africa',
                'region' => 'Africa',
                'sub_region' => 'Southern Africa',
                'flag_emoji' => '🇿🇦',
            ],
            [
                'country_code' => 'TN',
                'country_name' => 'Tunisia',
                'region' => 'Africa',
                'sub_region' => 'North Africa',
                'flag_emoji' => '🇹🇳',
            ],

            // Latin America
            [
                'country_code' => 'CO',
                'country_name' => 'Colombia',
                'region' => 'Americas',
                'sub_region' => 'South America',
                'flag_emoji' => '🇨🇴',
            ],
            [
                'country_code' => 'PE',
                'country_name' => 'Peru',
                'region' => 'Americas',
                'sub_region' => 'South America',
                'flag_emoji' => '🇵🇪',
            ],
            [
                'country_code' => 'CL',
                'country_name' => 'Chile',
                'region' => 'Americas',
                'sub_region' => 'South America',
                'flag_emoji' => '🇨🇱',
            ],
            [
                'country_code' => 'AR',
                'country_name' => 'Argentina',
                'region' => 'Americas',
                'sub_region' => 'South America',
                'flag_emoji' => '🇦🇷',
            ],

            // Central America
            [
                'country_code' => 'GT',
                'country_name' => 'Guatemala',
                'region' => 'Americas',
                'sub_region' => 'Central America',
                'flag_emoji' => '🇬🇹',
            ],
            [
                'country_code' => 'SV',
                'country_name' => 'El Salvador',
                'region' => 'Americas',
                'sub_region' => 'Central America',
                'flag_emoji' => '🇸🇻',
            ],
            [
                'country_code' => 'NI',
                'country_name' => 'Nicaragua',
                'region' => 'Americas',
                'sub_region' => 'Central America',
                'flag_emoji' => '🇳🇮',
            ],
            [
                'country_code' => 'DO',
                'country_name' => 'Dominican Republic',
                'region' => 'Americas',
                'sub_region' => 'Caribbean',
                'flag_emoji' => '🇩🇴',
            ],
        ];

        foreach ($countries as $country) {

            MstCountry::updateOrCreate(
                [
                    'country_code' => $country['country_code']
                ],
                $country
            );

        }
    }
}