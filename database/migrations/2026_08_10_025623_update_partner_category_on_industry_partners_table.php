<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE industry_partners
            MODIFY partner_category ENUM(
                'machinery',
                'testing_certification',
                'technology',
                'energy',
                'logistics',
                'erp_plm',
                'ai_digital',
                'digital_printing',
                'sustainability',
                'raw_material',
                'finance',
                'association',
                'institution'
            )
            NOT NULL
            DEFAULT 'technology'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE industry_partners
            MODIFY partner_category ENUM(
                'testing_certification',
                'technology',
                'machinery',
                'raw_material',
                'logistics',
                'finance',
                'institution',
                'association'
            )
            NOT NULL
            DEFAULT 'technology'
        ");
    }
};