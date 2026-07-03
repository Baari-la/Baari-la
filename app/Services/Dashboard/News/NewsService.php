<?php

declare(strict_types=1);

namespace App\Services\Dashboard\News;

use App\Models\News;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Dashboard News Service
 * ==========================================================================
 *
 * Business service for Dashboard News.
 *
 * Responsibilities:
 *
 * - Latest News
 * - Featured News
 * - Category News
 * - Dashboard Statistics
 *
 * Performance:
 *
 * • Only ONE database query
 * • Collection-based filtering
 * • Cache-friendly
 *
 * Used by:
 *
 * - NewsCacheService
 * - HomeIntelligenceService
 * - Dashboard
 * - REST API
 */
class NewsService
{
    /**
     * Dashboard News Collection.
     */
    protected Collection $news;

    /**
     * Number of records loaded from database.
     *
     * Can later be moved into config().
     */
    protected int $dashboardLimit = 100;

    /**
     * Dashboard Categories.
     *
     * key => database category
     */
    protected array $dashboardCategories = [

        'industryNews'       => 'Industry News',

        'technologyNews'     => 'Technology & Innovation',

        'marketNews'         => 'Market Intelligence',

        'tradePolicyNews'    => 'Trade & Policy',

        'sustainabilityNews' => 'Sustainability',

    ];

    /**
     * --------------------------------------------------------------------------
     * Dashboard Dataset
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        /*
        |--------------------------------------------------------------------------
        | ONE Database Query
        |--------------------------------------------------------------------------
        */

        $this->news = News::query()

            ->latest('created_at')

            ->limit($this->dashboardLimit)

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Dynamic Categories
        |--------------------------------------------------------------------------
        */

        $categories = [];

        foreach ($this->dashboardCategories as $key => $category) {

            $categories[$key] = $this->category($category);

        }

        /*
        |--------------------------------------------------------------------------
        | Final Dataset
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            'latestNews' => $this->latestNews(),

            'featuredNews' => $this->featuredNews(),

            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            ...$categories,

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'statistics' => $this->statistics(),

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Latest News
     * --------------------------------------------------------------------------
     */
    protected function latestNews(): Collection
    {
        return $this->news

            ->take(8)

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Featured News
     * --------------------------------------------------------------------------
     */
    protected function featuredNews(): Collection
    {
        return $this->news

            ->take(3)

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Category Dataset
     * --------------------------------------------------------------------------
     */
    protected function category(
        string $category,
        int $limit = 4
    ): Collection {

        return $this->news

            ->where('category', $category)

            ->take($limit)

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Dashboard Statistics
     * --------------------------------------------------------------------------
     */
    protected function statistics(): array
    {
        return [

            'totalNews' => $this->news->count(),

            'totalCategories' => $this->news

                ->pluck('category')

                ->filter()

                ->unique()

                ->count(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Available Categories
     * --------------------------------------------------------------------------
     */
    public function categories(): Collection
    {
        return $this->news

            ->pluck('category')

            ->filter()

            ->unique()

            ->sort()

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Configure Dashboard Limit
     * --------------------------------------------------------------------------
     */
    public function setDashboardLimit(int $limit): static
    {
        $this->dashboardLimit = $limit;

        return $this;
    }
}