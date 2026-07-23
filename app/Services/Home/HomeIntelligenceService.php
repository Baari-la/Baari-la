<?php

namespace App\Services\Home;

use App\Models\News;
use App\Models\Company;

class HomeIntelligenceService
{
    public function getData(): array
    {
        $categories = ['Market Intelligence', 'Trade & Policy', 'Sustainability', 'Technology & Innovation', 'Industry News'];
        $newsFeed   = [];
        
        foreach ($categories as $cat) {
            $key = str_replace(' & ', '', lcfirst(ucwords($cat)));
            $key = str_replace(' ', '', $key); 
            $newsFeed[$key] = News::where('category', $cat)->latest()->take(4)->get();
        }

        $intelligencePayload = [
            'latestNews'         => News::latest()->take(3)->get(),
            'latestIntelligence' => News::latest()->take(8)->get(),
      
            'textileTaxonomy'    => config(
                'textile_taxonomy'
            ),
   
            'intelligenceStats'  => [
                'reports'   => News::count(),
                'companies' => Company::count(),
                'markets'   => \DB::table('company_markets')->count(),
                'desks'     => News::distinct('category')->count('category'),
            ]
        ];

        return array_merge($intelligencePayload, $newsFeed);
    }
}