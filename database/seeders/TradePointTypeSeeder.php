<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradePointTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'code' => 'SEA_PORT',
                'name' => 'Pelabuhan Laut',
                'name_en' => 'Sea Port',
                'description' => 'Seaport used for international export and import activities.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'AIRPORT',
                'name' => 'Bandara',
                'name_en' => 'Airport',
                'description' => 'Airport used for international export and import activities.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'LAND_BORDER',
                'name' => 'Perbatasan Darat',
                'name_en' => 'Land Border',
                'description' => 'Land border crossing used for international trade activities.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'DRY_PORT',
                'name' => 'Pelabuhan Darat',
                'name_en' => 'Dry Port',
                'description' => 'Inland dry port or inland container terminal used for international trade logistics.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'code' => 'INLAND_TERMINAL',
                'name' => 'Terminal Pedalaman',
                'name_en' => 'Inland Terminal',
                'description' => 'Inland logistics terminal connected to international trade flows.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'code' => 'OTHER',
                'name' => 'Lainnya',
                'name_en' => 'Other',
                'description' => 'Other trade points that do not fit the primary classifications.',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        DB::transaction(function () use ($types): void {
            foreach ($types as $type) {
                DB::table('trade_point_types')->updateOrInsert(
                    [
                        'code' => $type['code'],
                    ],
                    [
                        'name' => $type['name'],
                        'name_en' => $type['name_en'],
                        'description' => $type['description'],
                        'is_active' => $type['is_active'],
                        'sort_order' => $type['sort_order'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });

        echo PHP_EOL;
        echo "Trade Point Types seeded successfully." . PHP_EOL;
    }
}