<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\TextileStat;
use Carbon\Carbon;

class FetchUnComtradeCompetitors extends Command
{
    protected $signature = 'comtrade:fetch-competitors {year}';
    protected $description = 'Menarik data tekstil lengkap (HS 2, 4, 6 Digit) dari UN Comtrade';

    public function handle()
    {
        $year = $this->argument('year');
        $this->info("Memulai sinkronisasi data kompetitor global untuk tahun {$year}...");

        // Pemetaan Kode Negara Standar UN Comtrade
        $competitors = [
            '360' => 'Indonesia',
            '156' => 'China',
            '704' => 'Vietnam',
            '050' => 'Bangladesh',
            '356' => 'India'
        ];

        $hsCodes = '50,51,52,53,54,55,56,57,58,59,60,61,62,63';
        
        // URL UTAMA RESMI (Menggantikan URL Preview Agar API Key Berfungsi)
        $url = "https://comtradeapi.un.org/data/v1/get/C/A/HS"; 

        $apiKey = env('UN_COMTRADE_API_KEY'); 
        if (!$apiKey) {
            $this->error("Gagal: API Key tidak terdeteksi di file .env!");
            return Command::FAILURE;
        }

        foreach ($competitors as $repCode => $countryName) {
            $this->info("Menyerap data dari server PBB untuk: {$countryName}...");

            $response = Http::withHeaders([
               'Ocp-Apim-Subscription-Key' => $apiKey
            ])->get($url, [
                'reporterCode' => $repCode, 
                'period'       => $year,
                'cmdCode'      => $hsCodes, // Nama variabel di sini sudah disamakan dengan di atas ($hsCodes)
                'partnerCode'  => 0,        // Perdagangan dengan Dunia global
            ]);

            if ($response->successful()) {
                $results = $response->json()['data'] ?? [];
                $this->info("-> Berhasil mengunduh " . count($results) . " records mentah.");

                $insertedCount = 0;

                foreach ($results as $row) {
                    $flow = strtoupper($row['flowCode'] ?? '');
                    $type = ($flow === 'M' || $flow === 'I') ? 'import' : 'export';
                    
                    $hsCode = $row['cmdCode'];
                    $length = strlen($hsCode); 

                    // Menangkap data Value USD dan Volume KG resmi dari UN Comtrade API v1
                    $valueUsd = $row['primaryValue'] ?? 0;
                    $volumeKg = $row['netWgt'] ?? 0;
                    $description = $row['cmdDescE'] ?? 'Sektor Produk Tekstil';

                    TextileStat::updateOrCreate(
                        [
                            'period'        => Carbon::createFromDate($year, 12, 1)->format('Y-m-d'),
                            'type'          => $type,
                            'reporter_code' => $repCode,
                            'hs_code'       => $hsCode,
                        ],
                        [
                            'country_name'   => $countryName,
                            'hs_digits'      => $length,
                            'hs_description' => $description,
                            'volume_kg'      => $volumeKg,
                            'value_usd'      => $valueUsd,
                        ]
                    );
                    $insertedCount++;
                }
                
                $this->info("-> Sukses memetakan {$insertedCount} baris data ke tabel.");
                sleep(2); // Proteksi akun free dari rate limiting

            } else {
                $this->error("-> Gagal terhubung untuk {$countryName}. Status: " . $response->status());
            }
        }

        $this->info("Proses sinkronisasi data ekosistem tekstil selesai!");
        return Command::SUCCESS;
    }
    }