<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       // Cari perusahaan yang verifikasinya berumur tepat 11 bulan (H-30 hari)
    $expiringCompanies = \App\Models\Company::whereNotNull('last_verified_at')
        ->where('last_verified_at', '<=', now()->subMonths(11))
        ->where('last_verified_at', '>', now()->subMonths(11)->subDay())
        ->get();

    foreach ($expiringCompanies as $company) {
        // Logika kirim email pengingat di sini
        // Mail::to($company->email_web)->send(new ExpiryReminderMail($company));
        
        $this->info("Reminder sent to: " . $company->nama_perusahaan);
    }
    }
}