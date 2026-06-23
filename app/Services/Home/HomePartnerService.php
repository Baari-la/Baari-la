<?php

namespace App\Services\Home;

use App\Models\IndustryPartner;
use Illuminate\Support\Facades\DB;

class HomePartnerService
{
    public function getData(): array
    {
        return [
            'featuredPartner'   => IndustryPartner::where('is_active', true)->where('partner_level', 'gold')->first(),
            'industrySolutions' => IndustryPartner::where('is_active', true)->take(6)->get(),
            'partnershipItems'  => DB::table('partnerships')->orderBy('match_percentage', 'desc')->get(),
            'regulations'       => DB::table('regulations')->orderBy('event_date', 'desc')->get(),
            'inventoryItems'    => DB::table('inventories')->orderBy('created_at', 'desc')->get(),
        ];
    }
}