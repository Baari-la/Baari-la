<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class MigrateCompanyHeadOffices extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:migrate-company-head-offices';

    /**
     * The console command description.
     */
    protected $description = 'Migrate legacy company addresses into company_locations table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $created = 0;
        $skipped = 0;

        foreach (Company::cursor() as $company) {

            // Skip jika sudah punya lokasi
            if ($company->locations()->exists()) {
                $skipped++;
                continue;
            }

            // Skip jika alamat kosong
            if (empty($company->alamat_lengkap)) {
                $skipped++;
                continue;
            }

            $this->line(
    "Processing {$company->id} - {$company->nama_perusahaan}"
);
            $company->locations()->create([
                'location_name' => 'Head Office',
                'location_type' => 'head_office',
                'country_name' => 'Indonesia',
                'city_name' => $company->city,
                'address' => $company->alamat_lengkap,
                'phone' => $company->telepon,
                'email' => $company->email_web,
                'is_primary' => true,
            ]);

            $created++;
        }

        $this->info("Created: {$created}");
        $this->info("Skipped: {$skipped}");

        return self::SUCCESS;
    }
}