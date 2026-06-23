<?php

namespace App\Http\Controllers;

use App\Models\News;
use Inertia\Inertia;

class PartnerInsightController extends Controller
{
    public function index()
    {
        $featured = News::whereNotNull('partner_name')
            ->latest()
            ->first();

        $articles = News::whereNotNull('partner_name')
            ->latest()
            ->paginate(12);

        $partners = News::selectRaw("
                partner_name,
                COUNT(*) as total_articles
            ")
            ->whereNotNull('partner_name')
            ->groupBy('partner_name')
            ->orderByDesc('total_articles')
            ->get();

        return Inertia::render(
            'PartnerInsights/Index',
            [
                'featured' => $featured,
                'articles' => $articles,
                'partners' => $partners,
            ]
        );
    }

    public function show(string $partner)
{
    $articles = News::where(
            'partner_name',
            $partner
        )
        ->latest()
        ->paginate(12);

    $featured = News::where(
            'partner_name',
            $partner
        )
        ->latest()
        ->first();

    return Inertia::render(
        'PartnerInsights/Show',
        [
            'partner' => $partner,
            'featured' => $featured,
            'articles' => $articles,
        ]
    );
}
}