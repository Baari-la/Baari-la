<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryMasterSeeder extends Seeder
{
    public function run(): void
    {
        $json = json_decode(
            file_get_contents(
                database_path('master-data/countries.json')
            ),
            true
        );

        DB::table('mst_countries')->truncate();

        DB::table('mst_countries')->insert(
            $json['data']
        );
    }
}