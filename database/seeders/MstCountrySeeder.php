<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MstCountry;

class MstCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [

            // ASEAN
            [
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
                'region' => 'Asia',
                'sub_region' => 'ASEAN',
                'flag_emoji' => '🇮🇩',
            ],
            [
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
                'region' => 'Asia',
                'sub_region' => 'ASEAN',
                'flag_emoji' => '🇻🇳',
            ],
            [
                'country_code' => 'TH',
                'country_name' => 'Thailand',
                'region' => 'Asia',
                'sub_region' => 'ASEAN',
                'flag_emoji' => '🇹🇭',
            ],
            [
                'country_code' => 'MY',
                'country_name' => 'Malaysia',
                'region' => 'Asia',
                'sub_region' => 'ASEAN',
                'flag_emoji' => '🇲🇾',
            ],
            [
                'country_code' => 'SG',
                'country_name' => 'Singapore',
                'region' => 'Asia',
                'sub_region' => 'ASEAN',
                'flag_emoji' => '🇸🇬',
            ],
            [
                'country_code' => 'KH',
                'country_name' => 'Cambodia',
                'region' => 'Asia',
                'sub_region' => 'ASEAN',
                'flag_emoji' => '🇰🇭',
            ],

            // EAST ASIA
            [
                'country_code' => 'CN',
                'country_name' => 'China',
                'region' => 'Asia',
                'sub_region' => 'East Asia',
                'flag_emoji' => '🇨🇳',
            ],
            [
                'country_code' => 'TW',
                'country_name' => 'Taiwan',
                'region' => 'Asia',
                'sub_region' => 'East Asia',
                'flag_emoji' => '🇹🇼',
            ],
            [
                'country_code' => 'HK',
                'country_name' => 'Hong Kong',
                'region' => 'Asia',
                'sub_region' => 'East Asia',
                'flag_emoji' => '🇭🇰',
            ],
            [
                'country_code' => 'JP',
                'country_name' => 'Japan',
                'region' => 'Asia',
                'sub_region' => 'East Asia',
                'flag_emoji' => '🇯🇵',
            ],
            [
                'country_code' => 'KR',
                'country_name' => 'South Korea',
                'region' => 'Asia',
                'sub_region' => 'East Asia',
                'flag_emoji' => '🇰🇷',
            ],

            // SOUTH ASIA
            [
                'country_code' => 'IN',
                'country_name' => 'India',
                'region' => 'Asia',
                'sub_region' => 'South Asia',
                'flag_emoji' => '🇮🇳',
            ],
            [
                'country_code' => 'PK',
                'country_name' => 'Pakistan',
                'region' => 'Asia',
                'sub_region' => 'South Asia',
                'flag_emoji' => '🇵🇰',
            ],
            [
                'country_code' => 'BD',
                'country_name' => 'Bangladesh',
                'region' => 'Asia',
                'sub_region' => 'South Asia',
                'flag_emoji' => '🇧🇩',
            ],
            [
                'country_code' => 'LK',
                'country_name' => 'Sri Lanka',
                'region' => 'Asia',
                'sub_region' => 'South Asia',
                'flag_emoji' => '🇱🇰',
            ],

            // EUROPE
            [
                'country_code' => 'CH',
                'country_name' => 'Switzerland',
                'region' => 'Europe',
                'sub_region' => 'Western Europe',
                'flag_emoji' => '🇨🇭',
            ],
            [
                'country_code' => 'DE',
                'country_name' => 'Germany',
                'region' => 'Europe',
                'sub_region' => 'Western Europe',
                'flag_emoji' => '🇩🇪',
            ],
            [
                'country_code' => 'IT',
                'country_name' => 'Italy',
                'region' => 'Europe',
                'sub_region' => 'Southern Europe',
                'flag_emoji' => '🇮🇹',
            ],
            [
                'country_code' => 'GB',
                'country_name' => 'United Kingdom',
                'region' => 'Europe',
                'sub_region' => 'Western Europe',
                'flag_emoji' => '🇬🇧',
            ],
            [
                'country_code' => 'PT',
                'country_name' => 'Portugal',
                'region' => 'Europe',
                'sub_region' => 'Southern Europe',
                'flag_emoji' => '🇵🇹',
            ],
            [
                'country_code' => 'TR',
                'country_name' => 'Turkey',
                'region' => 'Europe',
                'sub_region' => 'Eurasia',
                'flag_emoji' => '🇹🇷',
            ],

            // AMERICAS
            [
                'country_code' => 'US',
                'country_name' => 'United States',
                'region' => 'Americas',
                'sub_region' => 'North America',
                'flag_emoji' => '🇺🇸',
            ],
            [
                'country_code' => 'MX',
                'country_name' => 'Mexico',
                'region' => 'Americas',
                'sub_region' => 'North America',
                'flag_emoji' => '🇲🇽',
            ],
            [
                'country_code' => 'BR',
                'country_name' => 'Brazil',
                'region' => 'Americas',
                'sub_region' => 'South America',
                'flag_emoji' => '🇧🇷',
            ],
            [
                'country_code' => 'HN',
                'country_name' => 'Honduras',
                'region' => 'Americas',
                'sub_region' => 'Central America',
                'flag_emoji' => '🇭🇳',
            ],

            // AFRICA
            [
                'country_code' => 'ET',
                'country_name' => 'Ethiopia',
                'region' => 'Africa',
                'sub_region' => 'East Africa',
                'flag_emoji' => '🇪🇹',
            ],
            [
                'country_code' => 'KE',
                'country_name' => 'Kenya',
                'region' => 'Africa',
                'sub_region' => 'East Africa',
                'flag_emoji' => '🇰🇪',
            ],
            [
                'country_code' => 'EG',
                'country_name' => 'Egypt',
                'region' => 'Africa',
                'sub_region' => 'North Africa',
                'flag_emoji' => '🇪🇬',
            ],
            [
                'country_code' => 'MA',
                'country_name' => 'Morocco',
                'region' => 'Africa',
                'sub_region' => 'North Africa',
                'flag_emoji' => '🇲🇦',
            ],
        ];

        MstCountry::insert($countries);
    }
}