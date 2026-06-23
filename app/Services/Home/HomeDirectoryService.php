<?php

namespace App\Services\Home;

use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\CompanyMarket;

class HomeDirectoryService
{
    public function getData(): array
    {
        return [
            'directoryStats' => [
                'companies'       => Company::count(),
                'products'        => CompanyProduct::count(),
                'markets'         => CompanyMarket::count(),
                'exportCompanies' => Company::has('markets')->count(),
            ],
            'pendingCount' => auth()->check() ? Company::where('status_verifikasi', 'pending')->count() : 0,
        ];
    }
}